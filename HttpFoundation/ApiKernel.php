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

namespace Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiAreaGuesserInterface;

class ApiKernel extends HttpKernel
{
    public const API_VERSION_BY_DEFAULT = 1;

    /**
     * @var ApiAreaGuesserInterface
     */
    private $guesser;

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true): Response
    {
        if ($this->guesser->isApiRequest($request)) {
            $apiVersion = $this->guesser->getApiVersion($request) ?? self::API_VERSION_BY_DEFAULT;
            $request = new ApiRequest($request, $apiVersion);
        }

        return parent::handle($request, $type, $catch);
    }

    /**
     * @param ApiAreaGuesserInterface $guesser
     *
     * @required
     */
    public function setApiAreaGuesser(ApiAreaGuesserInterface $guesser)
    {
        $this->guesser = $guesser;
    }
}
