<?php

namespace Havit\TwigComponents\Extension;

use Havit\TwigComponents\Configuration;
use Havit\TwigComponents\TokenParser\ComponentTokenParser;
use Havit\TwigComponents\TokenParser\SlotTokenParser;

class ComponentExtension extends \Twig\Extension\AbstractExtension
{
    /** @var \Twig\Environment */
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getTokenParsers()
    {
        return [
            new ComponentTokenParser($this->configuration),
            new SlotTokenParser(),
        ];
    }
}
