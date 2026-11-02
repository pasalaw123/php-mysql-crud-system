<?php
// Database connection settings for XAMPP.
$host = "localhost";
$username = "root";
$password = "";
$database = "phpcrud";

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8mb4");
