<?php

declare(strict_types=1);


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
    $res = @include($_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/main.inc.php");
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
    $res = @include(\substr($tmp, 0, ($i + 1)) . "/main.inc.php");
}
if (!$res && $i > 0 && \file_exists(\dirname(\substr($tmp, 0, ($i + 1))) . "/main.inc.php")) {
    $res = @include(\dirname(\substr($tmp, 0, ($i + 1))) . "/main.inc.php");
}
// Try main.inc.php using relative path
if (!$res && \file_exists("../main.inc.php")) {
    $res = @include("../main.inc.php");
}
if (!$res && \file_exists("../../main.inc.php")) {
    $res = @include("../../main.inc.php");
}
if (!$res && \file_exists("../../../main.inc.php")) {
    $res = @include("../../../main.inc.php");
}
if (!$res) {
    die("Include of main fails");
}
/**
 * END of required section for loading Dolibarr environment, DO NOT MODIFY
 */

// Load necessary Dolibarr globals
global $conf, $db, $langs;

?>
<!doctype html>
<head>
<link rel="stylesheet" href="../css/publicstock.css">
<title><?= $conf->global->PUBLICSTOCK_PAGE_TITLE ?></title>
</head>
<body>
	<h1><?= $conf->global->PUBLICSTOCK_PAGE_TITLE ?></h1>

<?php
// Make sure Products module is enabled
if (!\isModEnabled('product')) {
    \httponly_accessforbidden('Products module must be enabled to use this feature.');
}

// Setup PSR-4 class autoload
\spl_autoload_register(function ($className) {
    $classNameWithoutRoot = \str_replace('artifaille\\publicstock\\', '', $className);
    $filename = __DIR__ . DIRECTORY_SEPARATOR
        . '..' . DIRECTORY_SEPARATOR
        . 'class' . DIRECTORY_SEPARATOR
        . \str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithoutRoot) . '.php';
    require_once $filename;
});


// Read data
$dao = new ProductDAO($db);
$products = $dao->readProducts();
$currencySymbol = $langs->getCurrencySymbol($conf->currency);

// Display
$content = '';
if (empty($products)) {
    $content .= $langs->translate('No product');
} else {
    foreach ($products as $product) {
		if ($product->getImageName() === '') {
			$imageBlock = '';
		} else {
            $imageURL = (\defined('DOL_MAIN_URL_ROOT') ? (DOL_MAIN_URL_ROOT . '/') : '')
				. 'document.php?modulepart=product&entity='
            	. $product->getEntityId()
            	. '&attachment=0&file='
            	. $product->getReference() . '/'
            	. $product->getImageName();
			$imageBlock = <<<HTML
				<img class="product_image" alt="product image" src="{$imageURL}">
HTML;
		}
        $content .= <<<HTML
			<div class="product">
				<h3 class="product_label">{$product->getLabel()}</h3>
				<p class="product_price_stock">
					{$product->getPrice()} {$currencySymbol} - {$langs->trans('Stock')} : {$product->getStock()}
				<p>
				{$imageBlock}
				<p class="product_desc">
					{$product->getDescription()}
				</p>
        	</div>
HTML;
    }
}
echo $content;
?>

</body>
</html>
