<?php

declare(strict_types=1);

use artifaille\publicstock\dao\CategoryDAO;
use artifaille\publicstock\model\Product;
use artifaille\publicstock\dao\ProductDAO;
use artifaille\publicstock\dao\CurrencyDAO;

// This page is public, so we disabled logged user control
if (!defined("NOLOGIN")) {
    define("NOLOGIN", '1');
}

/**
 * START of required section for loading Dolibarr environment, DO NOT MODIFY
 */
$res = 0;

// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
    $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/main.inc.php";
}

// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = $_SERVER['SCRIPT_FILENAME'] ?? '';
$tmp2 = \realpath(__FILE__);
$i = \mb_strlen($tmp) - 1;
$j = \mb_strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
    $i--;
    $j--;
}
if (!$res && $i > 0 && \file_exists(\substr($tmp, 0, ($i + 1)) . "/main.inc.php")) {
    $res = @include \substr($tmp, 0, ($i + 1)) . "/main.inc.php";
}
if (!$res && $i > 0 && \file_exists(\dirname(\substr($tmp, 0, ($i + 1))) . "/main.inc.php")) {
    $res = @include \dirname(\substr($tmp, 0, ($i + 1))) . "/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && \file_exists("../main.inc.php")) {
    $res = @include "../main.inc.php";
}
if (!$res && \file_exists("../../main.inc.php")) {
    $res = @include "../../main.inc.php";
}
if (!$res && \file_exists("../../../main.inc.php")) {
    $res = @include "../../../main.inc.php";
}
if (!$res) {
    die("Include of main fails");
}
/**
 * END of required section for loading Dolibarr environment, DO NOT MODIFY
 */

// Load necessary Dolibarr globals
global $conf, $db, $langs;
$langs->loadLangs(['products', 'publicstock@publicstock']);

// Make sure Products module is enabled
if (!\isModEnabled('product')) {
    \httponly_accessforbidden('Products module must be enabled to use this feature.');
}

// Setup PSR-4 class autoload
\spl_autoload_register(
    function ($className) {
        $classNameWithoutRoot = \str_replace('artifaille\\publicstock\\', '', $className);
        $filename = __DIR__ . DIRECTORY_SEPARATOR
        . '..' . DIRECTORY_SEPARATOR
        . 'class' . DIRECTORY_SEPARATOR
        . \str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithoutRoot) . '.php';
        include_once $filename;
    }
);


// Read data
$psProductDao = new ProductDAO($db);
$psCategoryDao = new CategoryDAO($db);
$psShowOutOfStock = (bool)($conf->global->PUBLICSTOCK_SHOW_OUT_OF_STOCK ?? false);
$psShowWithoutImage = (bool)($conf->global->PUBLICSTOCK_SHOW_WITHOUT_IMAGE ?? false);
$psCategories = $psCategoryDao->readProductCategories();
$psProducts = $psProductDao->readProducts($psShowOutOfStock, $psShowWithoutImage);
$psCurrencySymbol = $langs->getCurrencySymbol($conf->currency);
$psTheme = $conf->global->PUBLICSTOCK_THEME ?? '';
$psCss = $conf->global->PUBLICSTOCK_CSS ?? '';
$psShowImage = (bool)($conf->global->PUBLICSTOCK_SHOW_IMAGE ?? false);
$psShowDescription = (bool)($conf->global->PUBLICSTOCK_SHOW_DESCRIPTION ?? false);
$psShowStock = (bool)($conf->global->PUBLICSTOCK_SHOW_STOCK ?? false);
$psShowNature = (bool)($conf->global->PUBLICSTOCK_SHOW_NATURE ?? false);
$psShowUncategorized = (bool)($conf->global->PUBLICSTOCK_SHOW_UNCATEGORIZED ?? false);
$psPriceType = $conf->global->PUBLICSTOCK_SHOW_PRICE ?? '';

require_once __DIR__ . '/../tpl/index.html.php';
