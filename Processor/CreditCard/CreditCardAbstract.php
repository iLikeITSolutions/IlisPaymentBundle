<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor\CreditCard;

use Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract;
use Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard as CreditCardTransaction;

abstract class CreditCardAbstract extends ProcessorAbstract
{
    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    abstract public function capture(CreditCardTransaction $transaction);

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    abstract public function authorize(CreditCardTransaction $transaction);

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    abstract public function void (CreditCardTransaction $transaction);

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    abstract public function fulfill(CreditCardTransaction $transaction);

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    abstract public function cancel(CreditCardTransaction $transaction);
}