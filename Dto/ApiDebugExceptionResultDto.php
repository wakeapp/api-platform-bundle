<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Dto;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverTrait;

class ApiDebugExceptionResultDto implements DtoResolverInterface
{
    use DtoResolverTrait {
        DtoResolverTrait::resolve as parentResolve;
    }

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $line;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $stackTrace;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getStackTrace(): array
    {
        return $this->stackTrace;
    }
}
