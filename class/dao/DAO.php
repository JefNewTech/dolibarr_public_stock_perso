<?php

declare(strict_types=1);

namespace artifaille\publicstock\dao;

/**
 * Defines common properties for all DAO classes
 */
abstract class DAO
{
    /**
     * @var \DoliDb Dolibarr object for handling database queries
     */
    protected $doliDB;

    /**
     * @var string Prefix on table names in database
     */
    protected $tablePrefix;

    /**
     * Constructor
     *
     * @param \DoliDb $doliDB Dolibarr object for handling database queries
     */
    public function __construct(\DoliDb $doliDB)
    {
        $this->doliDB = $doliDB;
        $this->tablePrefix = \defined('MAIN_DB_PREFIX') ? \constant('MAIN_DB_PREFIX') : '';
    }

}
