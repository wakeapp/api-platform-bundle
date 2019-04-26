<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WakeappApiPlatformExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('wakeapp_api_platform.api_result_dto_class', $config['api_result_dto_class']);
        $container->setParameter('wakeapp_api_platform.api_area_guesser_service', $config['api_area_guesser_service']);
        $container->setParameter(
            'wakeapp_api_platform.error_code_guesser_service',
            $config['error_code_guesser_service']
        );
        $container->setParameter('wakeapp_api_platform.response_debug', $config['response_debug']);
        $container->setParameter('wakeapp_api_platform.minimal_api_version', $config['minimal_api_version']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}
