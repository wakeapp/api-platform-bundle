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
use MarfaTech\Component\DtoResolver\Dto\DtoResolverTrait;

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
