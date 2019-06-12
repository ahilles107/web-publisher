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

use DateTime;
use SWP\Bundle\ContentBundle\Model\RouteInterface;
use SWP\Bundle\ContentBundle\Model\ArticleInterface;

class ArticleEvent implements ArticleEventInterface
{
    protected $action;

    protected $pageViewSource;

    protected $impressionRoute;

    protected $impressionArticle;

    protected $impressionType;

    protected $createdAt;

    protected $uuid;

    public function __construct(string $uuid, string $action)
    {
        $this->uuid = $uuid;
        $this->action = $action;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getPageViewSource(): ?string
    {
        return $this->pageViewSource;
    }

    public function setPageViewSource(string $pageViewSource): void
    {
        $this->pageViewSource = $pageViewSource;
    }

    public function getImpressionRoute(): ?RouteInterface
    {
        return $this->impressionRoute;
    }

    public function setImpressionRoute(?RouteInterface $impressionRoute): void
    {
        $this->impressionRoute = $impressionRoute;
    }

    public function getImpressionArticle(): ?ArticleInterface
    {
        return $this->impressionArticle;
    }

    public function setImpressionArticle(?ArticleInterface $impressionArticle): void
    {
        $this->impressionArticle = $impressionArticle;
    }

    public function getImpressionType(): ?string
    {
        return $this->impressionType;
    }

    public function setImpressionType(?string $impressionType): void
    {
        $this->impressionType = $impressionType;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
