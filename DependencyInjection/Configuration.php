<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\DependencyInjection;

use Closure;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDtoInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('wakeapp_api_platform');

        $root
            ->children()
                ->booleanNode('response_debug')->defaultFalse()->end()
                ->scalarNode('api_result_dto_class')
                    ->defaultValue('Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto')
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
