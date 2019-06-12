<?php

/*
 * This file is part of the Superdesk Web Publisher Analytics Bundle.
 *
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2017 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\AnalyticsBundle\Model;

use SWP\Bundle\ContentBundle\Model\ArticleInterface;
use SWP\Component\Common\Model\TimestampableInterface;
use SWP\Component\Common\Model\TimestampableTrait;

class ArticleStatistics implements ArticleStatisticsInterface, TimestampableInterface
{
    use TimestampableTrait;

    protected $article;

    protected $impressions = 0;

    protected $pageViews = 0;

    protected $clickRate = 0;

    public function getArticle(): ArticleInterface
    {
        return $this->article;
    }

    public function setArticle(ArticleInterface $article): void
    {
        $this->article = $article;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function setImpressions(int $impressions): void
    {
        $this->impressions = $impressions;
    }

    public function getPageViews(): int
    {
        return $this->pageViews;
    }

    public function setPageViews(int $pageViews): void
    {
        $this->pageViews = $pageViews;
    }

    public function getClickRate(): float
    {
        return $this->clickRate;
    }

    public function setClickRate(float $clickRate): void
    {
        $this->clickRate = $clickRate;
    }
}
