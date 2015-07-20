<?php

namespace SWP\TemplateEngineBundle\Gimme\Loader;

class ArticleLoader implements LoaderInterface
{
    /**
     * Load meta object by provided type and parameters
     *
     * @param string $type       object type
     * @param array  $parameters parameters needed to load required object type
     *
     * @return Meta|bool false if meta cannot be loaded, a Meta instance otherwise
     */
    public function load($type, $parameter)
    {
        return 'Article 234';

        return false;
    }

    public function isSupported($type)
    {
        return $type === 'article' ? true : false;
    }
}