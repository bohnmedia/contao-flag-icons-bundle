<?php

declare(strict_types=1);

namespace BohnMedia\ContaoFlagIconsBundle\ContaoManager;

use BohnMedia\ContaoFlagIconsBundle\BohnMediaContaoFlagIconsBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(BohnMediaContaoFlagIconsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
