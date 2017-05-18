<?php
ini_set('display_errors', '1');
error_reporting(E_ERROR);

//DB
define('SYSPATH','bv5');
require("../system/config/database.php");
$DB_HOST = $config['default']['hostname'];
$DB_NAME = $config['default']['basename'];
$DB_USER = $config['default']['username'];
$DB_PASSWORD = $config['default']['password'];

//Admin
$ALANG = 'ru';
$PROJECT_NAME = "Oh.UA";
$ADMIN_SESSION_AUTH = 1;

//Tables
$TABLE_DOCS_RUBS="docs_rubs";
$TABLE_DOCS="docs";
$TABLE_NEWS_RUBS="news_rubs";
$TABLE_NEWS="news";

$TABLE_USERS_RUBS="utypes";
$TABLE_USERS="users";
$TABLE_MAIL="emails";


$TABLE_ADMINS_GROUPS="admins_groups";
$TABLE_ADMINS="admins";
$TABLE_ADMINS_MENU="admins_menu";
$TABLE_ADMINS_MENU_ASSOC="admins_menu_assoc";
$TABLE_ADMINS_LOG="admins_log";

?>
