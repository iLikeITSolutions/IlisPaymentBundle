<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor\CreditCard;

use Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract;
use Ilis\Bundle\PaymentBundle\Entity\Payment;

abstract class CreditCardAbstract extends ProcessorAbstract
{
    /**
     * @param Payment $payment
     */
    abstract public function capture(Payment $payment, $amount);

    /**
     * @param Payment $payment
     */
    abstract public function authorize(Payment $payment, $amount);

    /**
     * @param Payment $payment
     */
    abstract public function void (Payment $payment);

    /**
     * @param Payment $payment
     */
    abstract public function fullfill(Payment $payment);

    /**
     * @param Payment $payment
     */
    abstract public function cancel(Payment $payment);
}