<?php

declare(strict_types=1);

/*
 * This file is part of the ApiPlatformBundle package.
 *
 * (c) Wakeapp <https://wakeapp.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $responseDebug = $container->getParameter('wakeapp_api_platform.response_debug');
        $container->getParameterBag()->remove('wakeapp_api_platform.response_debug');

        $apiResultDtoClass = $container->getParameter('wakeapp_api_platform.api_result_dto_class');
        $container->getParameterBag()->remove('wakeapp_api_platform.api_result_dto_class');

        $listenerDefinition = $container->getDefinition(ApiResponseListener::class);
        $listenerDefinition
            ->addArgument($apiResultDtoClass)
            ->addArgument($responseDebug)
        ;
    }
}
