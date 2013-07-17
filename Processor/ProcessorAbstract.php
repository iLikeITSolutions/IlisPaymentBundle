<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor;

use Ilis\Bundle\PaymentBundle\Entity\Method;

abstract class ProcessorAbstract
{
    /**
     * @var Method
     */
    private $method;

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     */
    public function __construct (Method $method){

        $this->method = $method;

    }

}