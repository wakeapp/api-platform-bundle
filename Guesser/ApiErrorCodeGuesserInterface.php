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
