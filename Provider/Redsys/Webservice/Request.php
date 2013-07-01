<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice;

class Request {
	
	const XML_REQ_ROOT 							= 'DATOSENTRADA';
	
	// Common
	const XML_REQ_AMOUNT						= 'DS_MERCHANT_AMOUNT';
	const XML_REQ_ORDER							= 'DS_MERCHANT_ORDER';
	const XML_REQ_MERCHANTCODE					= 'DS_MERCHANT_MERCHANTCODE';
	const XML_REQ_TERMINAL						= 'DS_MERCHANT_TERMINAL';
	const XML_REQ_CURRENCY						= 'DS_MERCHANT_CURRENCY';
	const XML_REQ_TRANS_TYPE					= 'DS_MERCHANT_TRANSACTIONTYPE';
	const XML_REQ_SIGNATURE						= 'DS_MERCHANT_MERCHANTSIGNATURE';
	
	// Authorization
	const XML_REQ_PAN							= 'DS_MERCHANT_PAN';
	const XML_REQ_EXPIRYDATE					= 'DS_MERCHANT_EXPIRYDATE';
	const XML_REQ_CVV2							= 'DS_MERCHANT_CVV2';
	
	
	// Recurring
	const XML_REQ_SUMTOTAL						= 'DS_MERCHANT_SUMTOTAL';
	const XML_REQ_FRECUENCY						= 'DS_MERCHANT_DATEFRECUENCY';
	const XML_REQ_CHARGE_EXPIRYDATE				= 'DS_MERCHANT_CHARGE_EXPIRYDATE';
	const XML_REQ_TRANS_DATE					= 'DS_MERCHANT_TRANSACTIONDATE';
	
	// Capture / Void
	const  XML_REQ_AUTH_CODE					= 'DS_MERCHANT_AUTHORISATIONCODE';

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
	private $order;

    /**
     * @var string
     */
	private $merchant_code;

    /**
     * @var string
     */
	private $terminal;

    /**
     * @var string
     */
	private $currency;

    /**
     * @var string
     */
	private $transaction_type;

    /**
     * @var string
     */
	private $signature;

    /**
     * @var string
     */
	private $pan;

    /**
     * @var string
     */
	private $expiry_date;

    /**
     * @var string
     */
	private $cvv2;

    /**
     * @var string
     */
	private $sumtotal;

    /**
     * @var string
     */
	private $frequency;

    /**
     * @var string
     */
	private $charge_expiry_date;

    /**
     * @var string
     */
	private $trans_date;

    /**
     * @var string
     */
	private $auth_code;
	
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
				if (property_exists($this, $key))
					return $this->{$key};
			case 'set' :
				$key = $this->underscore(substr($method,3));
				if (property_exists($this, $key))
					$this->$key = $args[0];
			case 'has' :
				$key = $this->underscore(substr($method,3));
				if (property_exists($this, $key))
					return isset($this->{$key});
		}
	}
	
	/** 
	 * Return the XML version of the Request to be sent through the client
	 * 
	 * @return string
	 */
	public function toXml(){
		
		$xml = new \SimpleXMLElement("<".self::XML_REQ_ROOT . "/>", LIBXML_NOXMLDECL);
		
		if ($this->hasAmount())
			$xml->{self::XML_REQ_AMOUNT} = $this->getAmount();
		
		if ($this->hasOrder())
			$xml->{self::XML_REQ_ORDER} = $this->getOrder();
		
		if ($this->hasMerchantCode())
			$xml->{self::XML_REQ_MERCHANTCODE} = $this->getMerchantCode();
		
		if ($this->hasTerminal())
			$xml->{self::XML_REQ_TERMINAL} = $this->getTerminal();
		
		if ($this->hasCurrency())
			$xml->{self::XML_REQ_CURRENCY} = $this->getCurrency();
		
		if ($this->hasPan())
			$xml->{self::XML_REQ_PAN} = $this->getPan();
		
		if ($this->hasExpiryDate())
			$xml->{self::XML_REQ_EXPIRYDATE} = $this->getExpiryDate();
		
		if ($this->hasCvv2())
			$xml->{self::XML_REQ_CVV2} = $this->getCvv2();
		
		if ($this->hasTransactionType())
			$xml->{self::XML_REQ_TRANS_TYPE} = $this->getTransactionType();
		
		if ($this->hasSumtotal())
			$xml->{self::XML_REQ_SUMTOTAL} = $this->getSumtotal();
		
		if ($this->hasFrequency())
			$xml->{self::XML_REQ_FRECUENCY} = $this->getFrequency();
		
		if ($this->hasChargeExpiryDate())
			$xml->{self::XML_REQ_CHARGE_EXPIRYDATE} = $this->getChargeExpiryDate();
		
		if ($this->hasTransDate())
			$xml->{self::XML_REQ_TRANS_DATE} = $this->getTransDate();
		
		if ($this->hasAuthCode())
			$xml->{self::XML_REQ_AUTH_CODE} = $this->getAuthCode();
		
		if ($this->hasSignature())
			$xml->{self::XML_REQ_SIGNATURE} = $this->getSignature();
		
		$return =  $xml->asXML();

		// LIBXML_NOXMLDECL, doesn't seem to work properly
		$return = preg_replace('/<\\?.*?\\?>/','', $return);
		return $return;
	}
	
	/**
	 * Converts field names for setters and getters
	 *
	 * @param string $name
	 * @return string
	 */
	private function underscore($name)
	{
		$result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
		return $result;
	}
	
	
	
	

	
} 
