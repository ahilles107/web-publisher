<?php

namespace SWP\TemplateEngineBundle\Gimme\Loader;

/**
 * ChainLoader is a loader that calls other loaders to load Meta objects
 */
class ChainLoader implements LoaderInterface
{
    protected $loaders = array();

    /**
     * Adds a loader instance.
     *
     * @param LoaderInterface $loader A Loader instance
     */
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * Loads a Meta class from given datasource.
     *
     * @param string $type       object type
     * @param array  $parameters parameters needed to load required object type
     *
     * @return Meta|bool false if meta cannot be loaded, a Meta instance otherwise
     */
    public function load($type, $parameters)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->isSupported($type)) {
                if (false !== $meta = $loader->load($type, $parameters)) {
                    return $meta;
                }
            }
        }

        return false;
    }

    public function isSupported($type)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->isSupported($type)) {
                    return true;
            }
        }

        return false;
    }
}
