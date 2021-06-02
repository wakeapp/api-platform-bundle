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

namespace Wakeapp\Bundle\ApiPlatformBundle\EventListener;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiDebugExceptionResultDto;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\Factory\ApiDtoFactory;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesserInterface;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiResponse;

use function explode;
use function sprintf;

class ApiResponseListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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
     * @var bool
     */
    private $isException = false;

    /**
     * @var TranslatorInterface|null
     */
    private $translator = null;

    /**
     * @var ApiErrorCodeGuesserInterface
     */
    private $guesser;

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
     * @param ExceptionEvent $event
     *
     * @throws ExceptionInterface
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->getRequest() instanceof ApiRequest) {
            return;
        }

        $exception = $event->getThrowable();
        $errorCode = $this->guesser->guessErrorCode($exception);

        if (!$errorCode) {
            $errorCode = $exception->getCode();
        }

        if (!$errorCode) {
            $errorCode = ApiResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($errorCode >= 400 && $errorCode < 600) {
            $httpStatusCode = $errorCode;
        } else {
            $httpStatusCode = ApiResponse::HTTP_OK;
        }

        $data = null;

        if ($errorCode === ApiException::USER_INFO_ERROR) {
            $message = $exception->getMessage();
        } elseif ($this->translator) {
            $message = $this->translator->trans((string) $errorCode, [], 'api_response_code');
        } else {
            $message = (string) $errorCode;
        }

        if ($this->debug) {
            $data = new ApiDebugExceptionResultDto([
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'stackTrace' => explode("\n", $exception->getTraceAsString()),
            ]);
        }

        $path = $event->getRequest()->getRequestUri();
        $method = $event->getRequest()->getMethod();

        $this->logger->notice(sprintf('%s %s', $method, $path), [$exception]);

        $resultDto = $this->dtoFactory->createApiDto($this->apiResultDtoClass, [
            'data' => $data,
            'code' => $errorCode,
            'message' => $message,
        ]);

        $this->isException = true;

        $response = new ApiResponse($resultDto);
        $response->setStatusCode($httpStatusCode);

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (!$response instanceof ApiResponse) {
            return;
        }

        if ($this->isException) {
            return;
        }

        $message = '0';

        if ($this->translator) {
            $message = $this->translator->trans($message, [], 'api_response_code');
        }

        $resultDto = $this->dtoFactory->createApiDto($this->apiResultDtoClass, [
            'data' => $response->getDataDto(),
            'code' => 0,
            'message' => $message,
        ]);

        $headers = $response->headers->all();

        $event->setResponse(new ApiResponse($resultDto, $headers));
    }
}
