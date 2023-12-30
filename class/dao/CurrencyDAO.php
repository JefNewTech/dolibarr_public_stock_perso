<?php

declare(strict_types=1);

namespace artifaille\publicstock\dao;

/**
 * Read data about currencies
 */
class CurrencyDAO extends DAO
{
    /**
     * Read main currency from configuration for single currency setup
     *
     * @return string Currency code
     */
    public function readMainCurrency(): string
    {
        $query = <<<SQL
			SELECT value
			FROM {$this->tablePrefix}const
			WHERE name = 'MAIN_MONNAIE';
SQL;
		echo $query;
        $result = $this->doliDB->query($query);
        return $this->doliDB->fetch_row($result)[0] ?? '';
    }
}
