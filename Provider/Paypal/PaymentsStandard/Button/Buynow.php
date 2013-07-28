<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button;

class Buynow extends ButtonAbstract
{

    /**
     * @var string
     */
    private $itemName;

    /**
     * @var string
     */
    private $itemNumber;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var string
     */
    private $currencyCode;


    /**
     * @var float
     */
    private $amount;



    /**
     * Button Constructor
     */
    public function __construct($country = 'ES')
    {
        $this->cmd = ButtonAbstract::CMD_XCLICK;
        $this->bn = $this->buildBn('BuyNow', $country);
    }

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
     * @return Buynow
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
     * @return Buynow
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
     * @return Buynow
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

    /**
     * @param $amount
     * @return Buynow
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }



}