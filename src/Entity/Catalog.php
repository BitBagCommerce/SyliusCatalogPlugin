<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class Catalog implements CatalogInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        /** @var ArrayCollection<array-key, CatalogRuleInterface> $this->ruleId */
        $this->ruleId = new ArrayCollection();
    }

    /** @var int|null */
    protected  $id;

    /** @var  \DateTime|null */
    protected $startDate;

    /** @var \DateTime|null */
    protected $endDate;

    /** @var CatalogRuleInterface|null */
    protected $ruleId;

    public function getRuleId(): ?CatalogRuleInterface
    {
        return $this->ruleId;
    }

    public function setRuleId(?CatalogRuleInterface $ruleId): void
    {
        $this->ruleId = $ruleId;
    }

    public function hasRuleId(): bool
    {
        return !$this->ruleId->isEmpty();
    }

    public function hasRule(CatalogRuleInterface $rule): bool
    {
        return $this->ruleId->contains($rule);
    }

    public function addRuleId(CatalogRuleInterface $rule): void
    {
        if (!$this->hasRule($rule)) {
            $rule->setCatalog($this);
            $this->ruleId->add($rule);
        }
    }

    public function removeRule(CatalogRuleInterface $rule): void
    {
        $rule->setCatalog(null);
        $this->ruleId->removeElement($rule);
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->getCatalogTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getCatalogTranslation()->setName($name);
    }

    /** @return CatalogTranslationInterface|TranslationInterface */
    protected function getCatalogTranslation(): TranslationInterface
    {
        return  $this->getTranslation();
    }

    protected function createTranslation(): CatalogTranslation
    {
        return new CatalogTranslation();
    }
}
