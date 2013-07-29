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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ilis\Bundle\PaymentBundle\Form\Type\Paypal\BuyNowType;
use Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\Buynow;
use Ilis\Bundle\PaymentBundle\Exception\Exception;
use Ilis\Bundle\PaymentBundle\Service\Manager;

class PaymentsStandardController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buynowAction()
    {
        $request = $this->getRequest();
        $form = $this->createForm(new BuyNowType(), new Buynow());
        $form->bind($request);

        if (!$form->isValid())
            throw new Exception(sprintf(
                'Invalid Paypal Button (%s error/s)',
                count($form->getErrors())
            ));

        $button = $form->getData();

        /** @var $manager Manager */
        $manager = $this->get('ilis.payment.manager');

        $url = $manager->initPaypalBuyNowTransaction($button);
        return new RedirectResponse($url);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callbackHandlerAction()
    {
        $logger = $this->get('logger');
        $request = $this->getRequest();
        $logger->debug(var_export($request->request->all(), true));
        return new Response ('Callback Handler');
    }

}