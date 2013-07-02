<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Service;

use Doctrine\ORM\EntityManager;
use Ilis\Bundle\PaymentBundle\Entity\Transaction;
use Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard as CreditCardTransaction;
use Ilis\Bundle\PaymentBundle\Exception\Exception;

class Manager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param Transaction $transaction
     * @throws Exception
     */
    public function processTransaction(Transaction $transaction)
    {
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
    }

    /**
     * @param Transaction $transaction
     */
    protected function processCreditCardTransaction(CreditCardTransaction $transaction)
    {
        $type = $transaction->getType();

        switch($type)
        {
            case CreditCardTransaction::TYPE_AUTH:
                break;
            case CreditCardTransaction::TYPE_PREAUTH:
            case CreditCardTransaction::TYPE_FULLFILL:
            case CreditCardTransaction::TYPE_VOID:
            case CreditCardTransaction::TYPE_CANCEL:
            default:
                throw new Exception(sprintf(
                    'Unhandled transaction type "%"',
                    $type
                ));
        }
    }
}