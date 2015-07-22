<?php
namespace SWP\TemplateEngineBundle\Tests\Twig\Node;

use SWP\TemplateEngineBundle\Twig\Node\GimmeNode;

class Twig_Node_GimmeNodeTest extends \Twig_Test_NodeTestCase
{
    public function testConstructor()
    {
        $annotation = new \Twig_Node_Expression_Name('article', 1);
        $parameters = new \Twig_Node_Expression_Array([], 1);
        $body = new \Twig_Node_Text('', 1);
        $node = new GimmeNode($annotation, $parameters, $body, 1, 'gimme');
        $this->assertEquals($annotation, $node->getNode('annotation'));
        $this->assertEquals($parameters, $node->getNode('parameters'));
        $this->assertEquals($body, $node->getNode('body'));
    }

    public function getTests()
    {
        $annotation = new \Twig_Node_Expression_Name('article', 1);
        $parameters = new \Twig_Node_Expression_Array([], 1);
        $body = new \Twig_Node_Text('Test body', 1);
        $node = new GimmeNode($annotation, $parameters, $body, 1, 'gimme');

        return array(
            array($node, <<<EOF
// line 1
\$swpMetaLoader1 = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();
\$swpMeta1 = \$swpMetaLoader1->load((isset(\$context["article"]) ? \$context["article"] : null), array());
if (\$swpMeta1 !== false) {
    \$context[(isset(\$context["article"]) ? \$context["article"] : null)] = \$swpMeta1;    echo "Test body";
}
EOF
            ),
        );

        $annotation = new \Twig_Node_Expression_Name('article', 1);
        $body = new \Twig_Node_Text('Test body', 1);
        $node = new GimmeNode($annotation, null, $body, 1, 'gimme');

        return array(
            array($node, <<<EOF
// line 1
\$swpMetaLoader1 = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();
\$swpMeta1 = \$swpMetaLoader1->load((isset(\$context["article"]) ? \$context["article"] : null), null);
if (\$swpMeta1 !== false) {
    \$context[(isset(\$context["article"]) ? \$context["article"] : null)] = \$swpMeta1;    echo "Test body";
}
EOF
            ),
        );

        $annotation = new \Twig_Node_Expression_Name('article', 1);
        $parameters = new \Twig_Node_Expression_Array(['param1' => 'param2'], 1);
        $body = new \Twig_Node_Text('Test body', 1);
        $node = new GimmeNode($annotation, null, $body, 1, 'gimme');

        return array(
            array($node, <<<EOF
// line 1
\$swpMetaLoader1 = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();
\$swpMeta1 = \$swpMetaLoader1->load((isset(\$context["article"]) ? \$context["article"] : null), array('param1': 'param2'));
if (\$swpMeta1 !== false) {
    \$context[(isset(\$context["article"]) ? \$context["article"] : null)] = \$swpMeta1;    echo "Test body";
}
EOF
            ),
        );
    }
}
