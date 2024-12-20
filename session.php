<?php
//
  session_start();

  if(isset($_POST['section'])) {
    // Sanitize and set session variable
    $selectedOption = $_POST['section'];
    $_SESSION['section'] = $selectedOption;

  }

  if(isset($_POST['groupnumber'])) {
    // Sanitize and set session variable
    $selectedOption = $_POST['groupnumber'];
    $_SESSION['groupnumber'] = $selectedOption;

  }

  // Check if session variable exists
  if (isset($_SESSION['section'])) {
    echo $_SESSION['section'];
  } else {
    echo '';
  }

  // Check if session variable exists
  if (isset($_SESSION['groupnumber'])) {
    echo $_SESSION['groupnumber'];
  } else {
    echo '';
  }
?>