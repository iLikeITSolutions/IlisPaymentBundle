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
     * @ORM\Column(type="string", length=32)
     */
    private $cmd;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127)
     */
    private $bn;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $itemName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $itemNumber;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     *
     */
    private  $currencyCode;

    /**
     * @param $cmd
     * @return Paypal
     */
    public function setCmd($cmd)
    {
        $this->cmd = $cmd;
        return $this;
    }

    /**
     * @return string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @param $bn
     * @return Paypal
     */
    public function setBn($bn)
    {
        $this->bn = $bn;
        return $this;
    }

    /**
     * @return string
     */
    public function getBn()
    {
        return $this->bn;
    }

    /**
     * @param $itemName
     * @return Paypal
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @param $itemNumber
     * @return Paypal
     */
    public function setItemNumber($itemNumber)
    {
        $this->itemNumber = $itemNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    /**
     * @param $quantity
     * @return Paypal
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param $currency
     * @return Paypal
     */
    public function setCurrencyCode($currency)
    {
        $this->currencyCode = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }


}