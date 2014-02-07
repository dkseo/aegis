<?php
/**
 * Error Reporting
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
//error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * 최상위 경로로 변경
*/
chdir(dirname(__DIR__));

/**
 * default setting
*/
require "init_autoloader.php";

/**
 * Run
*/

//$func->debug( get_defined_vars() );
//$func->debug( get_defined_functions() );
//$func->debug( get_defined_constants() );
