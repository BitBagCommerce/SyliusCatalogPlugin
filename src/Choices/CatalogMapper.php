<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
