<?php
// Check if the constant __HEADER_FOOTER_PHP__ is not defined
if (!defined('__HEADER_FOOTER_PHP__')) {
    // Redirect the user to a different page
    header('Location: index.php');
    exit(); // Stop further execution of the script
}
?>
<br>
<footer class="text-center">
    <div style="background-color: #cee4ac; color: #063918" >
        <br>
        <p>&copy; 2024 Tabletop Tavern</p>
        <br>
    </div>
</footer>