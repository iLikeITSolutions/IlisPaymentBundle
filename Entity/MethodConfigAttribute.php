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
 * @ORM\Table(name="ilis_payment_method_config_attributes")
 */
class MethodConfigAttribute {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MethodAttribute")
	 */
	private $attribute;
	
	/**
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $value;
	
	/**
	 *
	 * @ORM\ManyToOne(targetEntity="Ilis\Bundle\PaymentBundle\Entity\MethodConfig", inversedBy="attributes")
	 * @var MethodConfig
	 *
	 */
	private $config;
	


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
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set config
     *
     * @param Ilis\Bundle\PaymentBundle\Entity\MethodConfig $config
     */
    public function setConfig(\Ilis\Bundle\PaymentBundle\Entity\MethodConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Get config
     *
     * @return Ilis\Bundle\PaymentBundle\Entity\MethodConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set attribute
     *
     * @param Ilis\Bundle\PaymentBundle\Entity\MethodAttribute $attribute
     */
    public function setAttribute(\Ilis\Bundle\PaymentBundle\Entity\MethodAttribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * Get attribute
     *
     * @return Ilis\Bundle\PaymentBundle\Entity\MethodAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
    
    /**
     * @return string
     */
    public function getName(){
    	
    	return $this->getAttribute()->getName();
    	
    }
}
