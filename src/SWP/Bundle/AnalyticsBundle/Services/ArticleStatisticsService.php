<?php

declare(strict_types=1);

namespace SWP\Bundle\AnalyticsBundle\Services;

use SWP\Bundle\AnalyticsBundle\Model\ArticleStatistics;
use SWP\Bundle\AnalyticsBundle\Model\ArticleStatisticsInterface;

final class ArticleStatisticsService implements ArticleStatisticsServiceInterface
{
    public function addArticleEvent(int $articleId, string $action, array $data): ArticleStatisticsInterface
    {
        return new ArticleStatistics();
    }
}
