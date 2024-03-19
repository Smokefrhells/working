<?php
date_default_timezone_set('Europe/moscow');
header('Content-Type:text/html; charset=utf-8');
$set['site'] = htmlspecialchars($_SERVER['HTTP_HOST']);
define("H", $_SERVER["DOCUMENT_ROOT"] . '/');
mb_internal_encoding("UTF-8");
#-Подключение базы данных-#
$pdo = new PDO('mysql:host=mysql;dbname=test', 'user', 'password', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
#-Загрузка функций-#
require_once H . 'system/function.php';
#-Cookie персонажа-#
session_start();
if (isset($_COOKIE['UsN']) and isset($_COOKIE['UsH'])) {
    $nick = check($_COOKIE['UsN']);
    $hash = check($_COOKIE['UsH']);
    $query = $pdo->prepare("SELECT * FROM `users` WHERE `nick` = :nick AND `hash` = :hash LIMIT 1");
    $query->execute(array(':nick' => $nick, ':hash' => $hash));
    $result = $query->fetch();
    $user = $result;
}
$act = isset($_GET['act']) ? htmlspecialchars($_GET['act']) : '';
foreach ($_GET as $ad) {
    if (is_numeric($ad)) {
        $ad = abs(intval($ad));
        if (preg_match('/\|include|asc|select|union|update|from|where|eval|glob|include|require|script|shell|INCLUDE|ASC|SELECT|UNION|UPDATE|FROM|WHERE|EVAL|GLOB|REQUIRE|SCRIPT|SHELL|BENCHMARK|CONCAT|INSERT\b/i', $ad)) {
            header("Refresh: 0;url=/" . SID);
            exit;
        }
    }
}
ob_start();
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
foreach ($_SESSION as $ad) {
    $ad = htmlspecialchars($ad);
}
foreach ($_COOKIE as $ad) {
    $ad = htmlspecialchars($ad);
}
$id = isset($_GET['id']) ? abs(intval($_GET['id'])) : NULL;

