<?php

declare(strict_types=1);

namespace BohnMedia\ContaoFlagIconsBundle\InsertTag;

use BohnMedia\ContaoFlagIconsBundle\FlagIconRenderer;
use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;

#[AsInsertTag('flag_icon')]
readonly class FlagIconInsertTag
{
    public function __construct(
        private FlagIconRenderer $renderer,
    ) {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $params = $insertTag->getParameters();
        $code = $params->get(0);

        if (null === $code) {
            return new InsertTagResult('', OutputType::html);
        }

        $ratio = $params->get('ratio');
        $width = $params->getScalar('width');
        $alt = $params->get('alt');

        $html = $this->renderer->render(
            (string) $code,
            null !== $ratio ? (string) $ratio : null,
            \is_int($width) && $width > 0 ? $width : null,
            null !== $alt ? (string) $alt : null,
        );

        return new InsertTagResult($html, OutputType::html);
    }
}
