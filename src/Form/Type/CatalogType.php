<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use BitBag\SyliusCatalogPlugin\Form\Type\Translation\CatalogTranslationType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.start_date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.end_date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.catalog',
                'entry_type' => CatalogTranslationType::class,
            ])
            ->add('rules', CatalogRuleCollectionType::class, [
                'label' => 'bitbag_sylius_Catalog_plugin.form.catalog.rules',
                'button_add_label' => 'bitbag_sylius_catalog_plugin.form.catalog.add_rule',
            ]);
    }
}
