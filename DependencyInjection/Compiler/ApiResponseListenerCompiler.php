<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wakeapp\Bundle\ApiPlatformBundle\EventListener\ApiResponseListener;

class ApiResponseListenerCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $requestDebug = $container->getParameter('wakeapp_api_platform.response_debug');
        $container->getParameterBag()->remove('wakeapp_api_platform.response_debug');

        $apiResultDtoClass = $container->getParameter('wakeapp_api_platform.api_result_dto_class');
        $container->getParameterBag()->remove('wakeapp_api_platform.api_result_dto_class');

        $listenerDefinition = $container->getDefinition(ApiResponseListener::class);
        $listenerDefinition
            ->addArgument($apiResultDtoClass)
            ->addArgument($requestDebug)
        ;

        if (!$container->has('translator')) {
            $listenerDefinition->replaceArgument(1, null);
        }
    }
}
