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
use Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\ButtonAbstract;

class BuyNow extends AbstractType
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

            if (!$button instanceof ButtonAbstract)
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