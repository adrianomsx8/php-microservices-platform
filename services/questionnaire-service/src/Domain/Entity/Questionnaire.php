<?php

namespace App\Domain\Entity;

use App\Domain\Enum\QuestionnaireStatus;

final class Questionnaire
{
    public function __construct(
        private readonly string $id,
        private readonly string $code,
        private string $title,
        private readonly int $version,
        private QuestionnaireStatus $status =
            QuestionnaireStatus::DRAFT,
    ) {
        if ($this->version < 1) {
            throw new \DomainException(
                'A versão deve ser maior que zero.'
            );
        }

        if (trim($this->code) === '') {
            throw new \DomainException(
                'O código do questionário é obrigatório.'
            );
        }

        if (trim($this->title) === '') {
            throw new \DomainException(
                'O título do questionário é obrigatório.'
            );
        }
    }

    public function publish(): void
    {
        if ($this->status !== QuestionnaireStatus::DRAFT) {
            throw new \DomainException(
                'Somente questionários em rascunho podem ser publicados.'
            );
        }

        $this->status = QuestionnaireStatus::PUBLISHED;
    }

    public function archive(): void
    {
        if ($this->status !== QuestionnaireStatus::PUBLISHED) {
            throw new \DomainException(
                'Somente questionários publicados podem ser arquivados.'
            );
        }

        $this->status = QuestionnaireStatus::ARCHIVED;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getStatus(): QuestionnaireStatus
    {
        return $this->status;
    }
}