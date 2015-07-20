<?php

namespace SWP\TemplateEngineBundle\Twig\Node;

/**
 * Gimme twig node.
 */
class GimmeNode extends \Twig_Node
{
    private static $cacheCount = 1;

    /**
     * @param \Twig_Node_Expression $annotation
     * @param \Twig_Node_Expression $parameters
     * @param \Twig_NodeInterface   $body
     * @param integer               $lineno
     * @param string                $tag
     */
    public function __construct(\Twig_Node_Expression $annotation, \Twig_Node_Expression $parameters, \Twig_NodeInterface $body, $lineno, $tag = null)
    {
        parent::__construct(array('parameters' => $parameters, 'body' => $body, 'annotation' => $annotation), array(), $lineno, $tag);
    }

    /**
     * {@inheritDoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $i = self::$cacheCount++;

        $compiler
            ->addDebugInfo($this)
            ->write("\$swpMetaLoader".$i." = \$this->getEnvironment()->getExtension('swp_gimme')->getLoader();\n")
            ->write("\$swpMeta".$i." = \$swpMetaLoader".$i."->load(")->subcompile($this->getNode('annotation'))->raw(", ")->subcompile($this->getNode('parameters'))->write(");\n")
            ->write("if (\$swpMeta".$i." !== false) {\n")
            ->indent()
                ->write("\$context[")->subcompile($this->getNode('annotation'))->write("] = \$swpMeta".$i.";")
                ->subcompile($this->getNode('body'))
                ->write("\n")
            ->outdent()
            ->write("}\n")
        ;
    }
}