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
 * @ORM\Entity(repositoryClass="Ilis\Bundle\PaymentBundle\Entity\MethodRepository")
 * @ORM\Table(name="ilis_payment_methods")
 */
class Method
{
    const TYPE_CREDITCARD = 'cc';

    const CODE_REDSYS_WEBSERVICE = 'redsys-webservice';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="Ilis\Bundle\PaymentBundle\Entity\MethodAttribute", mappedBy="method", cascade={"persist"}, orphanRemoval=true);
     */
    private $attributes;

    /**
     * @var OneToMany(targetEntity="Ilis\Bundle\PaymentBundle\Entity\MethodConfig", mappedBy="method")
     */
    private $configs;


    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->configs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add attributes
     *
     * @param Ilis\Bundle\PaymentBundle\Entity\MethodAttribute $attributes
     */
    public function addMethodAttribute(\Ilis\Bundle\PaymentBundle\Entity\MethodAttribute $attribute)
    {
        $attribute->setMethod($this);
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
}