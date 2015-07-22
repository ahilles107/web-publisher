<?php

namespace spec\SWP\TemplateEngineBundle\Gimme\Meta;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetaSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(__DIR__ . '/article.yml', '{
            "title": "New article",
            "keywords": "lorem, ipsum, dolor, sit, ame",
            "dont_expose_it": "this should be not exposed"
        }');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\TemplateEngineBundle\Gimme\Meta\Meta');
    }

    function it_should_show_title_when_printed()
    {
        $this->__toString()->shouldReturn('New article');
    }
}
