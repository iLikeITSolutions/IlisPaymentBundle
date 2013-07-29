<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity\Transaction;

use Ilis\Bundle\PaymentBundle\Entity\Transaction;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ilis_payment_transactions_paypal")
 */
class Paypal extends Transaction
{
    const TYPE_BUYNOW = 'paypal_buynow';

}