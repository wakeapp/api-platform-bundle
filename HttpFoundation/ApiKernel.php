<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiAreaGuesserInterface;

class ApiKernel extends HttpKernel
{
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
            $request = new ApiRequest($request);
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
