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
 * @ORM\Entity
 * @ORM\Table(name="ilis_payment_method_attributes")
 */
class MethodAttribute {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $name;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Method")
	 */
	private $method;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set method
     *
     * @param Ilis\Bundle\PaymentBundle\Entity\Method $method
     */
    public function setMethod(\Ilis\Bundle\PaymentBundle\Entity\Method $method)
    {
        $this->method = $method;
    }

    /**
     * Get method
     *
     * @return Ilis\Bundle\PaymentBundle\Entity\Method
     */
    public function getMethod()
    {
        return $this->method;
    }

}
