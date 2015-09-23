<?php

/**
 * This file is part of the Superdesk Web Publisher Web Renderer Bundle
 *
 * Copyright 2015 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2015 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */

namespace SWP\WebRendererBundle\Entity;

use SWP\WebRendererBundle\Entity\Page;

/**
 * PageRepository
 */
class PageContentRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get Query for Page searched by page
     *
     * @param Page $page
     *
     * @return \Doctrine\ORM\Query
     */
    public function getForPage($page)
    {
        $qb = $this->createQueryBuilder('pa')
            ->where('pa.page = :page')
            ->setParameters([
                'page' => $page,
            ]);

        return $qb->getQuery();
    }
}
