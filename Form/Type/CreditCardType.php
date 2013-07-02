<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CreditCardType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $numberMonth = ($i < 10) ? '0' . (string)$i : (string)$i;
            $months[$numberMonth] = $numberMonth;
        }

        $year = array();
        for ($i = date('y'); $i <= date('y') + 10; $i++) {
            $year[(string)$i] = (string)$i;
        }

        $builder
            ->add('name')
            ->add('creditCard')
            ->add('cvv')
            ->add('expiryDateMonth', 'choice', array(
                'choices' => $months,
            ))
            ->add('expiryDateYear', 'choice', array(
                'choices' => $year,
            ))
        ;
    }

    public function getName()
    {
        return 'transaction';
    }
}