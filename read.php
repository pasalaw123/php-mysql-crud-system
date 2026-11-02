<?php
require_once "dbconnection.php";
require_once "functions.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit("Invalid user ID.");
}

$stmt = mysqli_prepare($con, "SELECT * FROM tblusers WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    http_response_code(404);
    exit("User record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View User - PHP CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <h1>User Details</h1>
        <table>
            <tr><th>ID</th><td><?= e($user["ID"]) ?></td></tr>
            <tr><th>First Name</th><td><?= e($user["FirstName"]) ?></td></tr>
            <tr><th>Last Name</th><td><?= e($user["LastName"]) ?></td></tr>
            <tr><th>Mobile Number</th><td><?= e($user["MobileNumber"]) ?></td></tr>
            <tr><th>Email</th><td><?= e($user["Email"]) ?></td></tr>
            <tr><th>Address</th><td><?= nl2br(e($user["Address"])) ?></td></tr>
            <tr><th>Creation Date</th><td><?= e($user["CreationDate"]) ?></td></tr>
        </table>
        <div class="actions" style="margin-top:18px">
            <a class="btn secondary" href="index.php">Back to List</a>
            <a class="btn" href="edit.php?id=<?= e($user["ID"]) ?>">Edit User</a>
        </div>
    </div>
</div>
</body>
</html>
