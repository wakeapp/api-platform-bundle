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

namespace Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection;

use Closure;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDtoInterface;
use function is_subclass_of;
use function sprintf;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wakeapp_api_platform');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('wakeapp_api_platform');
        }

        $rootNode
            ->children()
                ->integerNode('minimal_api_version')
                    ->min(1)
                    ->defaultValue(1)
                ->end()
                ->booleanNode('response_debug')->defaultFalse()->end()
                ->scalarNode('api_result_dto_class')
                    ->defaultValue(ApiResultDto::class)
                    ->cannotBeEmpty()
                    ->validate()->always($this->validationForApiResultDtoClass())->end()
                ->end()
                ->scalarNode('api_area_guesser_service')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('error_code_guesser_service')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return Closure
     */
    private function validationForApiResultDtoClass(): Closure
    {
        return function ($apiResultClass) {
            if (!is_subclass_of($apiResultClass, ApiResultDtoInterface::class)) {
                throw new InvalidConfigurationException(sprintf(
                    'Parameter "api_result_dto_class" should contain class which implements "%s"',
                    ApiResultDtoInterface::class
                ));
            }

            return $apiResultClass;
        };
    }
}
