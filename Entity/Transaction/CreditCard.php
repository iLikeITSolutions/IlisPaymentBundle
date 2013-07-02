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
 * @ORM\Table(name="ilis_payment_transactions_creditcard")
 */
class CreditCard extends Transaction
{

    const TYPE_AUTH     = 'cc_auth';
    const TYPE_PREAUTH  = 'cc_preauth';
    const TYPE_FULLFILL = 'cc_fullfill';
    const TYPE_VOID     = 'cc_void';
    const TYPE_CANCEL   = 'cc_cancel';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $cardHolderName;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_code", type="string", nullable=true)
     */
    private $authCode;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $rawData;

    /**
     * @var string
     */
     public $creditCard;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[0-9]{3,4}$/", message="payment.validations.cvv.invalidcvv")
     */
    public $cvv;

    /**
     * Format needs to be mm
     *
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[0-9]{2}$/", message="payment.validations.expmonth.invalidexpdatemonth")
     */
    public $expiryDateMonth;

    /**
     * Format needs to be yy
     *
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[0-9]{2}$/", message="payment.validations.expmonth.invalidexpdateyear")
     */
    public $expiryDateYear;

    /**
     * This is based in Luhn Algorithm
     * @see http://en.wikipedia.org/wiki/Luhn_algorithm
     *
     * @Assert\True(message="payment.validations.cardnumber.checksum")
     * @return bool
     */
    public function isChecksumCorrect()
    {
        $cardNumber = $this->creditCard;

        $aux = '';
        foreach (str_split(strrev($cardNumber)) as $pos => $digit) {
            // Multiply * 2 all even digits
            $aux .= ($pos % 2 != 0) ? $digit * 2 : $digit;
        }
        // Sum all digits in string
        $checksum = array_sum(str_split($aux));

        // Card is OK if the sum is an even multiple of 10 and not 0
        return ($checksum != 0 && $checksum % 10 == 0);
    }

    /**
     * @Assert\True(message="payment.validations.expmonth.cardexpired")
     * @return bool
     */
    public function isExpirationDateValid()
    {
        $expiryDate = (string)$this->expiryDateYear . $this->expiryDateMonth;

        if (substr($expiryDate, 2, 2) < 1 || substr($expiryDate, 2, 2) > 12) return false;
        if ($expiryDate < date('ym')) return false;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set authCode
     *
     * @param string $authCode
     * @return CreditCard
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;

        return $this;
    }

    /**
     * Get authCode
     *
     * @return string
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Set rawData
     *
     * @param string $rawData
     * @return CreditCard
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;

        return $this;
    }

    /**
     * Get rawData
     *
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }
}