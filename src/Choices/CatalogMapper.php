<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Choices;

final class CatalogMapper implements CatalogMapperInterface
{
    private string $catalogName;

    public function __construct(string $catalogName)
    {
        $this->catalogName = $catalogName;
    }

    public function map(iterable $files): array
    {
        $templates = [];
        foreach ($files as $file) {
            $templates[$file->getBasename('.html.twig')] = $this->catalogName . '/' . $file->getBasename();
        }
        if (in_array(array_key_first(CatalogInterface::DEFAULT_TEMPLATE), array_keys($templates), true)) {
            return array_merge(['default' => $templates['default']], $templates);
        }

        return array_merge(CatalogInterface::DEFAULT_TEMPLATE, $templates);
    }
}
