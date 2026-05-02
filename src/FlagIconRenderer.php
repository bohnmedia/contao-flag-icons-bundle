<?php

declare(strict_types=1);

namespace BohnMedia\ContaoFlagIconsBundle;

use Contao\CoreBundle\Intl\Countries;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class FlagIconRenderer
{
    private const DEFAULT_RATIO = '4x3';

    private const RATIO_DIMENSIONS = [
        '4x3' => ['width' => 40, 'height' => 30],
        '1x1' => ['width' => 40, 'height' => 40],
    ];

    private const URI_ENCODE_MAP = [
        '%' => '%25',
        '#' => '%23',
        '<' => '%3C',
        '>' => '%3E',
        '"' => '%22',
        "'" => '%27',
        '&' => '%26',
    ];

    public function __construct(
        private Countries $countries,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    public function render(string $code, ?string $ratio = null, ?int $width = null, ?string $alt = null): string
    {
        if ('' === $code) {
            return '';
        }

        $code = strtolower($code);

        $ratio ??= self::DEFAULT_RATIO;
        if (!\array_key_exists($ratio, self::RATIO_DIMENSIONS)) {
            $ratio = self::DEFAULT_RATIO;
        }

        $svgPath = $this->projectDir.'/vendor/lipis/flag-icons/flags/'.$ratio.'/'.$code.'.svg';
        if (!is_file($svgPath)) {
            return '';
        }

        $svg = file_get_contents($svgPath);
        if (false === $svg) {
            return '';
        }

        if (null === $alt) {
            $alt = $this->countries->getCountries()[strtoupper($code)] ?? '';
        }

        [$resolvedWidth, $resolvedHeight] = $this->resolveDimensions($ratio, $width);

        $dataUri = 'data:image/svg+xml;utf8,'.strtr($svg, self::URI_ENCODE_MAP);

        $attributes = [
            'src' => $dataUri,
            'alt' => $alt,
            'width' => (string) $resolvedWidth,
            'height' => (string) $resolvedHeight,
            'class' => sprintf('flag-icon flag-icon--%s flag-icon--%s', $code, $ratio),
        ];

        $html = '<img';
        foreach ($attributes as $name => $value) {
            $html .= sprintf(' %s="%s"', $name, htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }
        $html .= '>';

        return $html;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function resolveDimensions(string $ratio, ?int $width): array
    {
        $defaults = self::RATIO_DIMENSIONS[$ratio];

        if (null === $width || $width <= 0) {
            return [$defaults['width'], $defaults['height']];
        }

        $height = (int) round($width * $defaults['height'] / $defaults['width']);

        return [$width, $height];
    }
}
