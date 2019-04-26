<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Exception;

use RuntimeException;
use Throwable;

class ApiException extends RuntimeException
{
    // Database Errors 1 - 9
    public const DATABASE_UNEXPECTED_ERROR = 1;

    // Application Package Name Errors 1x
    public const APPLICATION_PACKAGE_NAME_NOT_FOUND = 10;
    public const APPLICATION_PACKAGE_NAME_EMPTY = 11;

    // UDID Errors 2x
    public const UDID_NOT_FOUND = 20;
    public const UDID_EMPTY = 21;
    public const UDID_IS_DIFFERENT = 22;

    // HTTP Errors 400 - 599
    public const HTTP_BAD_REQUEST_DATA = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    // Client Version Errors 6xx
    public const CLIENT_VERSION_NOT_FOUND = 600;
    public const CLIENT_VERSION_EMPTY = 601;
    public const CLIENT_VERSION_NEED_UPDATE = 602;
    public const CLIENT_VERSION_INVALID_FORMAT = 603;

    // Sign Errors 7xx
    public const SIGN_INVALID = 700;
    public const SIGN_NOT_FOUND = 701;

    // Token Errors 8xx
    public const TOKEN_ENCODE_FAIL = 800;
    public const TOKEN_NOT_FOUND = 801;
    public const TOKEN_INVALID = 802;

    // Receipt Errors 9xx
    public const RECEIPT_UNEXPECTED_ERROR = 900;

    // Api version Errors 11xx
    public const API_VERSION_MINIMAL_NO_MATCHING = 1101;

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
