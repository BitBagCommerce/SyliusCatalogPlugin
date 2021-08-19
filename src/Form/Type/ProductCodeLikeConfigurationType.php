<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\ProductCodeLike;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class ProductCodeLikeConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('operator', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_code_exact' => ProductCodeLike::OPERATOR_EXACT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_code_prefix' => ProductCodeLike::OPERATOR_PREFIX,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_code_suffix' => ProductCodeLike::OPERATOR_SUFFIX,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_code_like' => ProductCodeLike::OPERATOR_LIKE,
                ],
            ])
            ->add('productCodePhrase', TextType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.product_code_prefix',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'string', 'groups' => ['sylius']]),
                ],
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_catalog_plugin_catalog_rule_sort_by_code_configuration';
    }
}
