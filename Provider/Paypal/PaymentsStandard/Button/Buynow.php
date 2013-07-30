<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button;

use Symfony\Component\Validator\Constraints as Assert;

class BuyNow extends ButtonAbstract
{

    const RETURN_METHOD_GET         = '0';
    const RETURN_METHOD_GET_NO_VARS = '1';
    const RETURN_METHOD_POST        = '2';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = "127")
     */
    protected $itemName;

    /**
     * @var string
     *
     * @Assert\Length(max = "127")
     */
    protected $itemNumber;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     *
     */
    protected $quantity;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"EUR"})
     *
     */
    protected $currencyCode;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @Assert\Range(min = "0.1")
     */
    protected $amount;

    /**
     * Button Constructor
     */
    public function __construct($country = ButtonAbstract::COUNTRY_DEFAULT)
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