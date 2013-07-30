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

    const PAYMENT_STATUS_COMPLETED  = 'Completed';
    const PAYMENT_STATUS_CREATED    = 'Created';
    const PAYMENT_STATUS_DENIED     = 'Denied';
    const PAYMENT_STATUS_EXPIRED    = 'Expired';
    const PAYMENT_STATUS_FAILED     = 'Failed';
    const PAYMENT_STATUS_PENDING    = 'Pending';
    const PAYMENT_STATUS_REFUNDED   = 'Refunded';
    const PAYMENT_STATUS_REVERSED   = 'Reversed';
    const PAYMENT_STATUS_PROCESSED  = 'Processed';
    const PAYMENT_STATUS_VOIDED     = 'Voided';

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $custom;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=32, nullable=true)
     *
     */
    private  $currencyCode;

    /**
     * @var string
     *
     * @ORM\Column(name="txn_type", nullable=true)
     */
    private $txnType;

    /**
     * @var string
     *
     * @ORM\Column(name="txn_id", nullable=true)
     */
    private $txnId;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_status", nullable=true)
     */
    private $paymentStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_data", type="text", nullable=true)
     */
    private $rawData;

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

    /**
     * @param $custom
     * @return Paypal
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param $txnType
     * @return Paypal
     */
    public function setTxnType($txnType)
    {
        $this->txnType = $txnType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTxnType()
    {
        return $this->txnType;
    }

    /**
     * @param $txnId
     * @return Paypal
     */
    public function setTxnid($txnId)
    {
        $this->txnId = $txnId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTxnId()
    {
        return $this->txnId;
    }

    /**
     * @param $paymentStatus
     * @return Paypal
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param $rawData
     * @return Paypal
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }


}