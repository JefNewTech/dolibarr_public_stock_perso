<?php

use artifaille\publicstock\model\Product;

// This page is public, so we disabled logges user control
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

// Setup PSR-4 class autoload
\spl_autoload_register(function ($className) {
    $classNameWithoutRoot = \str_replace('artifaille\\publicstock\\', '', $className);
    $filename = __DIR__ . DIRECTORY_SEPARATOR
        . '..' . DIRECTORY_SEPARATOR
        . 'class' . DIRECTORY_SEPARATOR
        . \str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithoutRoot) . '.php';
    require_once $filename;
});

$product = new Product();

echo 'Hello world';
