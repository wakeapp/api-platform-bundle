<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Logger;

use Psr\Log\LoggerAwareTrait;

class ApiPlatformLogger
{
    use LoggerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function notice(string $message, array $params = [])
    {
        if (null !== $this->logger) {
            $this->logger->notice($message, $params);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug(string $message, array $params = [])
    {
        if (null !== $this->logger) {
            $this->logger->debug($message, $params);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function error(string $message, array $params = [])
    {
        if (null !== $this->logger) {
            $this->logger->error($message, $params);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function critical(string $message, array $params = [])
    {
        if (null !== $this->logger) {
            $this->logger->critical($message, $params);
        }
    }
}
