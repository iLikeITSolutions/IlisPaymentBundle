<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ilis\Bundle\PaymentBundle\Entity\Transaction;

abstract class TransactionEventAbstract extends Event
{
    /**
     * @var Transaction
     */
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction
    }

    /**
     * @return \Ilis\Bundle\PaymentBundle\Entity\Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}