<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ilis\Bundle\PaymentBundle\Entity\Method;
use Ilis\Bundle\PaymentBundle\Entity\MethodAttribute;

class LoadMethodData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $attributes = array (
            'merchant',
            'terminal',
            'secretKey',
            'environment'
        );

        $method = new Method();
        $method->setName('Redsys (Webservice Integration)');
        $method->setCode('redsys-webservice');
        $method->setType(Method::TYPE_CREDITCARD);

        foreach ($attributes as $attributeName)
        {
            $attribute = new MethodAttribute();
            $attribute->setName($attributeName);
            $method->addMethodAttribute($attribute);
        }

        $manager->persist($method);
        $manager->flush();
    }
}