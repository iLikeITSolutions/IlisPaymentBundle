<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor\CreditCard;

use Ilis\Bundle\PaymentBundle\Entity\MethodConfig;
use Ilis\Bundle\PaymentBundle\Entity\Payment;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Client;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Merchant;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Transaction;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Request;
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
     * @param MethodConfig $config
     */
    public function __construct(MethodConfig $config){

        parent::__construct($config);

        $this->client = new Client($config->getEnvironment());

        $this->merchant = new Merchant(
            $config->getMerchant(),
            $config->getSecretKey(),
            $config->getTerminal()
        );

    }

    /**
     * @param Payment $payment
     */
    public function capture(Payment $payment, $amount){

        //FIXME: Use Entity\Transaction instead
        $payment->setCompleted(false);

        if (!is_numeric($amount) || $amount <= 0)
            throw new Exception("Invalid amount");

        $request = new Request();

        $request->setOrder($payment->getOrder());

        $amount = (float) number_format ($amount, 2, '.','')*100;

        $request->setAmount($amount);

        $request->setMerchantCode($this->merchant->getCode());
        $request->setTerminal($this->merchant->getTerminal());
        $request->setCurrency($this->merchant->getCurrency());
        $request->setPan($payment->getPan());
        $request->setCvv2($payment->getCvv2());
        $request->setExpiryDate($payment->getExpiryDate());
        $request->setTransactionType(Transaction::TYPE_AUTH);

        $this->merchant->signRequest($request);

        $response = $this->client->makeRequest($request);
        $operation = $response->getOperation();
        $authCode = $operation ? trim((string) $operation->Ds_AuthorisationCode) : null;

        // FIXME: Use Entity\Transaction instead
        if ($response->isValid() &&  !empty($authCode)){
            $payment->setCompleted(true);
            $payment->setAuthCode((string) $response->getOperation()->Ds_AuthorisationCode);
        } else {
            $payment->setError($response->getCode());
        }

        $payment->setRawData($response->asXml());

    }

    /**
     * @param Payment $payment
     */
    public function authorize(Payment $payment, $amount){
        // TODO:
    }

    /**
     * @param Payment $payment
     */
    public function void (Payment $payment){
        // TODO:
    }

    /**
     * @param Payment $payment
     */
    public function fullfill(Payment $payment){
        // TODO:
    }

    /**
     * @param Payment $payment
     */
    public function cancel(Payment $payment){

    }

}