<?php

namespace SWP\TemplateEngineBundle\Gimme\Loader;

/**
 * LoaderInterface is the interface all loaders must implement.
 */
interface LoaderInterface
{
    /**
     * Loads a Meta class from given datasource.
     *
     * @param string $type       object type
     * @param array  $parameters parameters needed to load required object type
     *
     * @return Meta|bool false if meta cannot be loaded, a Meta instance otherwise
     */
    public function load($type, $parameters);

    /**
     * Check if loader support required type
     * 
     * @param string  $type required type
     * 
     * @return bool false if loader don't support this type, true otherwise
     */
    public function isSupported($type);
}
