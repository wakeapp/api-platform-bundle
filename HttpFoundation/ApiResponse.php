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

use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * @var JsonSerializable|null
     */
    private $dataDto = null;

    /**
     * @param JsonSerializable|null $dataDto
     * @param array $headers
     */
    public function __construct(?JsonSerializable $dataDto = null, array $headers = [])
    {
        $this->dataDto = $dataDto;

        parent::__construct($dataDto, self::HTTP_OK, $headers);
    }

    /**
     * @return JsonSerializable|null
     */
    public function getDataDto(): ?JsonSerializable
    {
        return $this->dataDto;
    }
}
