<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\ArgumentResolver;

use Exception;
use Generator;
use Linkin\Bundle\SwaggerResolverBundle\Factory\SwaggerResolverFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;
use function is_subclass_of;

class ApiEntryDtoArgumentResolver implements ArgumentValueResolverInterface
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
     * {@inheritDoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $request instanceof ApiRequest && is_subclass_of($argument->getType(), DtoResolverInterface::class);
    }

    /**
     * @param ApiRequest|Request $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $argumentClassName = $argument->getType();
        $resolver = $this->factory->createForDefinition($argumentClassName);

        /** @var DtoResolverInterface $resolvedArgument */
        $resolvedArgument = new $argumentClassName();
        $resolvedArgument->injectResolver($resolver);

        try {
            $resolvedArgument->resolve($request->body->all());
        } catch (Exception|InvalidOptionsException $e) {
            throw new ApiException(ApiException::HTTP_BAD_REQUEST_DATA, $e->getMessage());
        }

        yield $resolvedArgument;
    }
}
