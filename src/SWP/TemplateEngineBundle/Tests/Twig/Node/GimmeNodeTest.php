<?php
namespace SWP\TemplateEngineBundle\Tests\Twig\Node;

use SWP\TemplateEngineBundle\Twig\Node\GimmeNode;

class GimmeNodeTest extends \Twig_Test_NodeTestCase
{
    public function testConstructor()
    {
        $annotation = new \Twig_Node(array(new \Twig_Node_Expression_AssignName('article', 1)));
        $parameters = new \Twig_Node_Expression_Array([], 1);
        $body = new \Twig_Node_Text('', 1);
        $node = new GimmeNode($annotation, $parameters, $body, 1, 'gimme');
        $this->assertEquals($annotation, $node->getNode('annotation'));
        $this->assertEquals($parameters, $node->getNode('parameters'));
        $this->assertEquals($body, $node->getNode('body'));
    }

    public function getTests()
    {
        $annotation1 = new \Twig_Node(array(new \Twig_Node_Expression_AssignName('article', 1)));
        $parameters1 = new \Twig_Node_Expression_Array(array(), 1);
        $body1 = new \Twig_Node_Text('Test body', 1);
        $node1 = new GimmeNode($annotation1, $parameters1, $body1, 1, 'gimme');

        $annotation2 = new \Twig_Node(array(new \Twig_Node_Expression_AssignName('article', 2)));
        $body2 = new \Twig_Node_Text('Test body', 2);
        $node2 = new GimmeNode($annotation2, null, $body2, 2, 'gimme');

        $annotation3 = new \Twig_Node(array(new \Twig_Node_Expression_AssignName('article', 3)));
        $parameters3 = new \Twig_Node_Expression_Array(array(new \Twig_Node_Expression_Constant('foo', 1), new \Twig_Node_Expression_Constant(true, 1)), 1);
        $body3 = new \Twig_Node_Text('Test body', 3);
        $node3 = new GimmeNode($annotation3, $parameters3, $body3, 3, 'gimme');

        return array(
            array($node1, <<<EOF
// line 1
\$swpMetaLoader1 = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();
\$context["article"] = \$swpMetaLoader1->load("article", array());
if (\$context["article"] !== false) {
    echo "Test body";
}
unset(\$context["article"]);
EOF
            ),
            array($node2, <<<EOF
// line 2
\$swpMetaLoader2 = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();
\$context["article"] = \$swpMetaLoader2->load("article", null);
if (\$context["article"] !== false) {
    echo "Test body";
}
unset(\$context["article"]);
EOF
            ),
            array($node3, <<<EOF
// line 3
\$swpMetaLoader3 = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();
\$context["article"] = \$swpMetaLoader3->load("article", array("foo" => true));
if (\$context["article"] !== false) {
    echo "Test body";
}
unset(\$context["article"]);
EOF
            ),
        );
    }
}
