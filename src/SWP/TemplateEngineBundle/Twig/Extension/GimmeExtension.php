<?php

namespace SWP\TemplateEngineBundle\Twig\Extension;

use SWP\TemplateEngineBundle\Twig\TokenParser\GimmeTokenParser;

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
        );
    }

    public function getName()
    {
        return 'swp_gimme';
    }
}