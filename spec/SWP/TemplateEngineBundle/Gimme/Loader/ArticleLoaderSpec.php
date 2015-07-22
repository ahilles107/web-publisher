<?php

namespace spec\SWP\TemplateEngineBundle\Gimme\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticleLoaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\TemplateEngineBundle\Gimme\Loader\ArticleLoader');
    }

    function it_should_load_meta()
    {
        $this->load('article', array())->shouldBeAnInstanceOf('SWP\TemplateEngineBundle\Gimme\Meta\Meta');
    }

    function it_should_check_if_type_is_supported()
    {
        $this->isSupported('article')->shouldReturn(true);
        $this->isSupported('article2')->shouldReturn(false);
    }
}
