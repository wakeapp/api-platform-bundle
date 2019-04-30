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

namespace Wakeapp\Bundle\ApiPlatformBundle\ArgumentResolver;

use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\Factory\ApiDtoFactory;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Component\DtoResolver\Dto\CollectionDtoResolverInterface;
use function is_subclass_of;

class ApiCollectionEntryDtoArgumentResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ApiDtoFactory
     */
    private $factory;

    /**
     * @param ApiDtoFactory $factory
     */
    public function __construct(ApiDtoFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $argumentType = $argument->getType();

        return $request instanceof ApiRequest && is_subclass_of($argumentType, CollectionDtoResolverInterface::class);
    }

    /**
     * @param ApiRequest|Request $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        /** @var CollectionDtoResolverInterface $resolvedArgument */
        $resolvedArgument = $this->factory->createApiCollectionDto($argument->getType());

        foreach ($request->body->all() as $item) {
            try {
                $resolvedArgument->add($item);
            } catch (Exception|InvalidOptionsException $e) {
                throw new ApiException(ApiException::HTTP_BAD_REQUEST_DATA, $e->getMessage());
            }
        }

        yield $resolvedArgument;
    }
}
