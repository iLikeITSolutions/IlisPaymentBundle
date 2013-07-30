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
use Ilis\Bundle\PaymentBundle\Entity\Transaction\Paypal as PaypalTransaction;

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
        $data = $this->getRequest()->request->all();

        if (!array_key_exists('custom', $data))
            throw new Exception('The transaction identifier has not been sent');

        $identifier = $data['custom'];
        $transactions = $this->getDoctrine()->getManager()->getRepository('IlisPaymentBundle:Transaction');

        $transaction = $transactions->getOneByIdentifier($identifier);

        if (!$transaction instanceof PaypalTransaction)
            throw new Exception('The transaction cannot be found');

        /** @var $manager \Ilis\Bundle\PaymentBundle\Service\Manager */
        $manager = $this->get('ilis.payment.manager');

        $transaction->setTxnid($data['txn_id']);
        $transaction->setTxnType($data['txn_type']);
        $transaction->setPaymentStatus($data['payment_status']);
        $transaction->setRawData(serialize($data));

        // TODO: Fill Transaction Data
        $manager->processTransaction($transaction);

        /** @var $logger \Monolog\Logger */
        $logger = $this->get('logger');
        $logger->debug(var_export($request->request->all(), true));

        return new Response('');
    }

}