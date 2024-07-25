<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class AttributeConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attribute', AttributeAutocompleteChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.attribute',
                'multiple' => false,
            ])
            ->add('value', TextType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.attribute_value',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'string', 'groups' => ['sylius']]),
                ],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_catalog_plugin_catalog_rule_taxon_configuration';
    }
}
