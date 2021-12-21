<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Choices;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

final class Catalog implements CatalogInterface
{
    private string $projectDir;

    private string $templatesDir;

    private const DEFAULT_TEMPLATE = ['default' => '@BitBagSyliusCatalogPlugin/Catalog/Templates/default.html.twig'];

    public function __construct(string $projectDir, string $templatesDir)
    {
        $this->projectDir = $projectDir;
        $this->templatesDir = $templatesDir;
    }

    public function getTemplates(): array
    {
        $finder = new Finder();

        try {
            $finder->files()->in($this->projectDir.'/templates/'.$this->templatesDir)->name('*.html.twig')->depth(0);
        } catch (DirectoryNotFoundException $directoryNotFoundException) {
            return self::DEFAULT_TEMPLATE;
        }

        if (!$finder->hasResults()) {
            return self::DEFAULT_TEMPLATE;
        }

        $templates = [];

        foreach ($finder->getIterator() as $file) {
            $templates[$file->getBasename('.html.twig')] = $this->templatesDir.'/'.$file->getBasename();
        }
        if (in_array(array_key_first(self::DEFAULT_TEMPLATE), array_keys($templates))) {
            return $templates;
        }

        return array_merge(self::DEFAULT_TEMPLATE, $templates);
    }
}
