<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\EventListener;

use Exception;
use Linkin\Bundle\SwaggerResolverBundle\Factory\SwaggerResolverFactory;
use Symfony\Component\HttpKernel\Event\FilterControllerArgumentsEvent;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Component\DtoResolver\Dto\CollectionDtoResolverInterface;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;

class ApiControllerArgumentListener
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
     * @param FilterControllerArgumentsEvent $event
     */
    public function onKernelControllerArguments(FilterControllerArgumentsEvent $event): void
    {
        $apiRequest = $event->getRequest();

        if (!$apiRequest instanceof ApiRequest) {
            return;
        }

        foreach ($event->getArguments() as $argument) {
            if ($argument instanceof CollectionDtoResolverInterface) {
                $resolver = $this->factory->createForDefinition($argument->getEntryDtoClassName());
                $argument->injectResolver($resolver);

                foreach ($apiRequest->body->all() as $item) {
                    try {
                        $argument->add($item);
                    } catch (Exception|InvalidOptionsException $e) {
                        throw new ApiException(ApiException::HTTP_BAD_REQUEST_DATA, $e->getMessage());
                    }
                }

                continue;
            }

            if (!$argument instanceof DtoResolverInterface) {
                continue;
            }

            $resolver = $this->factory->createForDefinition(get_class($argument));
            $argument->injectResolver($resolver);

            try {
                $argument->resolve($apiRequest->body->all());
            } catch (InvalidOptionsException $e) {
                throw new ApiException(ApiException::HTTP_BAD_REQUEST_DATA, $e->getMessage());
            }
        }
    }
}
