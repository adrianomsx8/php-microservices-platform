<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Questionnaire;

interface QuestionnaireRepositoryInterface
{
    public function save(Questionnaire $questionnaire): void;

    public function findById(string $id): ?Questionnaire;

    public function findPublishedByCode(
        string $code
    ): ?Questionnaire;
}