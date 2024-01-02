<?php

declare(strict_types=1);

namespace artifaille\publicstock\model;

/**
 * Represents a product to display
 */
class Product
{
    /**
     * Constructor
     *
     * @param int $entityId ID of product entity
     * @param string $ref Product reference
     * @param string $label Product short label
     * @param string $description Product long description
     * @param float $price Product selling price (taxes included)
     * @param int $stock Number of products left in stock
     * @param string $imageName Name of image file
     */
    public function __construct(
        protected int $entityId,
        protected string $reference,
        protected string $label,
        protected string $description,
        protected float $price,
        protected int $stock,
        protected string $imageName
    ) {
    }

    /**
 	 * Get ID of product entity
 	 *
 	 * @return int
 	 */
    public function getEntityId(): int
    {
        return $this->entityId;
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
     * Get product selling price (taxes included)
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
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
     * Get name of image file.
     *
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }
}
