<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ilis_payment_transactions")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="method_type", type="string")
 * @ORM\DiscriminatorMap({
        "cc" = "Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard",
   })
 * @ORM\HasLifecycleCallbacks()
 */
class Transaction
{

    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_ERROR   = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $amount;

    /**
     * @var Method
     *
     * @ORM\ManyToOne(targetEntity="Method")
     */
    private $method;

    /**
     * @ORM\PrePersist
     */
    public function setDefaults()
    {
        if (null === $this->status)
            $this->status = self::STATUS_PENDING;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set method
     *
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @return Transaction
     */
    public function setMethod(\Ilis\Bundle\PaymentBundle\Entity\Method $method = null)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return \Ilis\Bundle\PaymentBundle\Entity\Method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Transaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }


}