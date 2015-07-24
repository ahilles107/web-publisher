<?php

namespace SWP\TemplateEngineBundle\Twig\TokenParser;

use SWP\TemplateEngineBundle\Twig\Node\GimmeListNode;

/**
 * Parser for gimme/endgimme blocks.
 */
class GimmeListTokenParser extends \Twig_TokenParser
{
    /**
     * @param \Twig_Token $token
     *
     * @return boolean
     */
    public function decideGimmeListEnd(\Twig_Token $token)
    {
        return $token->test('endgimmelist');
    }

    public function decideGimmeListFork(\Twig_Token $token)
    {
        return $token->test(array('else', 'endgimmelist'));
    }

    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return 'gimmelist';
    }

    /**
     * {@inheritDoc}
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $variable = $this->parser->getExpressionParser()->parseAssignmentExpression();
        $from = $this->parser->getExpressionParser()->parseExpression();
        $collectionType = $this->parser->getExpressionParser()->parseAssignmentExpression();

        $collectionFilters = null;
        if ($stream->test(\Twig_Token::PUNCTUATION_TYPE, '|')) {
            $collectionFilters = $this->parser->getExpressionParser()->parsePostfixExpression($collectionType);
        }
        
        $parameters = null;
        if ($stream->nextIf(\Twig_Token::NAME_TYPE, 'with')) {
            $parameters = $this->parser->getExpressionParser()->parseExpression();
        }

        $ifExpression = null;
        if ($stream->nextIf(\Twig_Token::NAME_TYPE, 'if')) {
            $ifExpression = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideGimmeListFork'));
        if ($stream->next()->getValue() == 'else') {
            $stream->expect(\Twig_Token::BLOCK_END_TYPE);
            $else = $this->parser->subparse(array($this, 'decideGimmeListEnd'), true);
        } else {
            $else = null;
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new GimmeListNode($variable, $collectionType, $collectionFilters, $parameters, $ifExpression, $else, $body, $lineno, $this->getTag());
    }
}
