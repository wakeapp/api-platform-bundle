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

namespace Wakeapp\Bundle\ApiPlatformBundle\Guesser;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
        if ($exception instanceof ApiException) {
            return $exception->getCode();
        }

        if ($exception instanceof BadRequestHttpException) {
            return ApiException::HTTP_BAD_REQUEST_DATA;
        }

        if ($exception instanceof UnauthorizedHttpException) {
            return ApiException::HTTP_UNAUTHORIZED;
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return ApiException::HTTP_FORBIDDEN;
        }

        if ($exception instanceof NotFoundHttpException) {
            return ApiException::HTTP_NOT_FOUND;
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return ApiException::HTTP_METHOD_NOT_ALLOWED;
        }

        if ($exception instanceof ConflictHttpException) {
            return ApiException::HTTP_CONFLICT;
        }

        if ($exception instanceof GoneHttpException) {
            return ApiException::HTTP_GONE;
        }

        if ($exception instanceof PreconditionFailedHttpException) {
            return ApiException::HTTP_PRECONDITION_FAILED;
        }

        if ($exception instanceof ServiceUnavailableHttpException) {
            return ApiException::HTTP_SERVICE_UNAVAILABLE;
        }

        return null;
    }
}
