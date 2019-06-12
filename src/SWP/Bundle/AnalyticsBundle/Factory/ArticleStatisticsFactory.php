<?php

declare(strict_types=1);

/*
 * This file is part of the Superdesk Web Publisher Analytics Bundle.
 *
 * Copyright 2019 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2019 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\AnalyticsBundle\Command;

use SWP\Bundle\AnalyticsBundle\Model\ArticleStatistics;
use SWP\Component\Storage\Factory\FactoryInterface;

class ArticleStatisticsFactory implements FactoryInterface
{
    public function create()
    {
        return new ArticleStatistics();
    }
}
