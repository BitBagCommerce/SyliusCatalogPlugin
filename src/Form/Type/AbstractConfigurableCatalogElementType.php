<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Form\Type;

use BitBag\SyliusCatalogPlugin\Entity\ConfigurableCatalogElementInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfigurableCatalogElementType extends AbstractResourceType
{
    private FormTypeRegistryInterface $formTypeRegistry;

    public function __construct(
        FormTypeRegistryInterface $formTypeRegistry,
        string $dataClass,
        array $validationGroups = []
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->formTypeRegistry = $formTypeRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $type = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $configuration = $this->formTypeRegistry->get($type, 'default');

                if (null === $configuration) {
                    return;
                }

                $this->addConfigurationFields($event->getForm(), $configuration);
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $type = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $event->getForm()->get('type')->setData($type);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();
                if (!isset($data['type'])) {
                    return;
                }
                $configuration = $this->formTypeRegistry->get($data['type'], 'default');

                if (null === $configuration) {
                    return;
                }
                $this->addConfigurationFields($event->getForm(), $configuration);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('configuration_type', null)
            ->setAllowedTypes('configuration_type', ['string', 'null'])
        ;
    }

    protected function addConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form
            ->add('configuration', $configurationType, [
                'label' => false,
            ])
            ->add('isNegation', CheckboxType::class, [
                'label' => 'bitbag_sylius_catalog_plugin.ui.is_negation'
            ])
        ;
    }

    /* @phpstan-ignore-next-line Parameter $data is mixed type */
    protected function getRegistryIdentifier(FormInterface $form, $data = null): ?string
    {
        if ($data instanceof ConfigurableCatalogElementInterface && null !== $data->getType()) {
            return $data->getType();
        }

        return $form->getConfig()->getOption('configuration_type');
    }
}
