<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Choices;

use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

final class Catalog implements CatalogInterface
{
    private string $fullTemplatePath;

    private CatalogMapperInterface $catalogMapper;

    private Finder $finder;

    public function __construct(
        string $fullTemplatePath,
        CatalogMapperInterface $catalogMapper,
        Finder $finder,
    ) {
        $this->fullTemplatePath = $fullTemplatePath;
        $this->catalogMapper = $catalogMapper;
        $this->finder = $finder;
    }

    public function getTemplates(): array
    {
        try {
            $this->finder
                ->files()
                ->in($this->fullTemplatePath)
                ->name('*.html.twig')
                ->depth(0)
            ;
        } catch (DirectoryNotFoundException $directoryNotFoundException) {
            return self::DEFAULT_TEMPLATE;
        }

        if (!$this->finder->hasResults()) {
            return self::DEFAULT_TEMPLATE;
        }

        return $this->catalogMapper->map($this->finder->getIterator());
    }
}
