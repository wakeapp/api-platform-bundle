<?php

declare(strict_types=1);

/*
 * This file is part of the ApiPlatformBundle package.
 *
 * (c) MarfaTech <https://marfa-tech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MarfaTech\Bundle\ApiPlatformBundle\Dto;

use MarfaTech\Component\DtoResolver\Dto\DtoResolverInterface;

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
