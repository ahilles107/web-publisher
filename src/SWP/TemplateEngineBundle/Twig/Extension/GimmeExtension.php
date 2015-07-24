<?php

namespace SWP\TemplateEngineBundle\Twig\Extension;

use SWP\TemplateEngineBundle\Twig\TokenParser\GimmeTokenParser;
use SWP\TemplateEngineBundle\Twig\TokenParser\GimmeListTokenParser;

class GimmeExtension extends \Twig_Extension
{
    private $loader;

    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    public function getLoader()
    {
        return $this->loader;
    }

    public function getTokenParsers()
    {
        return array(
            new GimmeTokenParser(),
            new GimmeListTokenParser(),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('start', function($context, $node, $value) {
                $node['_collection_type_filters']['start'] = $value;

                return $node;
            }, array('needs_context' => true)),
            new \Twig_SimpleFilter('limit', function($context, $node, $value) {
                $node['_collection_type_filters']['limit'] = $value;

                return $node;
            }, array('needs_context' => true)),
            new \Twig_SimpleFilter('order', function($context, $node, $value1, $value2) {
                $node['_collection_type_filters']['order'] = [$value1, $value2];

                return $node;
            }, array('needs_context' => true)),
        );
    }

    public function getName()
    {
        return 'swp_gimme';
    }
}
