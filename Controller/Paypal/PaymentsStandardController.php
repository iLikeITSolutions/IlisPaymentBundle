<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Controller\Paypal;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PaymentsStandardController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buynowAction()
    {
        return new Response("Buynow!");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callbackHandlerAction()
    {
        return new Response ('Callback Handler');
    }

}