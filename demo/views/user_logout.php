<!-- filepath: /c:/xampp/htdocs/20222180-NguyenBaHa/demo/logout.php -->
<?php
session_start();
session_unset();
session_destroy();
header('Location: user_login.php');
exit();
?>