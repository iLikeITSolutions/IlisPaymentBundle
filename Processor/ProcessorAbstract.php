<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Processor;

use Ilis\Bundle\PaymentBundle\Entity\Method;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

abstract class ProcessorAbstract
{
    /**
     * @var Method
     */
    protected $method;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @param \Ilis\Bundle\PaymentBundle\Entity\Method $method
     */
    public function __construct (Method $method, Router $router = null){

        $this->method = $method;

        if (null !== $router)
            $this->router = $router;

    }

}