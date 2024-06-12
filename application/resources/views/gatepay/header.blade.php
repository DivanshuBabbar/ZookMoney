<?php

// remove comment // from the below code to enable errors & warnings for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// require __DIR__ . '../../vendor/autoload.php';
// require __DIR__ . '../../vendor/autoload.php';
// if (!defined('PAY_PAGE_CONFIG')) {
//     define('PAY_PAGE_CONFIG', realpath('config.php'));
// }
require(base_path().'/resources/views/gatepay/vendor/autoload.php');
// @include('vendor/autoload.php');
if (!defined('PAY_PAGE_CONFIG')) 
{
    define('PAY_PAGE_CONFIG', base_path().'/resources/views/gatepay/config.php');
}