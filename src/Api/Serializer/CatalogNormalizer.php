<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Api\Serializer;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Webmozart\Assert\Assert;

final class CatalogNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'catalog_normalizer_already_called';

    /** @var ProductsInsideCatalogResolverInterface */
    private $productResolver;

    public function __construct(
        ProductsInsideCatalogResolverInterface $productResolver
    ) {
        $this->productResolver = $productResolver;
    }

    public function normalize(
        $object,
        $format = null,
        array $context = []
    )
    {
        Assert::isInstanceOf($object, CatalogInterface::class);
        Assert::keyNotExists($context, self::ALREADY_CALLED);

        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        $products = $this->productResolver->findMatchingProducts($object);
        $products = $this->normalizer->normalize($products, $format, $context);

        $data['products'] = $products;

        return $data;
    }

    public function supportsNormalization(
        $data,
        $format = null,
        $context = []
    ): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof CatalogInterface && $this->isNotAdminGetOperation($context);
    }

    private function isNotAdminGetOperation(array $context): bool
    {
        return !isset($context['item_operation_name']) || !('admin_get' === $context['item_operation_name']);
    }
}
