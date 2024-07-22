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

    public function buildForm(FormBuilderInterface $builder, array $options): void
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
