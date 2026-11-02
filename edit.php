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

$data = [
    "fname" => $user["FirstName"],
    "lname" => $user["LastName"],
    "contactno" => $user["MobileNumber"],
    "email" => $user["Email"],
    "address" => $user["Address"]
];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $data = clean_user_input();
    $errors = validate_user($data);

    if (!$errors) {
        $stmt = mysqli_prepare($con,
            "UPDATE tblusers SET FirstName=?, LastName=?, MobileNumber=?, Email=?, Address=?
             WHERE ID=?");
        mysqli_stmt_bind_param($stmt, "sssssi",
            $data["fname"], $data["lname"], $data["contactno"],
            $data["email"], $data["address"], $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php?message=updated");
            exit;
        }
        $errors[] = "Could not update the record. Please try again.";
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User - PHP CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <h1>Update User Details</h1>
        <p class="subtitle">Change the details and save your updates.</p>

        <?php if ($errors): ?>
            <div class="notice error"><ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul></div>
        <?php endif; ?>

        <form method="POST" action="edit.php?id=<?= e($id) ?>">
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
                <button class="btn success" type="submit">Update User</button>
                <a class="btn secondary" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
