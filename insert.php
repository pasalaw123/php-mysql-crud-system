<?php
require_once "dbconnection.php";
require_once "functions.php";

$errors = [];
$data = ["fname"=>"", "lname"=>"", "contactno"=>"", "email"=>"", "address"=>""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $data = clean_user_input();
    $errors = validate_user($data);

    if (!$errors) {
        $stmt = mysqli_prepare($con,
            "INSERT INTO tblusers (FirstName, LastName, MobileNumber, Email, Address)
             VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss",
            $data["fname"], $data["lname"], $data["contactno"],
            $data["email"], $data["address"]);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?message=created");
            exit;
        }
        $errors[] = "Could not save the record. Please try again.";
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User - PHP CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <h1>Add New User</h1>
        <p class="subtitle">Enter the user's details below.</p>

        <?php if ($errors): ?>
            <div class="notice error"><ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul></div>
        <?php endif; ?>

        <form method="POST" action="insert.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="field">
                <label for="fname">First Name</label>
                <input id="fname" name="fname" maxlength="200" required value="<?= e($data["fname"]) ?>">
            </div>
            <div class="field">
                <label for="lname">Last Name</label>
                <input id="lname" name="lname" maxlength="200" required value="<?= e($data["lname"]) ?>">
            </div>
            <div class="field">
                <label for="contactno">Mobile Number</label>
                <input id="contactno" name="contactno" type="tel" inputmode="numeric"
                       pattern="[0-9]{7,15}" maxlength="15" required value="<?= e($data["contactno"]) ?>">
            </div>
            <div class="field">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" maxlength="200" required value="<?= e($data["email"]) ?>">
            </div>
            <div class="field">
                <label for="address">Address</label>
                <textarea id="address" name="address" required><?= e($data["address"]) ?></textarea>
            </div>
            <div class="actions">
                <button class="btn success" type="submit">Save User</button>
                <a class="btn secondary" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
