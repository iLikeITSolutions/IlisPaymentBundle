<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor;

use Ilis\Bundle\PaymentBundle\Entity\MethodConfig;

abstract class ProcessorAbstract
{
    /**
     * @var MethodConfig
     */
    private $config;

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\MethodConfig $config
     */
    public function __construct (MethodConfig $config){

        $this->config = $config;

    }

}