<?php

declare(strict_types=1);

/*
 * This file is part of the Superdesk Web Publisher Core Bundle.
 *
 * Copyright 2018 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2018 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\CoreBundle\Controller;

use function array_key_exists;
use function parse_url;
use function str_replace;
use function strpos;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SWP\Bundle\ContentBundle\ArticleEvents;
use SWP\Bundle\ContentBundle\Event\ArticleEvent;
use SWP\Bundle\ContentBundle\Form\Type\ArticleCommentsType;
use SWP\Component\Common\Exception\NotFoundHttpException;
use SWP\Component\Common\Response\ResponseContext;
use SWP\Component\Common\Response\SingleResourceResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleCommentsController extends Controller
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Update article comments number",
     *     statusCodes={
     *         200="Returned on success.",
     *         404="Return when article was not found"
     *     },
     * )
     * @Route("/api/{version}/content/articles", options={"expose"=true}, defaults={"version"="v1"}, name="swp_api_core_article_comments")
     * @Method("PATCH")
     */
    public function updateAction(Request $request)
    {
        $repository = $this->get('swp.repository.article');
        $articleResolver = $this->container->get('swp.resolver.article');

        $form = $this->createForm(ArticleCommentsType::class, [], ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $article = null;
            if (null !== $data['url']) {
                $article = strpos($data['url'], '/r/') ? $repository->findOneBySlug(
                    str_replace('/r/', '', $this->getFragmentFromUrl($data['url'], 'path'))
                ) : $articleResolver->resolve($data['url']);
            } elseif (null !== $data['id']) {
                $article = $repository->findOneBy(['id' => $data['id']]);
            }

            if (null === $article) {
                throw new NotFoundHttpException('Article was not found');
            }

            $article->setCommentsCount((int) $data['commentsCount']);
            $article->cancelTimestampable(true);
            $repository->flush();

            $this->container->get('event_dispatcher')->dispatch(ArticleEvents::POST_UPDATE, new ArticleEvent(
                $article,
                $article->getPackage(),
                ArticleEvents::POST_UPDATE
            ));

            return new SingleResourceResponse($article);
        }

        return new SingleResourceResponse($form, new ResponseContext(400));
    }

    private function getFragmentFromUrl(string $url, string $fragment): ?string
    {
        $fragments = parse_url($url);
        if (!array_key_exists($fragment, $fragments)) {
            return null;
        }

        return $fragments[$fragment];
    }
}
