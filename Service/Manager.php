<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Ilis\Bundle\PaymentBundle\Entity\Transaction;
use Ilis\Bundle\PaymentBundle\Entity\Method;
use Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard as CreditCardTransaction;
use Ilis\Bundle\PaymentBundle\Entity\Transaction\Paypal as PaypalTransaction;
use Ilis\Bundle\PaymentBundle\Exception\Exception;
use Ilis\Bundle\PaymentBundle\Processor\ProcessorFactory;
use Ilis\Bundle\PaymentBundle\PaymentEvents;
use Ilis\Bundle\PaymentBundle\Event\TransactionProcessedEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\BuyNow;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Manager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContainerAwareEventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ArrrayCollection
     */
    protected $methods;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em, ContainerAwareEventDispatcher $dispatcher, Router $router, Logger $logger)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->methods = new ArrayCollection();
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     * @param array $methodConfig
     */
    public function addMethod($code, array $methodConfig)
    {

        $method = new Method();
        $method->setCode($code);

        foreach($methodConfig as $key => $value)
            $method->addAttribute($key, $value);

        $this->methods->add($method);

    }

    /**
     * @param mixed $method
     * @return bool
     */
    public function methodIsAvailable($method)
    {
        foreach ($this->methods as $availableMethod)
            if ($method instanceof Method && $availableMethod->getCode() === $method->getCode())
                return true;
            elseif (is_string($method) && $availableMethod->getCode() === $method)
                return true;

        return false;
    }

    /**
     * @param bool $onlyAvailable
     * @return ArrayCollection
     */
    public function getPaymentMethods()
    {
        return $this->methods;
    }

    /**
     * @param Transaction $transaction
     * @throws Exception
     */
    public function processTransaction(Transaction $transaction)
    {
        $method = $transaction->getMethod();

        if (false === $this->methodIsAvailable($method))
            throw new Exception(sprintf(
                'The payment method "%s" is not available',
                $method->getName()
            ));

        switch(true){

            case $transaction instanceof CreditCardTransaction:
               $this->processCreditCardTransaction($transaction);
               break;
            case $transaction instanceof PaypalTransaction:
                $this->processPaypalTransaction($transaction);
                break;
            default:
                throw new Exception(sprintf(
                    'Unhandled Transaction class "%s"',
                    get_class($transaction)
                ));

        }

        // Persist processed transaction
        $this->em->persist($transaction);
        $this->em->flush();

        // dispatch TransactionCreated event
        $this->dispatcher->dispatch(
            PaymentEvents::TRANSACTION_PROCESSED,
            new TransactionProcessedEvent($transaction)
        );
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\BuyNow $button
     * @return string
     * @throws \Ilis\Bundle\PaymentBundle\Exception\Exception
     */
    public function initPaypalBuyNowTransaction(BuyNow $button)
    {
        if (!$this->methodIsAvailable(Method::CODE_PAYPAL_PAYMENTS_STANDARDS))
            throw new Exception('Paypal Payments Standards is not available');

        $filtered = $this->methods->filter(function($method){
            return $method->getCode() === Method::CODE_PAYPAL_PAYMENTS_STANDARDS;
        });

        if ($filtered->count() !== 1)
            throw new Exception(sprintf(
                'Unable to load Method "%s"',
                Method::CODE_PAYPAL_PAYMENTS_STANDARDS
            ));

        $method = $filtered->current();

        $transaction  = new PaypalTransaction;
        $transaction->setType(PaypalTransaction::TYPE_BUYNOW);
        $transaction->setAmount($button->getAmount());
        $transaction->setCmd($button->getCmd());
        $transaction->setBn($button->getBn());
        $transaction->setCurrencyCode($button->getCurrencyCode());
        $transaction->setQuantity($button->getQuantity());
        $transaction->setItemName($button->getItemName());
        $transaction->setItemNumber($button->getItemNumber());
        $transaction->setMethod($method);

        $this->em->persist($transaction);
        $this->em->flush();

        /** @var $processor \Ilis\Bundle\PaymentBundle\Processor\Paypal\PaymentStandard */
        $processor = $this->getProcessor($method);

        $url = $processor->buildUrl($transaction);
        return $url;

    }

    /**
     * @param CreditCardTransaction $transaction
     */
    protected function processCreditCardTransaction(CreditCardTransaction $transaction)
    {
        $type = $transaction->getType();
        $method = $transaction->getMethod();

        switch($type)
        {
            case CreditCardTransaction::TYPE_AUTH:

                if (null !== $transaction->getId())
                    throw new Exception('Invalid transaction');

                // Persist pending transaction
                $this->em->persist($transaction);
                $this->em->flush();

                // Transaction processing
                $processor = $this->getProcessor($method);
                $processor->capture($transaction);
                break;

            case CreditCardTransaction::TYPE_PREAUTH:
            case CreditCardTransaction::TYPE_FULFILL:
            case CreditCardTransaction::TYPE_VOID:
            case CreditCardTransaction::TYPE_CANCEL:
            default:
                throw new Exception(sprintf(
                    'Unhandled transaction type "%s"',
                    $type
                ));
        }
    }

    /**
     * @param PaypalTransaction $transaction
     */
    protected function processPaypalTransaction(PaypalTransaction $transaction)
    {
        switch ($transaction->getPaymentStatus())
        {
            case PaypalTransaction::PAYMENT_STATUS_COMPLETED:

                $transaction->setStatus(
                    Transaction::STATUS_SUCCESS
                );
                break;
            case PaypalTransaction::PAYMENT_STATUS_DENIED:
            case PaypalTransaction::PAYMENT_STATUS_EXPIRED:
            case PaypalTransaction::PAYMENT_STATUS_FAILED:
                $transaction->setStatus(
                    Transaction::STATUS_ERROR
                );
                break;
            default:
                throw new Exception(sprintf(
                    'Unhandled payment status "%s"',
                    $transaction->getPaymentStatus()
                ));
        }

    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @return \Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract
     * @throws \Ilis\Bundle\PaymentBundle\Exception\Exception
     */
    protected function getProcessor(Method $method)
    {
        $processor = ProcessorFactory::makeProcessor($method, $this->router);

        if (!is_subclass_of($processor, 'Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract'))
            throw new Exception(sprintf(
                'Unable to retrieve processor for method "%s"',
                $method->getName()
            ));

        return $processor;
    }

}