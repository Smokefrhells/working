<?php
require_once '../../system/system.php';
echo only_reg();
echo admod();
/*Скрипт модерирования в чате*/

#-Удаление сообщения из чата-#
switch($act){
case 'del_msg':
if(isset($_GET['msg_id']) and isset($_GET['user_id'])){
$msg_id = check($_GET['msg_id']);
$user_id = check($_GET['user_id']);
$chat = check($_GET['chat']);
if($chat == 1) $adress = '/chat?type_chat=obs';
if($chat == 2) $adress = '/chat?type_chat=torg';
if(!preg_match('/^[0-9]+$/u',$_GET['msg_id'])) $error = 'Некорректный идентификатор!';
if(!preg_match('/^[0-9]+$/u',$_GET['user_id'])) $error = 'Некорректный идентификатор!';
if(!isset($error)){
#-Выборка сообщения-#
$sel_chat_msg = $pdo->prepare("SELECT * FROM `chat` WHERE `id` = :msg_id AND `user_id` = :user_id");
$sel_chat_msg->execute(array(':msg_id' => $msg_id, ':user_id' => $user_id));
if($sel_chat_msg-> rowCount() != 0){
$msg = $sel_chat_msg->fetch(PDO::FETCH_LAZY);
#-Удаление сообщения-#
$del_chat = $pdo->prepare("DELETE FROM `chat` WHERE `id` = :msg_id");
$del_chat->execute(array(':msg_id' => $msg['id']));
header("Location: $adress");
exit();
}else{
header("Location: $adress");
$_SESSION['err'] = 'Нет такого сообщения!';
exit();	
}
}else{
header("Location: $adress");
$_SESSION['err'] = $error;
exit();		
}
}else{
header("Location: $adress");
$_SESSION['err'] = 'Нет такого сообщения!';
exit();		
}
}

#-Предупреждение-#
switch($act){
case 'pred':
if(isset($_GET['user_id'])){
$user_id = check($_GET['user_id']);
$chat = check($_GET['chat']);
if($chat == 1) $adress = '/chat?type_chat=obs';
if($chat == 2) $adress = '/chat?type_chat=torg';
if(!preg_match('/^[0-9]+$/u',$_GET['user_id'])) $error = 'Некорректный идентификатор!';
if(!isset($error)){
#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $user_id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-ID не должен быть равен нашему-#
if($all['id'] != $user['id'] and $all['prava'] != 1){
#-Выдаем предупреждение-#
$ins_chat = $pdo->prepare("INSERT INTO `chat` SET `msg` = :msg, `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chat->execute(array(':msg' => ''.$all['nick'].' получает предупреждение!', 'type' => $chat, ':user_id' => $user['id'], ':time' => time()));
header("Location: $adress");
exit();
}else{
header("Location: $adress");
$_SESSION['err'] = 'Вы пытаетесь выдать предупреждение себе!';
exit();	
}
}else{
header("Location: $adress");
$_SESSION['err'] = 'Игрок не найден!';
exit();	
}
}else{
header("Location: $adress");
$_SESSION['err'] = $error;
exit();		
}
}else{
header("Location: $adress");
$_SESSION['err'] = 'Ошибка!';
exit();		
}
}
?>