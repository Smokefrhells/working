<?php
require_once 'system/system.php';
setcookie('UsN', '', time() - 100, '/');
setcookie('UsH', '', time() - 100, '/');
header('Location: /');
exit();
?>