<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Doctrine\ORM\QueryBuilder;

interface RuleInterface
{
    public const OR = 'Or';

    public const AND = 'And';

    public function modifyQueryBuilder(
        array $configuration,
        QueryBuilder $queryBuilder,
        string $connectingRules,
    ): void;
}
