<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Factory;

use Linkin\Bundle\SwaggerResolverBundle\Factory\SwaggerResolverFactory;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Component\DtoResolver\Dto\CollectionDtoResolverInterface;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;

class ApiDtoFactory
{
    /**
     * @var SwaggerResolverFactory
     */
    private $factory;

    /**
     * @param SwaggerResolverFactory $factory
     */
    public function __construct(SwaggerResolverFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $className
     *
     * @return CollectionDtoResolverInterface
     */
    public function createApiCollectionDto(string $className): CollectionDtoResolverInterface
    {
        if (!is_subclass_of($className, CollectionDtoResolverInterface::class)) {
            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR);
        }

        /** @var CollectionDtoResolverInterface $collectionDto */
        $collectionDto = new $className();
        $resolver = $this->factory->createForDefinition($collectionDto->getEntryDtoClassName());

        $collectionDto->injectResolver($resolver);

        return $collectionDto;
    }

    /**
     * @param string $className
     * @param array $data
     *
     * @return DtoResolverInterface
     */
    public function createApiDto(string $className, array $data): DtoResolverInterface
    {
        if (!is_subclass_of($className, DtoResolverInterface::class)) {
            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR);
        }

        /** @var DtoResolverInterface $resultDto */
        $resultDto = new $className();
        $resolver = $this->factory->createForDefinition($className);

        $resultDto->injectResolver($resolver);
        $resultDto->resolve($data);

        return $resultDto;
    }
}
