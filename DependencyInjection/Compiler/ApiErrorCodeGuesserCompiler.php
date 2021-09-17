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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use MarfaTech\Bundle\ApiPlatformBundle\EventListener\ApiResponseListener;
use MarfaTech\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesser;
use MarfaTech\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesserInterface;
use function is_subclass_of;
use function sprintf;

class ApiErrorCodeGuesserCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $guesserId = $container->getParameter('marfatech_api_platform.error_code_guesser_service');
        $container->getParameterBag()->remove('marfatech_api_platform.error_code_guesser_service');

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

        $container->setAlias(ApiErrorCodeGuesserInterface::class, $guesserDefinition->getClass());

        $container
            ->getDefinition(ApiResponseListener::class)
            ->replaceArgument(0, $guesserDefinition)
        ;
    }
}
