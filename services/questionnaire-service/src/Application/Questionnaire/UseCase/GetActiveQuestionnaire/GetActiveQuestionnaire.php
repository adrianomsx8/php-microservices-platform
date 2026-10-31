<?php

namespace App\Application\UseCase\GetActiveQuestionnaire;

use App\Domain\Entity\Questionnaire;
use App\Domain\Repository\QuestionnaireRepositoryInterface;

final  class GetActiveQuestionnaire
{
    public function __construct(
        private QuestionnaireRepositoryInterface $repository
    ) {
    }

    public function execute(string $code): Questionnaire
    {
        $questionnaire = $this->repository
            ->findPublishedByCode($code);

        if ($questionnaire === null) {
            throw new \DomainException(
                'Questionário publicado não encontrado.'
            );
        }

        return $questionnaire;
    }
}