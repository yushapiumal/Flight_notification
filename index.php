<?php
// Display all errors for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define __ROUTE with a default value of 'home' if 'route' parameter is not set
define('__ROUTE', $_GET['route'] ?? 'home');

// Initialize meta tags with default values
$meta_title = '';
$meta_description = '';
$meta_keywords = '';

// Meta information for specific routes
$meta = [
    'promotion' => [
        'title' => '',
        'desc' => ''
    ],

];


if (__ROUTE != '' && isset($meta[__ROUTE])) {
    $meta_title = $meta[__ROUTE]['title'];
    $meta_description = $meta[__ROUTE]['desc'];
    $meta_keywords = $meta[__ROUTE]['keywords'] ?? false;
}


$staticRoutes = [
    'home' => 'login.php',
    'today_flight' => 'flights.php',
    'cms_view' => 'CMS/' . __ROUTE . '.php',
    'cms_ctrl' => 'CMS/' . __ROUTE . '.php',
    'login' => 'CMS/cms_' . __ROUTE . '.php',
];


$matches = [];


try {
    switch (true) {
        case array_key_exists(__ROUTE, $staticRoutes):
            require_once($staticRoutes[__ROUTE]);
            break;

        case (preg_match('/^blog\/([a-zA-Z0-9\-_]*)/', __ROUTE, $matches)):
            require_once('');
            break;

        case (preg_match('/^contact\/promo\/([0-9]*)/', __ROUTE, $matches)):
            require_once('');
            break;

        default:
            throw new Exception('Page not found', 404);
            break;
    }
} catch (Exception $e) {
    if ($e->getCode() == 404) {
        header('HTTP/1.0 404 Not Found');
        require_once('404.php');
        exit;
    }
}
