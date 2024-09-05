<?php

namespace voku\twig;

use Twig\Compiler;
use Twig\Node\Node;

class MinifyHtmlNode extends Node
{
    public function __construct(array $nodes = [], array $attributes = [], int $lineno = 0, $tag = null)
    {
        parent::__construct($nodes, $attributes, $lineno, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $bodyNode = $this->getNode('body');

        // Get the raw content directly from the TextNode attribute
        $rawContent = $bodyNode->getAttribute('data');

        // Manually handle the output buffering and compression
        $compiler
            ->addDebugInfo($this)

            // Start output buffering manually
            ->write("ob_start();\n")

            // Output the raw content directly to the buffer
            ->write("echo '$rawContent';\n")

            // Capture the output from the buffer
            ->write("\$compiledOutput = ob_get_clean();\n")

            // Retrieve the MinifyHtmlExtension and apply compression to the captured output
            ->write("\$extension = \$this->env->getExtension('\\voku\\twig\\MinifyHtmlExtension');\n")
            ->write("echo \$extension->compress(\$this->env, \$compiledOutput);\n");
    }
}
