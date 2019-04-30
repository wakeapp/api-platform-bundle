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

use Symfony\Component\HttpFoundation\Request;

interface ApiAreaGuesserInterface
{
    /**
     * Returns api version of the request
     *
     * @param Request $request
     *
     * @return int|null
     */
    public function getApiVersion(Request $request): ?int;

    /**
     * Check is received request related to the API area
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isApiRequest(Request $request): bool;
}
