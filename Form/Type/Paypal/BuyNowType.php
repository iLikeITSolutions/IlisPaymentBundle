<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Form\Type\Paypal;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Ilis\Bundle\PaymentBundle\Exception\Exception;
use Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\BuyNow;
use Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\ButtonAbstract;

class BuyNowType extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($factory) {

            $button = $event->getData();

            if (!$button instanceof BuyNow)
                throw new Exception('Invalid data object');

            $form = $event->getForm();

            // Cmd
            $form->add($factory->createNamed(
                'cmd',
                'hidden',
                $button->getCmd()
            ));

            // Bn
            $form->add($factory->createNamed(
                'bn',
                'hidden',
                $button->getBn()
            ));

            // ItemName
            $form->add($factory->createNamed(
                'item_name',
                'hidden',
                $button->getItemName()
            ));

            // Quantity
            $form->add($factory->createNamed(
                'quantity',
                'hidden',
                $button->getQuantity() !== null ? $button->getQuantity() : 1
            ));

            // CurrencyCode
            $form->add($factory->createNamed(
                'currency_code',
                'hidden',
                $button->getCurrencyCode() !== null ? $button->getCurrencyCode() : ButtonAbstract::CURRENCY_DEFAULT
            ));

            // Amount
            $form->add($factory->createNamed(
                'amount',
                'hidden',
                number_format((float) $button->getAmount(), 2, '.')
            ));

        });
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ilis_payment_paypal_buynow';
    }
}