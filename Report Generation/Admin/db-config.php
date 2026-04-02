<?php
// db_config.php

// Attempt to establish a database connection
$conn = mysqli_connect(
  "localhost",
  "root",
  "",
  "jobportal"
);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
