<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor;

use Ilis\Bundle\PaymentBundle\Entity\Method;
use Ilis\Bundle\PaymentBundle\Exception\Exception;

class ProcessorFactory
{

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     * @return ProcessorAbstract
     * @throws \Ilis\Bundle\PaymentBundle\Exception\Exception
     */
    public static function makeProcessor(Method $method)
    {
        $code = $method->getCode();

        switch ($code)
        {
            case Method::CODE_REDSYS_WEBSERVICE:
                $processor = new CreditCard\Redsys($method);
                break;
            case Method::CODE_PAYPAL_PAYMENTS_STANDARDS:
                $processor = new Paypal\PaymentsStandard($method);
                break;
            default:
                throw new Exception(sprintf(
                    'Unhandled method code "%s". Cannot instantiate Processor',
                    $code
                ));

        }

        return $processor;
    }
}