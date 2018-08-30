<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiAreaGuesserInterface;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiKernel;

class ApiKernelCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $apiAreaId = $container->getParameter('wakeapp_api_platform.api_area_guesser_service');
        $container->getParameterBag()->remove('wakeapp_api_platform.api_area_guesser_service');

        if (!$container->hasDefinition($apiAreaId)) {
            throw new ServiceNotFoundException($apiAreaId, ApiKernel::class);
        }

        $guesserDefinition = $container->getDefinition($apiAreaId);

        if (!is_subclass_of($guesserDefinition->getClass(), ApiAreaGuesserInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" should implements "%s" interface',
                $guesserDefinition->getClass(),
                ApiAreaGuesserInterface::class
            ));
        }

        $container
            ->getDefinition('http_kernel')
            ->setClass(ApiKernel::class)
            ->addMethodCall('setApiAreaGuesser', [$guesserDefinition])
        ;
    }
}
