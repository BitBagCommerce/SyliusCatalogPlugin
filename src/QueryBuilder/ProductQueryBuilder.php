<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\QueryBuilder;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch\RuleInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\DisMax;
use Sylius\Component\Registry\ServiceRegistry;

final class ProductQueryBuilder implements ProductQueryBuilderInterface
{
    /** @var ServiceRegistry */
    private $serviceRegistry;

    public function __construct(ServiceRegistry $serviceRegistry)
    {
        $this->serviceRegistry = $serviceRegistry;
    }

    public function findMatchingProductsQuery(CatalogInterface $catalog)
    {
        $connectingRules = $catalog->getConnectingRules();

        $rules = $catalog->getRules();
        $subQueries = $this->getQueries($rules->toArray());

        if (empty($subQueries)) {
            return new BoolQuery();
        }

        switch ($connectingRules) {
            case RuleInterface:: AND:
                $query = new BoolQuery();
                foreach ($subQueries as $subQuery) {
                    $query->addFilter($subQuery);
                }

                break;
            case RuleInterface:: OR:
                $query = new DisMax();
                foreach ($subQueries as $subQuery) {
                    $query->addQuery($subQuery);
                }

                break;
            default:
                throw new \InvalidArgumentException('Invalid connecting rule');
        }

        return $query;
    }

    private function getQueries(array $rules): array
    {
        $queries = [];
        foreach ($rules as $rule) {
            $type = $rule->getType();

            /** @var RuleInterface $ruleChecker */
            $ruleChecker = $this->serviceRegistry->get($type);

            $ruleConfiguration = $rule->getConfiguration();

            $queries[] = $ruleChecker->modifyQueryBuilder($ruleConfiguration);
        }

        return $queries;
    }
}
