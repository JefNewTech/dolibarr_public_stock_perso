<?php

declare(strict_types=1);

namespace artifaille\publicstock\model;

/**
 * Represents a product to display
 */
class Product
{
    /**
      * Fake category ID used for uncategorized products
      */
    public const UNCATEGORIZED = -1;

    protected $reference;

    protected $label;

    protected $description;

    protected $price;

    protected $priceTTC;

    protected $stock;

    protected $imageShare;

    protected $categoryId;

    protected $nature;

    protected $emplacement;

    /**
     * Constructor
     *
     * @param int $entityId ID of product entity
     * @param string $ref Product reference
     * @param string $label Product short label
     * @param string $description Product long description
     * @param float $price Product selling price (taxes excluded)
     * @param float $priceTTC Product selling price (taxes included)
     * @param int $stock Number of products left in stock
     * @param string $imageShare Hash of public image share
     * @param int $categoryId ID of product category
     * @param string $nature Internal label of product nature
     */
    public function __construct(
        string $reference,
        string $label,
        string $description,
        float $price,
        float $priceTTC,
        int $stock,
        string $imageShare,
        int $categoryId,
        string $nature,
        string $emplacement
    ) {
        $this->reference = $reference;
        $this->label = $label;
        $this->description = $description;
        $this->price = $price;
        $this->priceTTC = $priceTTC;
        $this->stock = $stock;
        $this->imageShare = $imageShare;
        $this->categoryId = $categoryId;
        $this->nature = $nature;
        $this->emplacement = $emplacement;
    }

    /**
     * Get product reference
     *
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Get product short label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get product long description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get product selling price (taxes excluded)
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get product selling price (taxes included)
     *
     * @return float
     */
    public function getPriceTTC(): float
    {
        return $this->priceTTC;
    }

    /**
     * Get number of products left in stock.
     *
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * Get hash of image public share.
     *
     * @return string
     */
    public function getImageShare(): string
    {
        return $this->imageShare;
    }

    /**
    * Get ID of product category.
    *
    * @return int ID of product category
    */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * Get product nature
     *
     * @return string Internal label of product nature
     */
    public function getNature(): string
    {
        return $this->nature;
    }
    
    public function getEmplacement(): string
    {
	return $this->emplacement;
    }
}