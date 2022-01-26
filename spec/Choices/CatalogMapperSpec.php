<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusCatalogPlugin\Choices;

use BitBag\SyliusCatalogPlugin\Choices\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Choices\CatalogMapper;
use BitBag\SyliusCatalogPlugin\Choices\CatalogMapperInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Finder\SplFileInfo;

final class CatalogMapperSpec extends ObjectBehavior
{
    private const CATALOG_NAME = 'catalog';

    public function let(): void
    {
        $this->beConstructedWith(self::CATALOG_NAME);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CatalogMapper::class);
    }

    public function it_implements_catalog_mapper_interface(): void
    {
        $this->shouldHaveType(CatalogMapperInterface::class);
    }

    public function it_returns_array_of_templates_from_catalog_dir_with_default_one(
        SplFileInfo $file1,
        SplFileInfo $file2
    ): void {
        $files = new \ArrayIterator([
            $file1->getWrappedObject(),
            $file2->getWrappedObject(),
        ]);

        $file1->getBasename('.html.twig')->willReturn('one');
        $file1->getBasename()->willReturn('one.html.twig');

        $file2->getBasename('.html.twig')->willReturn('two');
        $file2->getBasename()->willReturn('two.html.twig');

        $templates = [
            'one' => self::CATALOG_NAME . '/one.html.twig',
            'two' => self::CATALOG_NAME . '/two.html.twig',
        ];

        $this->map($files)->shouldReturn(array_merge(CatalogInterface::DEFAULT_TEMPLATE, $templates));
    }

    public function it_returns_array_of_templates_from_catalog_dir_with_first_default(
        SplFileInfo $file1,
        SplFileInfo $file2,
        SplFileInfo $default
    ): void {
        $files = new \ArrayIterator([
            $file1->getWrappedObject(),
            $file2->getWrappedObject(),
            $default->getWrappedObject(),
        ]);

        $file1->getBasename('.html.twig')->willReturn('one');
        $file1->getBasename()->willReturn('one.html.twig');

        $file2->getBasename('.html.twig')->willReturn('two');
        $file2->getBasename()->willReturn('two.html.twig');

        $default->getBasename('.html.twig')->willReturn('default');
        $default->getBasename()->willReturn('default.html.twig');

        $templates = [
            'one' => self::CATALOG_NAME . '/one.html.twig',
            'two' => self::CATALOG_NAME . '/two.html.twig',
            'default' => self::CATALOG_NAME . '/default.html.twig',
        ];

        if (in_array(array_key_first(CatalogInterface::DEFAULT_TEMPLATE), array_keys($templates))) {
            $this->map($files)->shouldReturn(array_merge(['default' => $templates['default']], $templates));
        }
    }
}
