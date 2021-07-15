<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\AbstractRule;
use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\AttributeRuleInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasAttributesQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class AttributeRuleSpec extends ObjectBehavior
{
    private int $i = 0;

    function let(
        LocaleContextInterface $localeContext
    ): void {
        $this->beConstructedWith($localeContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AbstractRule::class);
    }

    function it_implements_attribute_rule_interface(): void
    {
        $this->shouldHaveType(AttributeRuleInterface::class);
    }

    function it_returns_product_with_filtered_by_attributes(
        LocaleContextInterface $localeContext,
        QueryBuilder $queryBuilder,
        AttributeInterface $attribute
    ): void {
        $key = 'H@$H_K3V_J!W%P_L(S=O';
        $value = 'Attr value 99%!';

        $attribute->getCode()->willReturn('attrCode');
        $attribute->getConfiguration()->willReturn(['choices' => [
            $key => [
                'en_us' => $value
            ]
        ]]);
        $attribute->getType()->willReturn('select');
        $attribute->getStorageType()->willReturn('json');

        $configuration['attribute'] = $attribute->getWrappedObject();
        $configuration['value'] = $value;

        $localeContext->getLocaleCode()->willReturn('mc');
        $queryBuilder->expr()->willReturn(new Expr())->shouldBeCalled();
        $queryBuilder->leftJoin('p.attributes', 'productAttributes')->shouldBeCalled();

        $queryBuilder
            ->setParameter(
                'attributeValue' . $this->i++,
                '%"' . $key . '"%'
            )->shouldBeCalled();

        $queryBuilder
            ->setParameter(
                'attributeLocaleCode' . $this->i++,
                'mc'
            )->shouldBeCalled();

        $queryBuilder->andWhere(Argument::any())->shouldBeCalled();

        $this->modifyQueryBuilder($configuration, $queryBuilder->getWrappedObject(), 'And');
    }
}
