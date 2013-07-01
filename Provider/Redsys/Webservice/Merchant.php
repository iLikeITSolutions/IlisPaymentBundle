<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice;

use Ilis\Bundle\PaymentBundle\Provider\Redsys\Merchant as BaseMerchant;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Currency;

class Merchant extends BaseMerchant {
	
	const TERMINAL_DEFAULT = '001';
	
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
	public function __construct($code, $secret, $terminal = self::TERMINAL_DEFAULT, $currency = Currency::EUR){
	
		parent::__construct($code);
		$this->secret_key = $secret;
		$this->terminal = $terminal;
		$this->currency = $currency;
	
	}
	
	/**
	 *
	 * Get Terminal Number
	 *
	 * @return string
	 */
	public function getTerminal(){
	
		return $this->terminal;
	
	}
	
	/**
	 *
	 * Get Secret Key
	 *
	 * @return string
	 */
	public function getSecretKey(){
	
		return $this->secret_key;
	}
	
	/**
	 * Get Currency
	 *
	 * @return string
	 */
	public function getCurrency(){
	
		return $this->currency;
	
	}
	
	/**
	 * Sign a Webservice Request
	 * 
	 * @param Request $request
	 * @return void
	 * 
	 */
	public function signRequest(Request $request){
		
		$txt = '';
		
		if ($request->hasAmount())
			$txt .= $request->getAmount();
		
		if ($request->hasOrder())
			$txt .= $request->getOrder();
		
		if ($request->hasMerchantCode())
			$txt .= $request->getMerchantCode();
		
		if ($request->hasCurrency())
			$txt .= $request->getCurrency();
		
		switch ($request->getTransactionType()){
			
			case Transaction::TYPE_AUTH:
			case Transaction::TYPE_PREAUTH:
			case Transaction::TYPE_AUTH_DEFERRED:
				
				if ($request->hasPan())
					$txt .= $request->getPan();
				
				if ($request->hasCvv2())
					$txt .= $request->getCvv2();
				
				break;
			case Transaction::TYPE_RECURRING_AUTH_INITIAL:
			case Transaction::TYPE_RECURRING_AUTH_DEFERRED_INTITIAL:
				
				if ($request->hasPan())
					$txt .= $request->getPan();
				
				if ($request->hasSumtotal())
					$txt .= $request->getSumtotal();
				
				if ($request->hasCvv2())
					$txt .= $request->getCvv2();
				
				break;
				
			case Transaction::TYPE_FULLFILL:
			case Transaction::TYPE_VOID:
			case Transaction::TYPE_RECURRING_AUTH:
			case Transaction::TYPE_CANCEL:
			case Transaction::TYPE_FULLFILL_DEFERRED:
			case Transaction::TYPE_CANCEL_DEFERRED:
			case Transaction::TYPE_RECURRING_AUTH_DEFERRED:
				// Nothing to add here
				break;
					
			
		}
		
		// Append Transaction Type
		$txt .= $request->getTransactionType();
		// Append Secret Key
		$txt .= $this->getSecretKey();
		
		// Calculate hash
		$signature = sha1($txt);
		
		// Sign Request
		$request->setSignature($signature);
		
	}
	
}
