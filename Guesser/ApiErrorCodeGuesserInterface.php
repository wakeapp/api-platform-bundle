<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Guesser;

use Throwable;

interface ApiErrorCodeGuesserInterface
{
    /**
     * Guess error code by received exception
     *
     * @param Throwable $exception
     *
     * @return int|null
     */
    public function guessErrorCode(Throwable $exception): ?int;
}
