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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class PriceHigherThanConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('priceHigher', MoneyType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.form.catalog.add_catalog_configuration',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'numeric', 'groups' => ['sylius']]),
                ]
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_catalog_plugin_catalog_rule_price_higher_than_configuration';
    }
}
