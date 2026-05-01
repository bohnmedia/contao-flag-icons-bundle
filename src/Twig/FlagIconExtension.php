<?php

declare(strict_types=1);

namespace BohnMedia\ContaoFlagIconsBundle\Twig;

use BohnMedia\ContaoFlagIconsBundle\FlagIconRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FlagIconExtension extends AbstractExtension
{
    public function __construct(private readonly FlagIconRenderer $renderer)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('flag_icon', $this->renderer->render(...), ['is_safe' => ['html']]),
        ];
    }
}
