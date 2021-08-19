<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\AttributeType\DateAttributeType;
use Sylius\Component\Attribute\AttributeType\DatetimeAttributeType;
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\AttributeType\TextareaAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Attribute\Model\AttributeValueInterface;

interface AttributeRuleInterface
{
    public const ATTRIBUTE_STORAGE_FIELD_MAP = [
        SelectAttributeType::TYPE => AttributeValueInterface::STORAGE_JSON,
        CheckboxAttributeType::TYPE => AttributeValueInterface::STORAGE_BOOLEAN,
        IntegerAttributeType::TYPE => AttributeValueInterface::STORAGE_INTEGER,
        TextAttributeType::TYPE => AttributeValueInterface::STORAGE_TEXT,
        TextareaAttributeType::TYPE => AttributeValueInterface::STORAGE_TEXT,
        DatetimeAttributeType::TYPE => AttributeValueInterface::STORAGE_DATETIME,
        DateAttributeType::TYPE => AttributeValueInterface::STORAGE_DATE,
    ];

    public const SELECT_ATTRIBUTE_PATTERN = '%%"%s"%%';

    public const PRODUCT_ATTRIBUTES_ALIAS = 'productAttributes';
}
