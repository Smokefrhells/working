<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
echo clan_level();
/*Создание клана*/
switch($act){
case 'create':
if(isset($_POST['name']) and isset($_POST['type'])){
$name = check($_POST['name']); //Ник
$type = check($_POST['type']); //Ник
#-Проверяем имя клана-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['name'])) $error = 'Некорректное название!';
if(mb_strlen($name) < 5) $error = 'Название меньше 5 символов!';
if(mb_strlen($name) > 25) $error = 'Название больше 25 символов';
#-Проверяем тип-#
if(!in_array($type, array('0', '1'))) $error = 'Неверный тип!';
#-Проверяем есть ли такое название клана-#
$sel_clan = $pdo->prepare("SELECT `name` FROM `clan` WHERE `name` = :name");
$sel_clan->execute(array(':name' => $name)); 
if($sel_clan-> rowCount() != 0) $error = 'Уже есть такое название!';
#-Проверяем что мы не состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u ->execute(array(':user_id' => $user['id']));
if($sel_clan_u -> rowCount() != 0) $error = 'Вы состоите в клане!';
#-Достаточно ли золота-#
if($user['gold'] < 1000) $error = 'Недостаточно золота!';
#-Если нет ошибок-#
if(!isset($error)){
#-Создаем клан-#
$ins_clan = $pdo->prepare("INSERT INTO `clan` SET `name` = :name, `close` = :type, `time` = :time");
$ins_clan->execute(array(':name' => $name, ':type' => $type, ':time' => time()));
$clan_id = $pdo->lastInsertId();//Определяем id клана
#-Добавляем игрока в клан-#
$ins_clan_u = $pdo->prepare("INSERT INTO `clan_users` SET `clan_id` = :clan_id, `user_id` = :user_id, `prava` = :prava, `time` = :time");
$ins_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id'], ':prava' => 4, ':time' => time()));
#-Снимаем золото-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `clan_time` = 0, `clan_id` = :clan_id WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - 1000, ':clan_id' => $clan['id'], ':id' => $user['id']));
header("Location: /clan/view/$clan_id");
}else{
header('Location: /create_clan');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /create_clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}
?>