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
