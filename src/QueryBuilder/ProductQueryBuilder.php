<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\QueryBuilder;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch\RuleInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRuleInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Doctrine\Common\Collections\Collection;
use Elastica\Query\BoolQuery;
use Sylius\Component\Registry\ServiceRegistry;

final class ProductQueryBuilder implements ProductQueryBuilderInterface
{
    private ServiceRegistry $serviceRegistry;

    private QueryBuilderInterface $hasChannelQueryBuilder;

    public function __construct(ServiceRegistry $serviceRegistry, QueryBuilderInterface $hasChannelQueryBuilder)
    {
        $this->serviceRegistry = $serviceRegistry;
        $this->hasChannelQueryBuilder = $hasChannelQueryBuilder;
    }

    /**
     * @param string $connectingRules
     * @param Collection<int, CatalogRuleInterface> $rules
     * @return BoolQuery
     */
    public function findMatchingProductsQuery(string $connectingRules, Collection $rules): BoolQuery
    {
        $subQueries = $this->getQueries($rules->toArray());

        if (0 === count($subQueries)) {
            return new BoolQuery();
        }
        $query = new BoolQuery();
        $query->addFilter($this->hasChannelQueryBuilder->buildQuery([]));

        switch ($connectingRules) {
            case RuleInterface::AND:
                foreach ($subQueries as $subQuery) {
                    $query->addFilter($subQuery);
                }

                break;
            case RuleInterface::OR:
                foreach ($subQueries as $subQuery) {
                    $query->addShould($subQuery);
                    $query->setMinimumShouldMatch(1);
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

            $queries[] = $ruleChecker->createSubquery($ruleConfiguration);
        }

        return $queries;
    }
}
