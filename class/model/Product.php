<?php

namespace artifaille\publicstock\model;

/**
 * Represents a product to display
 */
class Product
{
    /**
     * @var int Product database id
     */
    protected int $id;


	/**
	 * @var string Product description
	 */
	protected string $description;


	/**
	 * @var float Product selling price (taxes included)
	 */
	protected float $price;
}
