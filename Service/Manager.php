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
use Ilis\Bundle\PaymentBundle\Exception\Exception;
use Ilis\Bundle\PaymentBundle\Processor\ProcessorFactory;
use Ilis\Bundle\PaymentBundle\PaymentEvents;
use Ilis\Bundle\PaymentBundle\Event\TransactionProcessedEvent;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var ArrrayCollection
     */
    protected $methods;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->methods[] = new ArrayCollection();
    }

    /**
     * @param array $methodConfig
     */
    public function addMethod(Method $method)
    {
        $this->methods->add($method);
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @return bool
     */
    public function methodIsAvailable(Method $method)
    {
        foreach ($this->methods as $availableMethod)
            if ($availableMethod->getCode() === $method->getCode())
                return true;

        return false;
    }

    /**
     * @param bool $onlyAvailable
     * @return array
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
     * @param Transaction $transaction
     */
    protected function processCreditCardTransaction(CreditCardTransaction $transaction)
    {
        $type = $transaction->getType();
        $method = $transaction->getMethod();

        switch($type)
        {
            case CreditCardTransaction::TYPE_AUTH:

                // TODO: If transaction already has an id clone it and use the new instance
                if (null !== $transaction->getId())
                    throw new Exception(sprintf(
                       "Invalid transaction"
                    ));

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
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @return \Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract
     * @throws \Ilis\Bundle\PaymentBundle\Exception\Exception
     */
    protected function getProcessor(Method $method)
    {
        $processor = ProcessorFactory::makeProcessor($method);

        if (!is_subclass_of($processor, 'Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract'))
            throw new Exception(sprintf(
                'Unable to retrieve processor for method "%s"',
                $method->getName()
            ));

        return $processor;
    }

}