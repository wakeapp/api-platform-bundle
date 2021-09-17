<?php

declare(strict_types=1);

/*
 * This file is part of the ApiPlatformBundle package.
 *
 * (c) MarfaTech <https://marfa-tech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MarfaTech\Bundle\ApiPlatformBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MarfatechApiPlatformExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('marfatech_api_platform.api_result_dto_class', $config['api_result_dto_class']);
        $container->setParameter('marfatech_api_platform.api_area_guesser_service', $config['api_area_guesser_service']);
        $container->setParameter(
            'marfatech_api_platform.error_code_guesser_service',
            $config['error_code_guesser_service']
        );
        $container->setParameter('marfatech_api_platform.response_debug', $config['response_debug']);
        $container->setParameter('marfatech_api_platform.minimal_api_version', $config['minimal_api_version']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}
