<?php

declare(strict_types=1);

namespace BohnMedia\ContaoFlagIconsBundle\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: ContaoCoreEvents::GENERATE_SYMLINKS)]
final class GenerateSymlinksListener
{
    public function __invoke(GenerateSymlinksEvent $event): void
    {
        $event->addSymlink('vendor/lipis/flag-icons/flags', 'assets/flag-icons');
    }
}
