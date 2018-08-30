<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesserInterface;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;

class ApiExceptionListener
{
    /**
     * @var ApiErrorCodeGuesserInterface
     */
    private $guesser;

    /**
     * @param ApiErrorCodeGuesserInterface $guesser
     */
    public function __construct(ApiErrorCodeGuesserInterface $guesser)
    {
        $this->guesser = $guesser;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$event->getRequest() instanceof ApiRequest) {
            return;
        }

        $exception = $event->getException();

        if ($exception instanceof ApiException) {
            return;
        }

        $apiErrorCode = $this->guesser->guessErrorCode($exception);
        $apiException = new ApiException($apiErrorCode, $exception->getMessage(), $exception);

        $event->setException($apiException);
    }
}
