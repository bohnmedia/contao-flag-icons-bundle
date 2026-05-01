<?php

declare(strict_types=1);

namespace BohnMedia\ContaoFlagIconsBundle\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\Intl\Countries;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsInsertTag('flag_icon')]
readonly class FlagIconInsertTag
{
    private const DEFAULT_RATIO = '4x3';

    private const RATIO_DIMENSIONS = [
        '4x3' => ['width' => 40, 'height' => 30],
        '1x1' => ['width' => 40, 'height' => 40],
    ];

    public function __construct(
        private Countries $countries,
        private Packages $packages,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $rawCode = $insertTag->getParameters()->get(0);

        if (null === $rawCode || '' === $rawCode) {
            return new InsertTagResult('', OutputType::html);
        }

        $code = strtolower((string) $rawCode);

        $ratio = (string) ($insertTag->getParameters()->get('ratio') ?? self::DEFAULT_RATIO);
        if (!\array_key_exists($ratio, self::RATIO_DIMENSIONS)) {
            $ratio = self::DEFAULT_RATIO;
        }

        $svgPath = $this->projectDir . '/vendor/lipis/flag-icons/flags/' . $ratio . '/' . $code . '.svg';
        if (!is_file($svgPath)) {
            return new InsertTagResult('', OutputType::html);
        }

        $altParam = $insertTag->getParameters()->get('alt');
        if (null !== $altParam) {
            $alt = (string) $altParam;
        } else {
            $alt = $this->countries->getCountries()[strtoupper($code)] ?? '';
        }

        [$width, $height] = $this->resolveDimensions(
            $ratio,
            $insertTag->getParameters()->getScalar('width'),
        );

        $url = $this->packages->getUrl('assets/flag-icons/' . $ratio . '/' . $code . '.svg');

        $attributes = [
            'src' => $url,
            'alt' => $alt,
            'width' => (string) $width,
            'height' => (string) $height,
            'class' => sprintf('flag-icon flag-icon--%s flag-icon--%s', $code, $ratio),
        ];

        $html = '<img';
        foreach ($attributes as $name => $value) {
            $html .= sprintf(' %s="%s"', $name, htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }
        $html .= '>';

        return new InsertTagResult($html, OutputType::html);
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function resolveDimensions(string $ratio, float|int|string|null $width): array
    {
        $defaults = self::RATIO_DIMENSIONS[$ratio];

        if (!\is_int($width) || $width <= 0) {
            return [$defaults['width'], $defaults['height']];
        }

        $height = (int) round($width * $defaults['height'] / $defaults['width']);

        return [$width, $height];
    }
}
