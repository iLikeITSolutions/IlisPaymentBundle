<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice;

class Response extends \SimpleXMLElement {

	const NODE_CODE			= 'CODIGO';
	const NODE_RECEIVED 	= 'RECIBIDO';
	const NODE_OPERATION	= 'OPERACION';
	
	const XML_RES_ROOT 		= 'RETORNOXML';

	/** 
	 * Check if the response is valid
	 * 
	 * @return boolean
	 * 
	 */
	public function isValid(){

		if ($this->getCode() === '0')
			return true;
		else
			return false;
	}

	/**
	 * Get the error code if present
	 * 
	 * @return mixed
	 */
	public function getCode(){

		if (!isset($this->{self::NODE_CODE}))
			return null;

		return (string) $this->{self::NODE_CODE};

	}
	
	/** 
	 * Get Operation detail in case of successful transaction
	 * 
	 * @return mixed
	 */
	public function getOperation(){
		
		if (!isset($this->{self::NODE_OPERATION}))
			return null;
		
		return $this->{self::NODE_OPERATION};
	}



}