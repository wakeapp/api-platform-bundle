<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;

class MinimalApiVersionRequestListener
{
    /**
     * @var int
     */
    protected $minimalApiVersion;

    /**
     * @param int $minimalApiVersion
     */
    public function __construct(int $minimalApiVersion)
    {
        $this->minimalApiVersion = $minimalApiVersion;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request instanceof ApiRequest) {
            return;
        }

        if ($request->getApiVersion() < $this->minimalApiVersion) {
            throw new ApiException(ApiException::API_VERSION_MINIMAL_NO_MATCHING);
        }
    }
}
