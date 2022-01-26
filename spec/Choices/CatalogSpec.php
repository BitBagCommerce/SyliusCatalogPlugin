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
use BitBag\SyliusCatalogPlugin\Choices\CatalogMapperInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

final class CatalogSpec extends ObjectBehavior
{
    private const FULL_TEMPLATE_PATH = 'spec/test';

    public function let(CatalogMapperInterface $catalogMapper, Finder $finder): void
    {
        $this->beConstructedWith(self::FULL_TEMPLATE_PATH, $catalogMapper, $finder);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Catalog::class);
    }

    public function it_implements_catalog_interface(): void
    {
        $this->shouldHaveType(CatalogInterface::class);
    }

    public function it_returns_default_template_if_directory_doesnt_exist(
        Finder $finder
    ): void {
        $finder->files()->willReturn($finder);
        $finder->in(Argument::type('string'))->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willThrow(DirectoryNotFoundException::class);

        $this->getTemplates()->shouldReturn(CatalogInterface::DEFAULT_TEMPLATE);
    }

    public function it_returns_default_template_if_directory_doesnt_contain_twig_files(
        Finder $finder
    ): void {
        if (!is_dir(self::FULL_TEMPLATE_PATH)) {
            mkdir(self::FULL_TEMPLATE_PATH, 0777, true);
        }
        $finder->files()->willReturn($finder);
        $finder->in(self::FULL_TEMPLATE_PATH)->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willReturn($finder);

        $finder->hasResults()->willReturn(false);

        $this->getTemplates()->shouldReturn(CatalogInterface::DEFAULT_TEMPLATE);

        $this->rrmdir(self::FULL_TEMPLATE_PATH);
    }

    private function rrmdir(string $directory): bool
    {
        array_map(fn (string $file) => is_dir($file) ? $this->rrmdir($file) : unlink($file), glob($directory . '/' . '*'));

        return rmdir($directory);
    }
}
