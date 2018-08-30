<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Guesser;

use Symfony\Component\HttpFoundation\Request;

interface ApiAreaGuesserInterface
{
    /**
     * Check is received request related to the API area
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isApiRequest(Request $request): bool;
}
