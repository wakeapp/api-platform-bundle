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

namespace MarfaTech\Bundle\ApiPlatformBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use MarfaTech\Bundle\ApiPlatformBundle\EventListener\ApiResponseListener;

class ApiResponseListenerCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $responseDebug = $container->getParameter('marfatech_api_platform.response_debug');
        $container->getParameterBag()->remove('marfatech_api_platform.response_debug');

        $apiResultDtoClass = $container->getParameter('marfatech_api_platform.api_result_dto_class');
        $container->getParameterBag()->remove('marfatech_api_platform.api_result_dto_class');

        $listenerDefinition = $container->getDefinition(ApiResponseListener::class);
        $listenerDefinition
            ->addArgument($apiResultDtoClass)
            ->addArgument($responseDebug)
        ;
    }
}
