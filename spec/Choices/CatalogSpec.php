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
use Symfony\Component\Finder\SplFileInfo;
use Tests\BitBag\SyliusCatalogPlugin\Application\Kernel;

final class CatalogSpec extends ObjectBehavior
{
    private string $testDir = 'spec/test';
    private string $templatesDir = 'catalog';

    private const DEFAULT_TEMPLATE = ['default' => '@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig'];

    function let(): void
    {
        $this->beConstructedWith($this->testDir, $this->templatesDir);
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
        $dir = $this->testDir.'/templates/'.$this->templatesDir;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $finder->files()->willReturn($finder);
        $finder->in($dir)->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willReturn($finder);

        $finder->hasResults()->willReturn(false);

        $this->getTemplates()->shouldReturn(self::DEFAULT_TEMPLATE);

        $this->rrmdir($this->testDir);
    }

    function it_return_templates_from_default_dir(
        Finder $finder,
        SplFileInfo $file1,
        SplFileInfo $file2
    ): void
    {
        $dir = $this->testDir.'/templates/'.$this->templatesDir;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $finder->files()->willReturn($finder);
        $finder->in($dir)->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willReturn($finder);
        $finder->hasResults()->willReturn(true);


        $finder->getIterator()->willReturn(new \ArrayIterator([
            $file1->getWrappedObject(),
            $file2->getWrappedObject(),
        ]));

        $file1->getBasename('.html.twig')->willReturn('one');
        $file1->getBasename()->willReturn('one.html.twig');
        file_put_contents($dir.'/one.html.twig', '');

        $file2->getBasename('.html.twig')->willReturn('two');
        $file2->getBasename()->willReturn('two.html.twig');
        file_put_contents($dir.'/two.html.twig', '');

        $templates = [
            'two' => $this->templatesDir.'/two.html.twig',
            'one' => $this->templatesDir.'/one.html.twig',
        ];

        $this->getTemplates()->shouldReturn(array_merge(self::DEFAULT_TEMPLATE, $templates));

        $this->rrmdir($this->testDir);
    }
    function it_return_templates_from_default_dir_with_first_default_if_default_exist(
        Finder $finder,
        SplFileInfo $file1,
        SplFileInfo $file2,
        SplFileInfo $default
    ): void
    {
        $dir = $this->testDir.'/templates/'.$this->templatesDir;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $finder->files()->willReturn($finder);
        $finder->in($dir)->willReturn($finder);
        $finder->name('*.html.twig')->willReturn($finder);
        $finder->depth(0)->willReturn($finder);
        $finder->hasResults()->willReturn(true);


        $finder->getIterator()->willReturn(new \ArrayIterator([
            $file1->getWrappedObject(),
            $file2->getWrappedObject(),
            $default->getWrappedObject(),
        ]));

        $file1->getBasename('.html.twig')->willReturn('one');
        $file1->getBasename()->willReturn('one.html.twig');
        file_put_contents($dir.'/one.html.twig', '');

        $file2->getBasename('.html.twig')->willReturn('two');
        $file2->getBasename()->willReturn('two.html.twig');
        file_put_contents($dir.'/two.html.twig', '');

        $default->getBasename('.html.twig')->willReturn('default');
        $default->getBasename()->willReturn('default.html.twig');
        file_put_contents($dir.'/default.html.twig', '');

        $templates = [
            'two' => $this->templatesDir.'/two.html.twig',
            'one' => $this->templatesDir.'/one.html.twig',
            'default' => $this->templatesDir.'/default.html.twig',
        ];

        if (in_array(array_key_first(self::DEFAULT_TEMPLATE), array_keys($templates))) {
            $this->getTemplates()->shouldReturn(array_merge(['default' => $templates['default']], $templates));
        }

        $this->rrmdir($this->testDir);
    }


    private function rrmdir(string $directory): bool
    {
        array_map(fn (string $file) => is_dir($file) ? $this->rrmdir($file) : unlink($file), glob($directory . '/' . '*'));

        return rmdir($directory);
    }
}
