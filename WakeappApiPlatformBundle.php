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
