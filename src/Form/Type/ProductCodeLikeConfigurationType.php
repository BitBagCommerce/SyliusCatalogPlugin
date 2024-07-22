<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
