<?php

/**
 * This file is part of the Superdesk Web Publisher Web Renderer Bundle.
 *
 * Copyright 2015 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2015 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */

namespace SWP\WebRendererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentController extends Controller
{
    public function renderContentPageAction()
    {
        $context = $this->container->get('context');
        $metaLoader = $this->container->get('swp_template_engine_loader_chain');
        $currentPage = $context->getCurrentPage();

        $article = $metaLoader->load('article', ['contentPath' => $currentPage['contentPath']]);
        if ($article) {
            $context->registerMeta('article', $article);
        }

        return $this->render('views/'.$currentPage['templateName']);
    }

    public function renderContainerPageAction($contentSlug)
    {
        $context = $this->container->get('context');
        $metaLoader = $this->container->get('swp_template_engine_loader_chain');
        $currentPage = $context->getCurrentPage();

        $article = $metaLoader->load('article', ['slug' => $contentSlug]);
        if ($article) {
            $context->registerMeta('article', $article);
        } else {
            return new NotFoundHttpException('Not Found');
        }

        return $this->render('views/'.$currentPage['templateName']);
    }
}
