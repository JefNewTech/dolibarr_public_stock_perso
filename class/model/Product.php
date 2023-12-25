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
     * @var int Product database id
     * @var string Product description
     * @var float Product selling price (taxes included)
     */
    public function __construct(
        protected int $id,
        protected string $description,
        protected float $price
    ) {
    }
}
