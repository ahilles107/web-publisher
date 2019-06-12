<?php

declare(strict_types=1);
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

interface ArticleStatisticsInterface
{
    public function getArticle(): ArticleInterface;

    public function setArticle(ArticleInterface $article): void;

    public function getImpressions(): int;

    public function setImpressions(int $impressionsNumber): void;

    public function getPageViews(): int;

    public function setPageViews(int $pageViewsNumber): void;

    public function getClickRate(): float;

    public function setClickRate(float $internalClickRate): void;
}
