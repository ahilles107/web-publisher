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
namespace SWP\Bundle\WebRendererBundle\Locator;

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Symfony\Component\Filesystem\Filesystem;
use Sylius\Bundle\ThemeBundle\Locator\ResourceLocatorInterface;

class ApplicationResourceLocator implements ResourceLocatorInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    private $deviceDetector;

    private $lookForDeviceTemplate;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem, $deviceDetector, $lookForDeviceTemplate = false)
    {
        $this->filesystem = $filesystem;
        $this->deviceDetector = $deviceDetector;
        $this->lookForDeviceTemplate = $lookForDeviceTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function locateResource($resourceName, ThemeInterface $theme)
    {
        $paths = $this->getApplicationPaths($resourceName, $theme);
        foreach ($paths as $path) {
            if ($this->filesystem->exists($path)) {
                return $path;
            }
        }

        throw new ResourceNotFoundException($resourceName, $theme);
    }

    public function getApplicationPaths($resourceName, ThemeInterface $theme)
    {
        $paths = array();
        if ($this->lookForDeviceTemplate) {
            $paths[] = sprintf('%s/%s/%s', $theme->getPath(), $this->deviceDetector->getType(), $resourceName);
        }
        $paths[] = sprintf('%s/%s', $theme->getPath(), $resourceName);

        return $paths;
    }
}
