<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection\Compiler\ApiErrorCodeGuesserCompiler;
use Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection\Compiler\ApiKernelCompiler;
use Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection\Compiler\ApiResponseListenerCompiler;

class WakeappApiPlatformBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new ApiErrorCodeGuesserCompiler())
            ->addCompilerPass(new ApiKernelCompiler())
            ->addCompilerPass(new ApiResponseListenerCompiler())
        ;
    }
}
