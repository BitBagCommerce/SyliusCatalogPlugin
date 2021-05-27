<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\QueryBuilder;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch\RuleInterface;
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

    public function findMatchingProductsQuery(string $connectingRules, Collection $rules)
    {
        $subQueries = $this->getQueries($rules->toArray());

        if (empty($subQueries)) {
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
