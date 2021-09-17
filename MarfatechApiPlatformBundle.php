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

namespace MarfaTech\Bundle\ApiPlatformBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use MarfaTech\Bundle\ApiPlatformBundle\DependencyInjection\Compiler\ApiErrorCodeGuesserCompiler;
use MarfaTech\Bundle\ApiPlatformBundle\DependencyInjection\Compiler\ApiKernelCompiler;
use MarfaTech\Bundle\ApiPlatformBundle\DependencyInjection\Compiler\ApiResponseListenerCompiler;

class MarfatechApiPlatformBundle extends Bundle
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
