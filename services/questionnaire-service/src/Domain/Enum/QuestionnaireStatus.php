<?php

namespace App\Domain\Enum;

enum QuestionnaireStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}