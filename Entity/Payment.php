<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;

class Payment {
	
	private $data;
	
	/**
	* Set/Get attribute wrapper
	*
	* @param   string $method
	* @param   array $args
	* @return  mixed
	*/
	public function __call($method, $args)
	{
		switch (substr($method, 0, 3)) {
			case 'get' :
				$key = $this->underscore(substr($method,3));
				if (array_key_exists($key, $this->data))
					return $this->data[$key];
				break;
			case 'set' :
				$key = $this->underscore(substr($method,3));
				$this->data[$key] = $args[0];
				break;
			case 'has' :
				$key = $this->underscore(substr($method,3));
				if (array_key_exists($key, $this->data))
					return isset($this->data[$key]);
				break;
		}
	}
	
	/**
	 * @return array
	 */
	public function toArray(){
	
		return $this->data;
	}
	
	/**
	 * Converts field names for setters and geters
	 * @param string $name
	 * @return string
	 */
	private function underscore($name)
	{
		$result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
		return $result;
	}
	
}