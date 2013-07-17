<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor\CreditCard;

use Ilis\Bundle\PaymentBundle\Entity\Method;
use Ilis\Bundle\PaymentBundle\Entity\Transaction;
use Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard as CreditCardTransaction;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Client as WsClient;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Merchant as WsMerchant;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Transaction as WsTransaction;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Request as WsRequest;
use Ilis\Bundle\PaymentBundle\Exception\Exception;

class Redsys extends CreditCardAbstract
{

    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Method $method
     */
    public function __construct(Method $method){

        parent::__construct($method);

        $this->client = new WsClient($method->getEnvironment());

        $this->merchant = new WsMerchant(
            $method->getMerchant(),
            $method->getSecretKey(),
            $method->getTerminal()
        );

    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    public function capture(CreditCardTransaction $transaction){

        if (!is_numeric($transaction->getAmount()) || $transaction->getAmount() <= 0)
            throw new Exception("Invalid amount");

        // TODO: Check if the transaction has set the proper payment method

        $request = new WsRequest();

        $request->setOrder($transaction->getId());

        $amount = (float) number_format (
                $transaction->getAmount(),
                2,
                '.'
                ,''
        )*100;

        $request->setAmount($amount);
        $request->setOrder($transaction->getIdentifier());
        $request->setMerchantCode($this->merchant->getCode());
        $request->setTerminal($this->merchant->getTerminal());
        $request->setCurrency($this->merchant->getCurrency());
        $request->setPan($transaction->creditCard);
        $request->setCvv2($transaction->cvv);
        $request->setExpiryDate(sprintf("%s%s", $transaction->expiryDateYear, $transaction->expiryDateMonth));
        $request->setTransactionType(WsTransaction::TYPE_AUTH);

        $this->merchant->signRequest($request);

        $response = $this->client->makeRequest($request);
        $operation = $response->getOperation();
        $authCode = $operation ? trim((string) $operation->Ds_AuthorisationCode) : null;

        if ($response->isValid() &&  !empty($authCode)){
            $transaction->setStatus(Transaction::STATUS_SUCCESS);
            $transaction->setAuthCode((string) $response->getOperation()->Ds_AuthorisationCode);
        } else {
            $transaction->setStatus(Transaction::STATUS_ERROR);
            $transaction->setStatusCode((string) $response->getCode());
        }

        $transaction->setRawData($response->asXml());

    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    public function authorize(CreditCardTransaction $transaction){
        // TODO:
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    public function void (CreditCardTransaction $transaction){
        // TODO:
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    public function fulfill(CreditCardTransaction $transaction){
        // TODO:
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard $transaction
     */
    public function cancel(CreditCardTransaction $transaction){
        // TODO:
    }

}