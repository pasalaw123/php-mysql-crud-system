<?php
require_once "dbconnection.php";
require_once "functions.php";

$result = mysqli_query($con, "SELECT ID, FirstName, LastName, MobileNumber, Email, Address, CreationDate
                              FROM tblusers ORDER BY ID DESC");
$message = $_GET["message"] ?? "";
$messages = [
    "created" => "User record added successfully.",
    "updated" => "User record updated successfully.",
    "deleted" => "User record deleted successfully."
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP CRUD Application</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <h1>PHP CRUD User Management</h1>
        <p class="subtitle">Create, view, update and delete user records.</p>

        <?php if (isset($messages[$message])): ?>
            <div class="notice success-message"><?= e($messages[$message]) ?></div>
        <?php endif; ?>

        <div class="actions" style="margin-bottom:18px">
            <a class="btn success" href="insert.php">+ Add New User</a>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Mobile</th><th>Email</th><th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= e($row["ID"]) ?></td>
                            <td><?= e($row["FirstName"] . " " . $row["LastName"]) ?></td>
                            <td><?= e($row["MobileNumber"]) ?></td>
                            <td><?= e($row["Email"]) ?></td>
                            <td>
                                <div class="actions">
                                    <a class="btn" href="read.php?id=<?= e($row["ID"]) ?>">View</a>
                                    <a class="btn secondary" href="edit.php?id=<?= e($row["ID"]) ?>">Edit</a>
                                    <form class="inline-form" method="POST" action="delete.php"
                                          onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="id" value="<?= e($row["ID"]) ?>">
                                        <button class="btn danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty">No user records found. Select “Add New User” to create the first record.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
