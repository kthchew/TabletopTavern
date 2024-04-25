#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } else {
        try {
            $user = Tabletop\Entities\User::createUser($email, $username, $password);
            $_SESSION['user'] = $user;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = "Username or email already exists.";
            } else {
                $error = $e->getMessage();
            }
        }
    }
}
define('__HEADER_FOOTER_PHP__', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabletop Tavern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include 'header.php';?>

<form method="post" action="register.php" class="w-50 mx-auto mt-5">
    <h2 class="text-center">Register</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" id="email" class="form-control">
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <button type="submit" class="btn mb-3">Register</button>
    <p>Already a user? <a href="login.php">Login here.</a></p>
</form>

</body>

</html>
