<?php

/**
 * This file is part of the Superdesk Web Publisher Content Bundle.
 *
 * Copyright 2015 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2015 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */
namespace SWP\Bundle\ContentBundle\Loader;

use SWP\Component\TemplatesSystem\Gimme\Loader\LoaderInterface;
use SWP\Component\TemplatesSystem\Gimme\Meta\Meta;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\Common\Cache\CacheProvider;
use SWP\Component\MultiTenancy\PathBuilder\TenantAwarePathBuilderInterface;

class ArticleMediaLoader implements LoaderInterface
{
    /**
     * @var PublishWorkflowChecker
     */
    protected $publishWorkflowChecker;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var string
     */
    protected $configurationPath;

    /**
     * @var CacheProvider
     */
    protected $metadataCache;

    /**
     * @var TenantAwarePathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @var string
     */
    protected $routeBasepaths;

    public function __construct(
        PublishWorkflowChecker $publishWorkflowChecker,
        DocumentManager $dm,
        $configurationPath,
        CacheProvider $metadataCache,
        TenantAwarePathBuilderInterface $pathBuilder,
        $routeBasepaths,
        $metaFactory
    ) {
        $this->publishWorkflowChecker = $publishWorkflowChecker;
        $this->dm = $dm;
        $this->configurationPath = $configurationPath.'/Resources/meta/media.yml';
        $this->metadataCache = $metadataCache;
        $this->pathBuilder = $pathBuilder;
        $this->routeBasepaths = $routeBasepaths;
        $this->metaFactory = $metaFactory;
    }

    /**
     * Load meta object by provided type and parameters.
     *
     * @MetaLoaderDoc(
     *     description="Article Loader loads articles from Content Repository",
     *     parameters={
     *         contentPath="SINGLE|required content path",
     *         slug="SINGLE|required content slug",
     *         pageName="COLLECTION|name of Page for required articles"
     *     }
     * )
     *
     * @param string $type         object type
     * @param array  $parameters   parameters needed to load required object type
     * @param int    $responseType response type: single meta (LoaderInterface::SINGLE) or collection of metas (LoaderInterface::COLLECTION)
     *
     * @return Meta|Meta[]|bool false if meta cannot be loaded, a Meta instance otherwise
     */
    public function load($type, array $parameters = array(), $responseType = LoaderInterface::SINGLE)
    {
        if ($responseType === LoaderInterface::COLLECTION) {
            if (array_key_exists('article', $parameters)) {
                $media = $this->dm->find(null, $parameters['article']->getValues()->getId().'/media');

                if ($media) {
                    $meta = [];
                    foreach ($media->getChildren() as $item) {
                        $meta[] = $this->metaFactory->create($item);
                    }

                    return $meta;
                }
            }
        }

        return false;
    }

    /**
     * Checks if Loader supports provided type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isSupported($type)
    {
        return in_array($type, ['articleMedia']);
    }
}
