<?php

namespace SWP\TemplateEngineBundle\Gimme\Loader;

use SWP\TemplateEngineBundle\Gimme\Meta\Meta;

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
    public function load($type, $parameters)
    {
        return new Meta(__APP_DIR__ . '/Resources/meta/article.yml', array(
            'title' => 'New article',
            'keywords' => 'lorem, ipsum, dolor, sit, amet'
        ));
    }

    public function isSupported($type)
    {
        return $type === 'article' ? true : false;
    }
}