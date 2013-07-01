<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor;

use Ilis\Bundle\PaymentBundle\Entity\Method;
use Ilis\Bundle\PaymentBundle\Entity\MethodConfig;
use Ilis\Bundle\PaymentBundle\Exception\Exception;

class ProcessorFactory
{

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\MethodConfig $config
     * @return ProcessorAbstract
     * @throws \Ilis\Bundle\PaymentBundle\Exception\Exception
     */
    public static function makeProcessor(MethodConfig $config)
    {
        $method = $config->getMethod();
        $code = $method->getCode();

        switch ($code)
        {
            case Method::CODE_REDSYS_WEBSERVICE:
                $processor = new CreditCard\Redsys($config);
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