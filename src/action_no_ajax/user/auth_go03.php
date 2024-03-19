<?php
require "../../pass/lib/password.php";
require_once '../../system/system.php';
global $user;
echo reg();
/*Авторизация игрока*/
switch($act){
case 'login':
if(isset($_POST['nick']) and isset($_POST['password'])){
$nick = check($_POST['nick']);
$password = check($_POST['password']);
$hash = hash_cod('0');
/*-Проверка ввода-*/
#-Ник-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['nick'])) $error = 'Введите другой ник!';
if(mb_strlen($nick) < 1) $error = 'Введите ник!';
if(mb_strlen($nick) > 25) $error = 'Слишком длинный ник!';
#-Пароль-#
if(!preg_match('/^([a-zA-Z0-9]+)([a-zA-Z0-9!@#$%^&*()\-_\+;:,.\/?\|\~\[\]\{\}])+$/u',$_POST['password'])) $error = 'Некорректный пароль!';
if(mb_strlen($password) < 6) $error = 'Минимальная длина пароля 6 символов!';
if(mb_strlen($password) > 25) $error = 'Слишком длинный пароль!';
#-Только если нет ошибок-#
if(!isset($error)){
#-Выборка данных из БД-#
$sel_users= $pdo->prepare("SELECT `id`,`nick`,`password` FROM `users` WHERE `nick` = :nick LIMIT 1");
$sel_users->execute(array(':nick' => $nick)); 
$result = $sel_users->fetch(PDO::FETCH_LAZY);
#-Проверяем пароль и логин-#
if($result['nick'] === $nick and password_verify($password,$result['password'])){
#-Запись данных в БД-#
$upd_users = $pdo->prepare("UPDATE `users` SET `hash` = :hash WHERE `nick` = :nick LIMIT 1");
$upd_users->execute(array(':hash' => $hash, ':nick' => $nick)); 
#-Ставим куки-#
setcookie('UsN',$nick, time()+604800, '/');
setcookie('UsH',$hash, time()+604800, '/');
header ("Location: /");
exit();	
}else{
header('Location: /');
$_SESSION['err'] = 'Неверный ник или пароль!';
exit();		
}
}else{
header('Location: /');
$_SESSION['err'] = $error;
exit();		
}
}else{
header('Location: /');
$_SESSION['err'] = 'Введите данные!';
exit();	
}
}
?>