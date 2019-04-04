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
     * @var self
     */
    protected $previous;

    /**
     * {@inheritdoc}
     */
    public function resolve(array $data): void
    {
        $previous = $data['previous'];

        if (!$previous instanceof Throwable) {
            $this->parentResolve($data);

            return;
        }

        $previousDto = new self();
        $previousDto->injectResolver($this->getOptionsResolver());
        $previousDto->resolve([
                'code' => $previous->getCode(),
                'file' => $previous->getFile(),
                'line' => $previous->getLine(),
                'message' => $previous->getMessage(),
                'previous' => $previous->getPrevious(),
            ])
        ;

        $data['previous'] = $previousDto;

        $this->parentResolve($data);
    }

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
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options): void
    {
        $options->setDefined([
            'code',
            'file',
            'line',
            'message',
            'previous',
        ]);
    }
}
