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
     * @var string Product short label
     * @var string Product long description
     * @var float Product selling price (taxes included)
     */
    public function __construct(
        protected string $label,
        protected string $description,
        protected float $price
    ) {
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
}
