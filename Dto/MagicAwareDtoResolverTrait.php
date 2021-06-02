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

namespace Wakeapp\Bundle\ApiPlatformBundle\Dto;

use RuntimeException;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverTrait;
use function lcfirst;
use function sprintf;
use function strpos;
use function substr;

trait MagicAwareDtoResolverTrait
{
    use DtoResolverTrait;

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (strpos($name, 'get') !== 0) {
            throw new RuntimeException(sprintf('Method %s does not exists', $name));
        }

        $property = lcfirst(substr($name, 3));

        return $this->$property;
    }
}
