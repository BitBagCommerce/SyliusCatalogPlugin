<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Valid;

final class PriceConfigurationType extends AbstractType
{
    public const OPERATOR_ALL_GTE = 'all_gte';

    public const OPERATOR_ALL_GT = 'all_gt';

    public const OPERATOR_ALL_LT = 'all_lt';

    public const OPERATOR_ALL_LTE = 'all_lte';

    public const OPERATOR_ANY_GTE = 'any_gte';

    public const OPERATOR_ANY_GT = 'any_gt';

    public const OPERATOR_ANY_LT = 'any_lt';

    public const OPERATOR_ANY_LTE = 'any_lte';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_greater' => self::OPERATOR_ALL_GT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_greater_equal' => self::OPERATOR_ALL_GTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_less' => self::OPERATOR_ALL_LT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_less_equal' => self::OPERATOR_ALL_LTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_greater' => self::OPERATOR_ANY_GT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_greater_equal' => self::OPERATOR_ANY_GTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_less' => self::OPERATOR_ANY_LT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_less_equal' => self::OPERATOR_ANY_LTE,
                ],
            ])
            ->add('price', ChannelBasedRulePricing::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.add_catalog_configuration',
                'constraints' => [
                    new Valid(['groups' => ['sylius']]),
                    new Positive(['groups' => ['sylius']]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_catalog_plugin_catalog_rule_price_higher_than_configuration';
    }
}
