<?php

declare(strict_types=1);

namespace artifaille\publicstock\dao;

use artifaille\publicstock\model\Product;

class ProductDAO
{
    /**
     * @var \DoliDb Dolibarr database object
     */
    protected $doliDB;

    /**
     * @var string Prefix on table names in database
     */
    protected $tablePrefix;

    public function __construct(\DoliDb $doliDB)
    {
        $this->doliDB = $doliDB;
        $this->tablePrefix = \defined('MAIN_DB_PREFIX') ? \constant('MAIN_DB_PREFIX') : '';
    }

    /**
     * Fetch products that are available to sell (tosell = 1, stock > 0)
     *
     * @return Product[] Products found
     */
    public function readProducts(): array
    {
        $products = [];
        $query = <<<SQL
            SELECT p.rowid, p.ref AS productRef, p.description, p.label, p.price_ttc, SUM(ps.reel) AS stock,
            file.entity, file.filepath, file.ref, file.fullpath_orig, file.filename
            FROM {$this->tablePrefix}product AS p
            LEFT JOIN {$this->tablePrefix}product_stock AS ps
			ON ps.fk_product = p.rowid
            LEFT JOIN {$this->tablePrefix}ecm_files AS file
			ON src_object_type = 'product'
			AND src_object_id = p.rowid
			WHERE tosell = 1
			GROUP BY p.rowid
			HAVING stock > 0;
SQL;
        $result = $this->doliDB->query($query);
        $row = $this->doliDB->fetch_array($result);
        while (\is_array($row)) {
            $rowid = (int)$row['rowid'];
            $products[$rowid] = new Product($rowid, $row['description'], (float)$row['price_ttc']);
            $row = $this->doliDB->fetch_array($result);
        }
        return $products;
    }
}
