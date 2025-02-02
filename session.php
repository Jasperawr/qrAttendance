<?php
session_start();

// Function to set session variables
function setSessionVariable($key)
{
  if (isset($_POST[$key])) {
    $_SESSION[$key] = $_POST[$key];
  }
}

setSessionVariable('class');
setSessionVariable('semester');
setSessionVariable('room');
setSessionVariable('program');
setSessionVariable('academic_year');
setSessionVariable('year_level');

// Prepare an associative array for session data to return as a JSON response
$response = [
  'class' => isset($_SESSION['class']) ? $_SESSION['class'] : '',
  'semester' => isset($_SESSION['semester']) ? $_SESSION['semester'] : '',
  'room' => isset($_SESSION['room']) ? $_SESSION['room'] : '',
  'program' => isset($_SESSION['program']) ? $_SESSION['program'] : '',
  'academic_year' => isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : '',
  'year_level' => isset($_SESSION['year_level']) ? $_SESSION['year_level'] : ''
];

// Return the session data as a JSON response
echo json_encode($response);
