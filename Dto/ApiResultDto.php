<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Dto;

use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverTrait;

class ApiResultDto implements ApiResultDtoInterface
{
    use DtoResolverTrait;

    /**
     * @var int
     */
    protected $code = 0;

    /**
     * @var DtoResolverInterface|null
     */
    protected $data = null;

    /**
     * @var string
     */
    protected $message;

    /**
     * {@inheritdoc}
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): ?DtoResolverInterface
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
