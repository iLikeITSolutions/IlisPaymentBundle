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

        $parameters['business'] = $this->method->getBusiness();
        $parameters['amount'] = $transaction->getAmount();

        if ($this->method->getSandbox() === true)
            $baseUrl = 'http://www.sandbox.paypal.com/cgi-bin/webscr';
        else
            $baseUrl = 'http://www.paypal.com/cgi-bin/webscr';

        $query = http_build_query($parameters);

        return "$baseUrl?$query";

    }
}