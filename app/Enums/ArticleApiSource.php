<?php

declare(strict_types=1);

namespace App\Enums;

enum ArticleApiSource: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
    case NYTIMES = 'nytimes';
}
