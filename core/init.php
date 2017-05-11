<?php
@ob_end_clean();
@session_start();
@ob_start();

//ROOT DIRECTORY PATH
$path1 = explode('core',__DIR__);
$path =  $path1[0];


/**
 * Defining Root directory
 */
define("ROOT_DIR",$path);

/**
 * MySQL Database Server Address
 */
define("DB_SERVER","localhost");

/**
 * MySQL Database Username
 */
define("DB_USERNAME","root");

/**
 * MySQL Database Password
 */
define("DB_PASSWORD","");

/**
 * DATABASE NAME of the System
 */
define("DB_NAME","kc596");

//ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Setting timezone for India :) 
 */
date_default_timezone_set('Asia/Kolkata');

?>
