<?php
// Start a session so forms can use a CSRF token.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function csrf_token() {
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function verify_csrf() {
    $submitted = $_POST["csrf_token"] ?? "";
    if (!hash_equals($_SESSION["csrf_token"] ?? "", $submitted)) {
        http_response_code(403);
        exit("Invalid form token. Please go back, refresh the page and try again.");
    }
}

function clean_user_input() {
    return [
        "fname" => trim($_POST["fname"] ?? ""),
        "lname" => trim($_POST["lname"] ?? ""),
        "contactno" => trim($_POST["contactno"] ?? ""),
        "email" => trim($_POST["email"] ?? ""),
        "address" => trim($_POST["address"] ?? "")
    ];
}

function validate_user($data) {
    $errors = [];

    if ($data["fname"] === "") $errors[] = "First name is required.";
    if ($data["lname"] === "") $errors[] = "Last name is required.";
    if (!preg_match('/^[0-9]{7,15}$/', $data["contactno"])) {
        $errors[] = "Mobile number must contain 7 to 15 digits only.";
    }
    if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }
    if ($data["address"] === "") $errors[] = "Address is required.";

    return $errors;
}
