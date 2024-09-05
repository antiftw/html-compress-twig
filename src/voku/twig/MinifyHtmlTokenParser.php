<?php

declare(strict_types=1);

namespace voku\twig;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class MinifyHtmlTokenParser extends AbstractTokenParser
{

    public function decideHtmlCompressEnd(Token $token): bool
    {
        return $token->test('endhtmlcompress');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    public function getTag(): string
    {
        return 'htmlcompress';
    }

    public function parse(Token $token): MinifyHtmlNode
    {
        $lineNumber = $token->getLine();
        $stream = $this->parser->getStream();
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideHtmlCompressEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);
        $nodes = ['body' => $body];

        return new MinifyHtmlNode($nodes, [], $lineNumber, $this->getTag());
    }
}
