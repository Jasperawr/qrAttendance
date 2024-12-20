<?php

header("Access-Control-Allow-Origin: *");

// Normalize request URI
$requestPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$basePath = '/';
$router = str_replace($basePath, '', $requestPath);
$router = trim(strtolower($router), '/');

// Define routes
$routes = [
    '' => 'index.php',
    'home' => 'index.php',
    'students' => 'views/students.php',
    'inventory' => 'views/inventory.php',
    'addstudent' => 'views/addstudent.php',
    'attendanceoverview' => 'views/attendanceoverview.php',
    'users' => 'views/admin/user.php',
    'inventoryadmin' => 'views/admin/inventory.php',
    'additem' => 'views/admin/additem.php',
];

// Match route or default to 404
if (array_key_exists($router, $routes)) {
    include $routes[$router];
} else {
    error_log("404 - Unmatched route: " . $router);
    include '404.php';
}
exit;
