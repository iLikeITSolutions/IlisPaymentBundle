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
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @return bool
     */
    public function methodIsAvailable(Method $method)
    {
        $methods = $this->em->getRepository('IlisPaymentBundle:Method');
        $enabledConfiguration = $methods->countConfigurations($method, true);
        return $enabledConfiguration > 0;
    }

    /**
     * @param bool $onlyAvailable
     * @return array
     */
    public function getPaymentMethods($onlyEnabled = true)
    {
        $repository = $this->em->getRepository('IlisPaymentBundle:Method');
        $methods = $repository->getConfigured($onlyEnabled);
        return $methods;
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @throw Exception
     * @return \Ilis\Bundle\PaymentBundle\Entity\MethodConfig
     */
    public function getMethodConfig(Method $method)
    {
        $repository = $this->em->getRepository('IlisPaymentBundle:MethodConfig');
        $configs = $repository->getByMethod($method, true);

        if (count($configs) === 0 )
            throw new Exception(sprintf(
                'No configuration found for method "%s"',
                $method->getName()
            ));

        // Since is theoretically possible that multiples configuration are available, we always return the first one
        return array_shift($configs);
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
                    'Unhandled transaction type "%"',
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
        $config = $this->getMethodConfig($method);
        $processor = ProcessorFactory::makeProcessor($config);

        if (!is_subclass_of($processor, 'Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract'))
            throw new Exception(sprintf(
                'Unable to retrieve processor for method "%s"',
                $method->getName()
            ));

        return $processor;
    }

}