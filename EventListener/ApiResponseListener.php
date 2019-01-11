<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Translation\TranslatorInterface;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiDebugExceptionResultDto;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\Factory\ApiDtoFactory;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiResponse;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesserInterface;

class ApiResponseListener
{
    /**
     * @var string
     */
    private $apiResultDtoClass;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var ApiDtoFactory
     */
    private $dtoFactory;

    /**
     * @var TranslatorInterface|null
     */
    private $translator = null;

    /**
     * @param ApiErrorCodeGuesserInterface $guesser
     * @param ApiDtoFactory $dtoFactory
     * @param TranslatorInterface|null $translator
     * @param string $apiResultDtoClass
     * @param bool $debug
     */
    public function __construct(
        ApiErrorCodeGuesserInterface $guesser,
        ApiDtoFactory $dtoFactory,
        ?TranslatorInterface $translator = null,
        string $apiResultDtoClass = ApiResultDto::class,
        bool $debug = false
    ) {
        $this->guesser = $guesser;
        $this->apiResultDtoClass = $apiResultDtoClass;
        $this->debug = $debug;
        $this->dtoFactory = $dtoFactory;
        $this->translator = $translator;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$event->getRequest() instanceof ApiRequest) {
            return;
        }

        $exception = $event->getException();
        $statusCode = ApiException::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof ApiException) {
            $statusCode = $exception->getCode();
        } else {
            $statusCode = $this->guesser->guessErrorCode($exception);
        }

        $data = null;

        if ($statusCode === ApiException::USER_INFO_ERROR) {
            $message = $exception->getMessage();
        } elseif ($this->translator) {
            $message = $this->translator->trans((string) $statusCode, [], 'api_response_code');
        } else {
            $message = (string) $statusCode;
        }

        if ($this->debug) {
            $data = new ApiDebugExceptionResultDto();
            $data->resolve([
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'previous' => $exception->getPrevious(),
            ]);
        }

        $resultDto = $this->dtoFactory->createApiDto($this->apiResultDtoClass, [
            'data' => $data,
            'code' => $statusCode,
            'message' => $message,
        ]);

        $httpStatus = JsonResponse::HTTP_OK;

        if ($statusCode >= 400 && $statusCode < 600) {
            $httpStatus = $statusCode;
        }

        $event->allowCustomResponseCode();
        $event->setResponse(new JsonResponse($resultDto, $statusCode));
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (!$response instanceof ApiResponse) {
            return;
        }

        if ($this->translator) {
            $message = $this->translator->trans('0', [], 'api_response_code');
        }

        $resultDto = $this->dtoFactory->createApiDto($this->apiResultDtoClass, [
            'data' => $response->getDataDto(),
            'code' => 0,
            'message' => $message,
        ]);

        $event->setResponse(new JsonResponse($resultDto));
    }
}
