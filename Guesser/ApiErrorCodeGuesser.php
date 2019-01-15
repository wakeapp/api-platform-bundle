<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Guesser;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;

class ApiErrorCodeGuesser implements ApiErrorCodeGuesserInterface
{
    /**
     * @param Throwable $exception
     *
     * @return int|null
     */
    public function guessErrorCode(Throwable $exception): ?int
    {
        if ($exception instanceof NotFoundHttpException) {
            return ApiException::HTTP_NOT_FOUND;
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return ApiException::HTTP_METHOD_NOT_ALLOWED;
        }
        
        if ($exception instanceof ApiException) {
            return $exception->getCode();
        }

        return null;
    }
}
