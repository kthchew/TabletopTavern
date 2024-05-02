<?php
require __DIR__ . '/../vendor/autoload.php';
if (!defined('__HEADER_FOOTER_PHP__')) {
    $rootPath = Tabletop\Config::getRootPath();
    header("Location: $rootPath/index.php");
    exit();
}
?>
<br>
<footer class="text-center w-100">
    <div style="background-color: #cee4ac; color: #063918" >
        <br>
        <p>&copy; 2024 Tabletop Tavern</p>
        <br>
    </div>
</footer>