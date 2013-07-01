<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice;

class Transaction {
	
	// Transactions' types
	const TYPE_AUTH								= 'A';
	const TYPE_PREAUTH							= '1';
	const TYPE_FULLFILL							= '2';
	const TYPE_VOID								= '3';
	const TYPE_CANCEL							= '9';
	
	const TYPE_AUTH_DEFERRED					= 'O';
	const TYPE_FULLFILL_DEFERRED				= 'P';
	const TYPE_CANCEL_DEFERRED					= 'Q';
	
	const TYPE_RECURRING_AUTH_INITIAL			= '5';
	const TYPE_RECURRING_AUTH					= '6';
	
	const TYPE_RECURRING_AUTH_DEFERRED_INTITIAL	= 'R';
	const TYPE_RECURRING_AUTH_DEFERRED			= 'S';
	
}
