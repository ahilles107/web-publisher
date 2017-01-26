<?php

declare(strict_types=1);

/*
 * This file is part of the Superdesk Web Publisher Core Bundle.
 *
 * Copyright 2016 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2016 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\CoreBundle\EventListener;

use SWP\Bundle\ContentBundle\Event\ArticleEvent;
use SWP\Bundle\ContentListBundle\Event\ContentListEvent;
use SWP\Bundle\CoreBundle\Matcher\ArticleCriteriaMatcherInterface;
use SWP\Component\Common\Criteria\Criteria;
use SWP\Component\ContentList\ContentListEvents;
use SWP\Component\ContentList\Model\ContentListInterface;
use SWP\Component\ContentList\Model\ContentListItemInterface;
use SWP\Component\ContentList\Model\ListContentInterface;
use SWP\Component\ContentList\Repository\ContentListRepositoryInterface;
use SWP\Component\Storage\Factory\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AutomaticListAddArticleListener
{
    /**
     * @var ContentListRepositoryInterface
     */
    private $listRepository;

    /**
     * @var FactoryInterface
     */
    private $listItemFactory;

    /**
     * @var ArticleCriteriaMatcherInterface
     */
    private $articleCriteriaMatcher;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        ContentListRepositoryInterface $listRepository,
        FactoryInterface $listItemFactory,
        ArticleCriteriaMatcherInterface $articleCriteriaMatcher,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->listRepository = $listRepository;
        $this->listItemFactory = $listItemFactory;
        $this->articleCriteriaMatcher = $articleCriteriaMatcher;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ArticleEvent $event
     */
    public function addArticleToList(ArticleEvent $event)
    {
        $article = $event->getArticle();
        /** @var ContentListInterface[] $contentLists */
        $contentLists = $this->listRepository->findAll();

        foreach ($contentLists as $contentList) {
            $filters = $contentList->getFilters();

            if ($this->articleCriteriaMatcher->match($article, new Criteria($filters))) {
                /* @var ContentListItemInterface $contentListItem */
                $contentListItem = $this->listItemFactory->create();

                if ($article instanceof ListContentInterface) {
                    $contentListItem->setContent($article);
                }

                $contentListItem->setPosition($contentList->getItems()->count());
                $contentList->addItem($contentListItem);
                $this->eventDispatcher->dispatch(
                    ContentListEvents::POST_ITEM_ADD,
                    new ContentListEvent($contentList, $contentListItem)
                );
            }
        }
    }
}