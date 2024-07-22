<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
        ProductsInsideCatalogResolverInterface $productResolver,
    ) {
        $this->productResolver = $productResolver;
    }

    public function normalize(
        $object,
        ?string $format = null,
        array $context = [],
    ): array {
        Assert::isInstanceOf($object, CatalogInterface::class);
        Assert::keyNotExists($context, self::ALREADY_CALLED);

        $context[self::ALREADY_CALLED] = true;

        /** @var array $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        $products = $this->productResolver->findMatchingProducts($object);
        $products = $this->normalizer->normalize($products, $format, $context);

        $data['products'] = $products;

        return $data;
    }

    public function supportsNormalization(
        $data,
        ?string $format = null,
        array $context = [],
    ): bool {
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
