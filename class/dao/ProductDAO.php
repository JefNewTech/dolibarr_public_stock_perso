<?php

declare(strict_types=1);

namespace artifaille\publicstock\dao;

use artifaille\publicstock\model\Product;

/**
 * Reads products in database and outputs PHP objects
 */
class ProductDAO extends DAO
{
    /**
     * Fetch products that are available to sell (tosell = 1, stock > 0)
     *
     * @param  bool $includeOutOfStock Include products that are currently out of stock
     * @return Product[] Products found
     */
    public function readProducts(bool $includeOutOfStock): array
    {
        $products = [];
        $stockFilter = $includeOutOfStock ? '' : 'HAVING stock > 0';
        $query = <<<SQL
            SELECT p.rowid, p.ref AS productRef, p.description, p.label, p.price_ttc, SUM(ps.reel) AS stock,
            file.entity, file.filename, cp.fk_categorie AS categoryId
            FROM {$this->tablePrefix}product AS p
            LEFT JOIN {$this->tablePrefix}product_stock AS ps
			ON ps.fk_product = p.rowid
        	LEFT JOIN {$this->tablePrefix}ecm_files AS file
			ON src_object_type = 'product'
			AND src_object_id = p.rowid
			AND (
				file.filename LIKE "%.gif"
				OR file.filename LIKE "%.jpg"
				OR file.filename LIKE "%.jpeg"
				OR file.filename LIKE "%.png"
				OR file.filename LIKE "%.bmp"
				OR file.filename LIKE "%.webp"
				OR file.filename LIKE "%.xpm"
				OR file.filename LIKE "%.xbm"
			)
			LEFT JOIN {$this->tablePrefix}categorie_product AS cp
			ON cp.fk_product = p.rowid
			WHERE tosell = 1
			GROUP BY p.rowid
			{$stockFilter};
SQL;
        $result = $this->doliDB->query($query);
        $row = $this->doliDB->fetch_array($result);
        while (\is_array($row)) {
            $rowid = (int)$row['rowid'];
            $categoryId = (int)($row['categoryId'] ?? -1);
            $products[$categoryId][$rowid] = new Product(
                (int)$row['entity'],
                $row['productRef'],
                $row['label'],
                $row['description'] ?? '',
                (float)($row['price_ttc'] ?? 0.0),
                (int)($row['stock'] ?? 0),
                $row['filename'] ?? '',
				(int)($rox['categoryId'] ?? Product::UNCATEGORIZED)
            );
            $row = $this->doliDB->fetch_array($result);
        }
        return $products;
    }
}
