<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class ProductAssociationRuleType extends AbstractConfigurableCatalogElementType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', ProductAssociationRuleChoiceType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.rule_type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_catalog_plugin_product_association_rule';
    }
}
