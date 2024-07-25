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
use Symfony\Component\Validator\Constraints\Valid;

final class FirstVariantPriceConfigurationType extends AbstractType
{
    public const OPERATOR_GTE = 'all_gte';

    public const OPERATOR_GT = 'all_gt';

    public const OPERATOR_LT = 'all_lt';

    public const OPERATOR_LTE = 'all_lte';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('operator', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_greater' => self::OPERATOR_GT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_greater_equal' => self::OPERATOR_GTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_less' => self::OPERATOR_LT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_less_equal' => self::OPERATOR_LTE,
                ],
            ])
            ->add('price', ChannelBasedRulePricing::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.add_catalog_configuration',
                'constraints' => [
                    new Valid(['groups' => ['sylius']]),
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
