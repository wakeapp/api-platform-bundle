<?php

declare(strict_types=1);

/*
 * This file is part of the ApiPlatformBundle package.
 *
 * (c) MarfaTech <https://marfa-tech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MarfaTech\Bundle\ApiPlatformBundle\Exception;

use RuntimeException;
use Throwable;

class ApiException extends RuntimeException
{
    // HTTP Errors 400 - 599
    public const HTTP_BAD_REQUEST_DATA = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_CONFLICT = 409;
    public const HTTP_GONE = 410;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_SERVICE_UNAVAILABLE = 503;

    /**
     * @var int customizable error for which should be displayed for user as is
     */
    public const USER_INFO_ERROR = 10000;

    /**
     * @param int $code
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(int $code, string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
