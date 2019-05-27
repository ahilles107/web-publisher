<?php

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

namespace SWP\Bundle\CoreBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use SWP\Bundle\CoreBundle\Form\Type\FacebookApplicationType;
use SWP\Bundle\CoreBundle\Model\FacebookApplication;
use SWP\Bundle\FacebookInstantArticlesBundle\Model\ApplicationInterface;
use SWP\Component\Common\Criteria\Criteria;
use SWP\Component\Common\Pagination\PaginationData;
use SWP\Component\Common\Response\ResourcesListResponse;
use SWP\Component\Common\Response\ResponseContext;
use SWP\Component\Common\Response\SingleResourceResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FbApplicationController extends Controller
{
    /**
     * @Operation(
     *     tags={""},
     *     summary="Lists Facebook Applications",
     *     @SWG\Parameter(
     *         name="sorting",
     *         in="query",
     *         description="todo",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned on success."
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Unexpected error."
     *     )
     * )
     *
     * @Route("/api/{version}/facebook/applications/", options={"expose"=true}, defaults={"version"="v2"}, methods={"GET"}, name="swp_api_list_facebook_applications")
     */
    public function listAction(Request $request)
    {
        $repository = $this->get('swp.repository.facebook_application');

        $items = $repository->getPaginatedByCriteria(
            new Criteria(),
            $request->query->get('sorting', ['id' => 'asc']),
            new PaginationData($request)
        );

        return new ResourcesListResponse($items);
    }

    /**
     * @Operation(
     *     tags={""},
     *     summary="Create Facebook application",
     *     @SWG\Parameter(
     *         name="appId",
     *         in="formData",
     *         description="",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="appSecret",
     *         in="formData",
     *         description="",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Returned on success."
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when not valid data."
     *     )
     * )
     *
     * @Route("/api/{version}/facebook/applications/", options={"expose"=true}, defaults={"version"="v2"}, methods={"POST"}, name="swp_api_create_facebook_applications")
     */
    public function createAction(Request $request)
    {
        /* @var FacebookApplication $feed */
        $application = $this->get('swp.factory.facebook_application')->create();
        $form = $form = $this->get('form.factory')->createNamed('', FacebookApplicationType::class, $application, ['method' => $request->getMethod()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->checkIfApplicationExists($application);
            $this->get('swp.repository.facebook_application')->add($application);

            return new SingleResourceResponse($application, new ResponseContext(201));
        }

        return new SingleResourceResponse($form, new ResponseContext(400));
    }

    /**
     * @Operation(
     *     tags={""},
     *     summary="Delete Facebook application",
     *     @SWG\Response(
     *         response="204",
     *         description="Returned on success."
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Unexpected error."
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Application not found"
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="Application is used by page"
     *     )
     * )
     *
     * @Route("/api/{version}/facebook/applications/{id}", options={"expose"=true}, defaults={"version"="v2"}, methods={"DELETE"}, name="swp_api_delete_facebook_applications")
     */
    public function deleteAction($id)
    {
        $repository = $this->get('swp.repository.facebook_application');
        if (null === $application = $repository->findOneBy(['id' => $id])) {
            throw new NotFoundHttpException('There is no Application with provided id!');
        }

        if (null !== $page = $this->get('swp.repository.facebook_page')->findOneBy(['id' => $id])) {
            throw new ConflictHttpException(sprintf('This Application is used by page with id: %s!', $page->getId()));
        }

        $repository->remove($application);

        return new SingleResourceResponse(null, new ResponseContext(204));
    }

    /**
     * @param ApplicationInterface $application
     */
    private function checkIfApplicationExists(ApplicationInterface $application)
    {
        if (null !== $this->get('swp.repository.facebook_application')->findOneBy([
                'appId' => $application->getAppId(),
            ])
        ) {
            throw new ConflictHttpException('This Application already exists!');
        }
    }
}
