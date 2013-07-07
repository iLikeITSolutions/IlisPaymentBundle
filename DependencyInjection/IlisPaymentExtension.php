<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class IlisPaymentExtension extends Extension
{
    /**
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container){

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($container->getParameter('kernel.environment') !== "prod")
        {
            $container->setParameter(
                'ilis.payment.transaction_identifier_suffix',
                null
            );

            return;
        }

        if (array_key_exists('transaction_identifier_suffix', $config))
        {
            $container->setParameter(
                'ilis.payment.transaction_identifier_suffix',
                $config['transaction_identifier_suffix']
            );
        }

    }
}