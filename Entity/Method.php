<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Method
{
    const TYPE_CREDITCARD = 'cc';
    const CODE_REDSYS_WEBSERVICE = 'redsys_webservice';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $attributes;

    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param $key
     * @param $value
     */
    public function addAttribute($key, $value)
    {
        $this->attributes->set($key, $value);
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
     * @param string $key
     * @return string
     */
    public function getAttribute($key){

        return $this->attributes->get($key);
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
            $key = $this->underscore(substr($method,3));
            return $this->attributes->get($key);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }

    /**
     * Converts field names for setters and getters
     * @param string $name
     * @return string
     */
    private function underscore($name)
    {
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        return $result;
    }
}