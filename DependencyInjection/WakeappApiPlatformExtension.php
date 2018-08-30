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

        $apiResultDtoClass = $config['api_result_dto_class'];
        $apiAreaGuesser = $config['api_area_guesser_service'];
        $errorCodeGuesser = $config['error_code_guesser_service'];
        $requestDebug = $config['response_debug'];

        $container->setParameter('wakeapp_api_platform.api_result_dto_class', $apiResultDtoClass);
        $container->setParameter('wakeapp_api_platform.api_area_guesser_service', $apiAreaGuesser);
        $container->setParameter('wakeapp_api_platform.error_code_guesser_service', $errorCodeGuesser);
        $container->setParameter('wakeapp_api_platform.response_debug', $requestDebug);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}
