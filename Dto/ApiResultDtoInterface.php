<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Dto;

use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;

interface ApiResultDtoInterface extends DtoResolverInterface
{
    /**
     * Returns response DTO
     *
     * @return DtoResolverInterface|null
     */
    public function getData(): ?DtoResolverInterface;

    /**
     * Returns api response code
     *
     * @return int
     */
    public function getCode(): int;

    /**
     * Returns api response message
     *
     * @return string
     */
    public function getMessage(): string;
}
