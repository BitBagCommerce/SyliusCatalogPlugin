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

use BitBag\SyliusCatalogPlugin\Checker\Rule\PriceRule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class PriceConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_greater' => PriceRule::OPERATOR_ALL_GT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_greater_equal' => PriceRule::OPERATOR_ALL_GTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_less' => PriceRule::OPERATOR_ALL_LT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_all_less_equal' => PriceRule::OPERATOR_ALL_LTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_greater' => PriceRule::OPERATOR_ANY_GT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_greater_equal' => PriceRule::OPERATOR_ANY_GTE,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_less' => PriceRule::OPERATOR_ANY_LT,
                    'bitbag_sylius_catalog_plugin.ui.form.catalog.product_price_any_less_equal' => PriceRule::OPERATOR_ANY_LTE,
                ],
            ])
            ->add('price', ChannelBasedRulePricing::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.add_catalog_configuration',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'numeric', 'groups' => ['sylius']]),
                ],
                    'currency' => $options['currency'],
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
