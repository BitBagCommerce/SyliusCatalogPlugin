<?php
/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */
declare(strict_types=1);

namespace spec\BitBag\SyliusCatalogPlugin\Choices;

use BitBag\SyliusCatalogPlugin\Choices\Catalog;
use BitBag\SyliusCatalogPlugin\Choices\CatalogInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

final class CatalogSpec extends ObjectBehavior
{
    private string $projectDir = '/test/';
    private string $templatesDir = 'catalog';

    private const DEFAULT_TEMPLATE = ['default' => '@BitBagSyliusCatalogPlugin/Catalog/Templates/default.html.twig'];

    function let(): void
    {
        $this->beConstructedWith($this->projectDir, $this->templatesDir);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Catalog::class);
    }

    function it_implements_catalog_interface(): void
    {
        $this->shouldHaveType(CatalogInterface::class);
    }

    function it_return_default_template_if_directory_doesnt_exist(
        Finder $finder
    ): void
    {
        $finder->files()->willReturn($finder);
        $finder->in(Argument::type('string'))->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willThrow(DirectoryNotFoundException::class);

        $this->getTemplates()->shouldReturn(self::DEFAULT_TEMPLATE);
    }

    function it_return_default_template_if_directory_doesnt_contain_twig_files(
        Finder $finder
    ): void
    {
        $finder->files()->willReturn($finder);
        $finder->in($this->projectDir.'/templates/bundles/BitBagSyliusCatalogPlugin/Catalog/Templates')->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willReturn($finder);

        $finder->hasResults()->willReturn(false);

        $this->getTemplates()->shouldReturn(self::DEFAULT_TEMPLATE);
    }


}
