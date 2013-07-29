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

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $cmd;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127)
     */
    private $itemName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127)
     */
    private $itemNumber;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     *
     */
    private  $currencyCode;





}