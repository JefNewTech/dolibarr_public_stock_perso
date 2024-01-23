<?php

declare(strict_types=1);

namespace artifaille\publicstock\dao;

/**
 * Reads categories in database.
 */
class CategoryDAO extends DAO
{
    /**
     * Read categories that have at least one product in them.
     *
     * @return string[] Categories labels
     */
    public function readProductCategories(): array
    {
        $categories = [];
        $query = <<<SQL
		    SELECT c.rowid, c.label
		    FROM {$this->tablePrefix}categorie AS c
			INNER JOIN {$this->tablePrefix}categorie_product AS cp
			ON cp.fk_categorie = c.rowid;
SQL;
        $result = $this->doliDB->query($query);
        $row = $this->doliDB->fetch_array($result);
        while (\is_array($row)) {
            $categories[(int)$row['rowid']] = $row['label'];
            $row = $this->doliDB->fetch_array($result);
        }
        return $categories;
    }
}
