#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $user = Tabletop\Entities\User::getUserByNamePassword($username, $password);
        $_SESSION['user'] = $user->id;
        $_SESSION['username'] = $user->username;
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
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
</head>

<body>
<header class="container-fluid bg-dark text-white m-0">
    <h1 class="text-center py-5 mb-0">Tabletop Tavern</h1>
</header>
<nav class="navbar navbar-expand-lg bg-dark bg-opacity-75 navbar-dark py-3 justify-content-center">
    <ul class="navbar-nav justify-content-around w-75">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="filter.php">Filter Search</a></li>
        <li class="nav-item"><a class="nav-link" href="creators.php">Creators</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout <?php echo $_SESSION['username'] ?></a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<form action="login.php" method="post" class="w-50 mx-auto mt-5">
    <h2 class="text-center">Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

</body>

</html>
