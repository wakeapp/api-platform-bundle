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

namespace Wakeapp\Bundle\ApiPlatformBundle\Factory;

use Linkin\Bundle\SwaggerResolverBundle\Factory\SwaggerResolverFactory;
use RuntimeException;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Component\DtoResolver\Dto\CollectionDtoResolverInterface;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;
use function is_subclass_of;
use function sprintf;

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
            throw new RuntimeException(sprintf(
                'Received class should implement "%s"',
                CollectionDtoResolverInterface::class
            ));
        }

        /** @var CollectionDtoResolverInterface $className */
        $resolver = $this->factory->createForDefinition($className::getItemDtoClassName());

        return new $className($resolver);
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
            throw new RuntimeException(sprintf(
                'Received class should implement "%s"',
                DtoResolverInterface::class
            ));
        }

        $resolver = $this->factory->createForDefinition($className);

        return new $className($data, $resolver);
    }

    /**
     * @param string $className
     * @param ApiRequest $request
     * @param bool $withHeaders
     * @param bool $withFiles
     *
     * @return DtoResolverInterface
     */
    public function createApiDtoByRequest(
        string $className,
        ApiRequest $request,
        bool $withHeaders = false,
        bool $withFiles = false
    ): DtoResolverInterface {
        if (!is_subclass_of($className, DtoResolverInterface::class)) {
            throw new RuntimeException(sprintf(
                'Received class should implement "%s"',
                DtoResolverInterface::class
            ));
        }

        $resolver = $this->factory->createForRequest($request);
        $data = $this->getDataForRequest($request, $withHeaders, $withFiles);

        return new $className($data, $resolver);
    }

    /**
     * @param ApiRequest $request
     * @param bool $withHeaders
     * @param bool $withFiles
     *
     * @return array
     */
    private function getDataForRequest(ApiRequest $request, bool $withHeaders, bool $withFiles): array
    {
        $data = $this->getRequestDataByMethod($request);

        if ($withHeaders) {
            $data += $request->headers->all();
        }

        if ($withFiles) {
            $data += $request->files->all();
        }

        return $data;
    }

    /**
     * @param ApiRequest $request
     *
     * @return array
     */
    private function getRequestDataByMethod(ApiRequest $request): array
    {
        $requestMethod = $request->getMethod();
        $data = $request->attributes->all();

        if ($requestMethod === ApiRequest::METHOD_GET || $requestMethod === ApiRequest::METHOD_DELETE) {
            return $data + $request->query->all();
        }

        return $data + $request->body->all() + $request->request->all();
    }
}
