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
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;
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

        /** @var ArrayCollection<array-key, CatalogRuleInterface> $this->rules */
        $this->rules = new ArrayCollection();
        $this->associatedProducts = new ArrayCollection();
    }

    /** @var int|null */
    protected  $id;

    /** @var  \DateTime|null */
    protected $startDate;

    /** @var \DateTime|null */
    protected $endDate;

    /** @var CatalogRuleInterface[]|Collection */
    protected $rules;

    /** @var string|null */
    protected $code;

    /** @var string|null */
    protected $connectingRules;

    /** @var ProductInterface[]|Collection */
    protected $associatedProducts;

    public function getConnectingRules(): ?string
    {
        return $this->connectingRules;
    }

    public function setConnectingRules(?string $connectingRules): void
    {
        $this->connectingRules = $connectingRules;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return !$this->rules->isEmpty();
    }

    public function hasRule(CatalogRuleInterface $rule): bool
    {
        return $this->rules->contains($rule);
    }

    public function addRule(CatalogRuleInterface $rule): void
    {
        if (!$this->hasRule($rule)) {
            $rule->setCatalog($this);
            $this->rules->add($rule);
        }
    }

    public function removeRule(CatalogRuleInterface $rule): void
    {
        $rule->setCatalog(null);
        $this->rules->removeElement($rule);
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


    public function getAssociatedProducts(): Collection
    {
        return $this->associatedProducts;
    }

    public function hasAssociatedProduct(ProductInterface $product): bool
    {
        return $this->associatedProducts->contains($product);
    }

    public function addAssociatedProduct(ProductInterface $product): void
    {
        if (!$this->hasAssociatedProduct($product)) {
            $this->associatedProducts->add($product);
        }
    }

    public function removeAssociatedProduct(ProductInterface $product): void
    {
        if ($this->hasAssociatedProduct($product)) {
            $this->associatedProducts->removeElement($product);
        }
    }
}
