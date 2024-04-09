#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
session_destroy();
header('Location: index.php');
exit;
?>