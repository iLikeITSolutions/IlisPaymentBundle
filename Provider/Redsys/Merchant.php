<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys;

class Merchant {
	
	const TERMINAL_DEFAULT = '001';
	
	/**
	 * 
	 * Fuc Code
	 * 
	 * @var string 
	 */
	private $code;
	
	/**
	 * 
	 * Terminal Number
	 * 
	 * @var string
	 */
	private $terminal;
	
	/**
	 * 
	 * @var string
	 */
	private $currency;
	
	
	/**
	 * 
	 * Secret Key used to sign requests
	 * 
	 * @var string
	 */
	private $secret_key;
	
	
	/**
	 * 
	 * Class Constructor
	 * 
	 * @param string $code
	 * @param string $secret
	 * @param string $terminal
	 * @param string $currency
	 */
	public function __construct($code){
		
		$this->code = $code;
		
	}
	
	/**
	 * Get Merchant Code
	 * 
	 * @return string
	 */
	public function getCode(){
		
		return $this->code;
		
	}
	
}