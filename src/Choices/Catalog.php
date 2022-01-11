<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Choices;

use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

final class Catalog implements CatalogInterface
{
    private string $fullTemplatePath;

    private CatalogMapperInterface $catalogMapper;

    public function __construct(string $fullTemplatePath, CatalogMapperInterface $catalogMapper)
    {
        $this->fullTemplatePath = $fullTemplatePath;
        $this->catalogMapper = $catalogMapper;
    }

    public function getTemplates(): array
    {
        $finder = new Finder();

        try {
            $finder
                ->files()
                ->in($this->fullTemplatePath)
                ->name('*.html.twig')
                ->depth(0)
            ;
        } catch (DirectoryNotFoundException $directoryNotFoundException) {
            return self::DEFAULT_TEMPLATE;
        }

        if (!$finder->hasResults()) {
            return self::DEFAULT_TEMPLATE;
        }

        return $this->catalogMapper->map($finder->getIterator());
    }
}
