<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;

class ApiResponse extends JsonResponse
{
    /**
     * @var DtoResolverInterface|null
     */
    private $dataDto = null;

    /**
     * @param DtoResolverInterface|null $dataDto
     * @param array $headers
     */
    public function __construct(?DtoResolverInterface $dataDto = null, array $headers = [])
    {
        $this->dataDto = $dataDto;

        parent::__construct($dataDto, self::HTTP_OK, $headers);
    }

    /**
     * @return DtoResolverInterface|null
     */
    public function getDataDto(): ?DtoResolverInterface
    {
        return $this->dataDto;
    }
}
