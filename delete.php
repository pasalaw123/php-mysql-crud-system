<?php
require_once "dbconnection.php";
require_once "functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

verify_csrf();
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Invalid user ID.");
}

$stmt = mysqli_prepare($con, "DELETE FROM tblusers WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$deleted = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

if ($deleted > 0) {
    header("Location: index.php?message=deleted");
} else {
    header("Location: index.php");
}
exit;
