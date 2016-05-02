<?php
session_start();
unset($_SESSION['user']);
unset($_SESSION['is_auth']);
session_destroy()
header("Location: login.php");
?>