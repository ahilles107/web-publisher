<?php

namespace SWP\TemplateEngineBundle\Twig\TokenParser;

use SWP\TemplateEngineBundle\Twig\Node\GimmeNode;

/**
 * Parser for gimme/endgimme blocks.
 */
class GimmeTokenParser extends \Twig_TokenParser
{
    /**
     * @param \Twig_Token $token
     *
     * @return boolean
     */
    public function decideCacheEnd(\Twig_Token $token)
    {
        return $token->test('endgimme');
    }

    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return 'gimme';
    }

    /**
     * {@inheritDoc}
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $annotation = $this->parser->getExpressionParser()->parseExpression();
        $parameters = null;
        if ($stream->test(\Twig_Token::PUNCTUATION_TYPE, '{')) {
            $parameters = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideCacheEnd'), true);
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new GimmeNode($annotation, $parameters, $body, $lineno, $this->getTag());
    }
}