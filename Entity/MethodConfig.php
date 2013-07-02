<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Ilis\Bundle\PaymentBundle\Entity\MethodConfigRepository")
 * @ORM\Table(name="ilis_payment_method_configs")
 * @ORM\HasLifecycleCallbacks()
 */
class MethodConfig {
	
	const STATUS_DISABLED = 0;
    const STATUS_ENABLED  = 1;

    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    private $status;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Method", inversedBy="configs")
	 */
	private $method;

    /**
	 * @ORM\OneToMany(targetEntity="MethodConfigAttribute",mappedBy="config")
	 * @var ArrayCollection 
	 */
	private $attributes;


    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function setDefaults()
    {
        if (null ===$this->status)
                $this->status = self::STATUS_DISABLED;
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
     * Set method
     *
     * @param Method $method
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

    /**
     * Add attributes
     *
     * @param Ilis\Bundle\PaymentBundle\Entity\MethodConfigAttribute $attributes
     */
    public function addMethodConfigAttribute(\Ilis\Bundle\PaymentBundle\Entity\MethodConfigAttribute $attribute)
    {
        $this->attributes[] = $attribute;
    }

    /**
     * Get attributes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function getAttribute($name){
    	
    	foreach ($this->attributes as $attribute)
    		if ($attribute->getName() === $name)
    			return $attribute->getValue();
    }
    
    /**
    * Magic attribute getter
    *
    * @param string $method
    * @param array $args
    * @return string
    */
    public function __call($method, $args){
    	 
    	if (substr($method,0,3) === 'get'){
    		$attribute = lcfirst(substr($method,3));
    		return $this->getAttribute($attribute);
    	}
    }
}
