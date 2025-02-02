<?php

header("Access-Control-Allow-Origin: *");

// Start the session to access session variables
session_start();

// Normalize request URI
$requestPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$basePath = '/qrAttendance'; // Update base path to match your actual folder
$router = str_replace($basePath, '', $requestPath); // Remove the base path
$router = trim(strtolower($router), '/'); // Trim any leading or trailing slashes

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
    'profile' => 'views/profile.php',
    'changepassword' => 'views/changePassword.php',
    'printattendance' => 'views/printattendance.php',
    'generalreport' => 'views/admin/printreport.php',
];

// Check if the session role is set
if (!isset($_SESSION['role']) || !isset($_SESSION['faculty_id'])) {
    header('Location: /login.php'); // Redirect to login if not logged in
    exit;
}

// Get the role and faculty_id from session
$userRole = $_SESSION['role'];
$facultyId = $_SESSION['faculty_id'];

// Check access for restricted routes (admin-only pages)
$restrictedRoutes = [
    'users',
    'inventoryadmin',
    'additem',
    'generalreport',
];

// If the route is restricted and the user is not an admin, redirect them
if (in_array($router, $restrictedRoutes) && $userRole !== 'Admin') {
    header('Location: 404.php'); // Redirect to error or login page
    exit;
}

// Match route or default to 404
if (array_key_exists($router, $routes)) {
    include $routes[$router];
} else {
    error_log("404 - Unmatched route: " . $router);
    include '404.php';
}

exit;
