<?php

/**
 * This is a sample config file, and shows how you can use it
 * Copy this file to `config.php` when done
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 */
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';

$siteurl = $protocol . $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')) . '/';
$sitepath = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/')) . '/';

/**
 * Location settings
 */
define('HTTP_SERVER', $siteurl);
define('HTTP_CSS', HTTP_SERVER . 'public/css/');
define('HTTP_IMG', HTTP_SERVER . 'public/images/');
define('HTTP_JS', HTTP_SERVER . 'public/js/');

define('DIR_SERVER', $sitepath);
define('DIR_SYSTEM', DIR_SERVER . 'system/');
define('DIR_CATALOG', DIR_SERVER . 'catalog/');
define('DIR_UPLOAD', DIR_SERVER . 'uploads/');
define('DIR_LIBRARY', DIR_SERVER . 'library/');
define('DIR_LANGUAGE', DIR_CATALOG . 'language/');

/**
 * Language settings
 */
define('DEFAULT_LANG_ID', 1);
define('DEFAULT_LANG_SHORT', 'nl');

/**
 * Database settings
 */
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', 'toor');
define('DB_DBNAME', 'mvc');
define('DB_PREFIX', 'mvc__');
