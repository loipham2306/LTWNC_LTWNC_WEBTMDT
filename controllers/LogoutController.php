<?php
session_start();
session_unset();
session_destroy();
header("Location: ../views/pages/login.php");
exit();
?>