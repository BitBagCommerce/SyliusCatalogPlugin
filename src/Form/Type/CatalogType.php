<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\RuleInterface;
use BitBag\SyliusCatalogPlugin\Choices\Catalog;
use BitBag\SyliusCatalogPlugin\Form\Type\Translation\CatalogTranslationType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class CatalogType extends AbstractResourceType
{
    private array $attributeChoices;

    public function __construct(string $dataClass, array $attributeChoices, array $validationGroups = [])
    {
        parent::__construct($dataClass, $validationGroups);

        $this->attributeChoices = $attributeChoices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber(TextType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.code',
            ]))
            ->add('startDate', DateTimeType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.start_date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.end_date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.template',
                'choices' => Catalog::getTemplates(),
            ])
            ->add('sortingType', ChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.sorting_type',
                'choices' => array_flip($this->attributeChoices),
            ])
            ->add('displayProducts', ChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.number_of_products',
                'choices' => array_combine(
                    range(1, 12, 1),
                    range(1, 12, 1)
                ),
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.catalog',
                'entry_type' => CatalogTranslationType::class,
            ])
            ->add('rules', CatalogRuleCollectionType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.rules',
                'button_add_label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.add_rule',
            ])
            ->add('connectingRules', CatalogRuleChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.rule_connecting_rules',
                'choices' => [
                    'And' => RuleInterface::AND,
                    'Or' => RuleInterface::OR,
                ],
            ])
            ->add('productAssociationRules', ProductAssociationRuleCollectionType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.product_rules',
                'button_add_label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.add_rule',
            ])
            ->add('productAssociationConnectingRules', ProductAssociationRuleChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.rule_connecting_rules',
                'choices' => [
                    'And' => 'And',
                    'Or' => 'Or',
                ],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_catalog_plugin_catalog';
    }
}
