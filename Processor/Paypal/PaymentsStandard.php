<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor\Paypal;

use Ilis\Bundle\PaymentBundle\Processor\ProcessorAbstract;
use Ilis\Bundle\PaymentBundle\Entity\Transaction\Paypal as PaypalTransaction;

class PaymentsStandard extends ProcessorAbstract
{
    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\Paypal $transaction
     * @throws Exception
     * @return string
     */
    public function buildUrl(PaypalTransaction $transaction)
    {
        switch ($transaction->getType())
        {
            case PaypalTransaction::TYPE_BUYNOW:
                return $this->buildBuyNowUrl($transaction);
                break;
            default:
                throw new Exception(sprintf(
                    'Unhandled transaction type "%s"',
                    $transaction->getType()
                ));
        }
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Transaction\Paypal $transaction
     * @return string
     */
    protected function buildBuyNowUrl(PaypalTransaction $transaction)
    {
        $parameters = array ();

        // Standard Variables
        $parameters['cmd']              = $transaction->getCmd();
        $parameters['bn']               = $transaction->getBn();
        $parameters['currency_code']    = $transaction->getCurrencyCode();
        $parameters['business']         = $this->method->getBusiness();
        $parameters['amount']           = $transaction->getAmount();

        // Optional Variables
        if ($transaction->getItemName())
            $parameters['item_name'] = $transaction->getItemName();
        if ($transaction->getItemNumber())
            $parameters['item_number'] = $transaction->getItemNumber();
        if ($transaction->getQuantity())
            $parameters['quantity'] = $transaction->getQuantity();

        // Notifiy Url
        $parameters['notify_url'] = $this->router->generate(
            'ilis_payment_paypal_callback',
            array(),
            true
        );

        // Transaction Identifier
        $parameters['custom'] = $transaction->getIdentifier();

        // Use Sandbox?
        if ($this->method->getSandbox() === true)
            $baseUrl = 'http://www.sandbox.paypal.com/cgi-bin/webscr';
        else
            $baseUrl = 'http://www.paypal.com/cgi-bin/webscr';

        // Return Method
        $parameters['rm'] = $this->method->getRm();

        // Return
        $parameters['return'] = $this->router->generate(
          $this->method->getReturn(),
          array(),
          true
        );

        // Cancel Return
        $parameters['cancel_return'] = $this->router->generate(
            $this->method->getCancelReturn(),
            array(),
            true
        );


        $query = http_build_query($parameters);

        return "$baseUrl?$query";

    }
}