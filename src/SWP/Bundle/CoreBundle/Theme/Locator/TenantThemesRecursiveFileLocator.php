<?php

/*
 * This file is part of the Superdesk Web Publisher Core Bundle.
 *
 * Copyright 2017 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2017 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\CoreBundle\Theme\Locator;

use Sylius\Bundle\ThemeBundle\Locator\FileLocatorInterface;
use Sylius\Bundle\ThemeBundle\Factory\FinderFactoryInterface;
use Symfony\Component\Finder\SplFileInfo;

class TenantThemesRecursiveFileLocator implements FileLocatorInterface
{
    protected $finderFactory;

    protected $paths;

    public function __construct(
        FinderFactoryInterface $finderFactory,
        array $paths
    ) {
        $this->finderFactory = $finderFactory;
        $this->paths = $paths;
    }

    /**
     * {@inheritdoc}
     */
    public function locateFileNamed(string $name): string
    {
        return $this->doLocateFilesNamed($name)->current();
    }

    /**
     * {@inheritdoc}
     */
    public function locateFilesNamed(string $name): array
    {
        return iterator_to_array($this->doLocateFilesNamed($name));
    }

    /**
     * @param string $name
     *
     * @return \Generator
     */
    protected function doLocateFilesNamed($name)
    {
        $this->assertNameIsNotEmpty($name);
        $found = false;
        foreach ($this->paths as $path) {
            try {
                $finder = $this->finderFactory->create();
                $finder
                    ->depth(2)
                    ->files()
                    ->followLinks()
                    ->name($name)
                    ->ignoreUnreadableDirs()
                    ->in($path);

                /** @var SplFileInfo $file */
                foreach ($finder as $file) {
                    $found = true;

                    yield $file->getPathname();
                }
            } catch (\InvalidArgumentException $exception) {
            }
        }

        if (false === $found) {
            throw new \InvalidArgumentException(sprintf(
                'The file "%s" does not exist (searched in the following directories: %s).',
                $name,
                implode(', ', $this->paths)
            ));
        }
    }

    /**
     * @param string $name
     */
    protected function assertNameIsNotEmpty($name)
    {
        if (null === $name || '' === $name) {
            throw new \InvalidArgumentException(
                'An empty file name is not valid to be located.'
            );
        }
    }
}
