<?php

declare(strict_types=1);

namespace Wakeapp\Bundle\ApiPlatformBundle\Dto;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;
use Wakeapp\Component\DtoResolver\Dto\AbstractDtoResolver;

class ApiDebugExceptionResultDto extends AbstractDtoResolver
{
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
    public function resolve(array $data): AbstractDtoResolver
    {
        $previous = $data['previous'];

        if (!$previous instanceof Throwable) {
            return parent::resolve($data);
        }

        $previousDto = new self();
        $previousDto
            ->injectResolver($this->getOptionsResolver())
            ->resolve([
                'code' => $previous->getCode(),
                'file' => $previous->getFile(),
                'line' => $previous->getLine(),
                'message' => $previous->getMessage(),
                'previous' => $previous->getPrevious(),
            ])
        ;

        $data['previous'] = $previousDto;

        return parent::resolve($data);
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
     * {@inheritdoc}
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
