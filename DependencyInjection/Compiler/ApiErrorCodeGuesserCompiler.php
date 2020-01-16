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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Wakeapp\Bundle\ApiPlatformBundle\EventListener\ApiResponseListener;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesser;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesserInterface;
use function is_subclass_of;
use function sprintf;

class ApiErrorCodeGuesserCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $guesserId = $container->getParameter('wakeapp_api_platform.error_code_guesser_service');

        if (empty($guesserId)) {
            $guesserId = ApiErrorCodeGuesser::class;
            $container->setDefinition($guesserId, new Definition($guesserId));
        }

        if (!$container->hasDefinition($guesserId)) {
            throw new ServiceNotFoundException($guesserId, ApiResponseListener::class);
        }

        $guesserDefinition = $container->getDefinition($guesserId);

        if (!is_subclass_of($guesserDefinition->getClass(), ApiErrorCodeGuesserInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" should implements "%s" interface',
                $guesserDefinition->getClass(),
                ApiErrorCodeGuesserInterface::class
            ));
        }

        $container
            ->getDefinition(ApiResponseListener::class)
            ->replaceArgument(0, $guesserDefinition)
        ;
    }
}
