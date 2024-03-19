<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
echo admod();
	
#-Отправка сообщений в чат-#
switch($act){
case 'send':
if(isset($_POST['msg'])){
$msg = check($_POST['msg']); //Сообщение
$ank_id = check($_POST['ank_id']); //ID того кому пишем
#-Проверяем данные-#
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 5000) $error = 'Слишком длинное сообщение!';
#-Если нет ошибок-#
if(!isset($error)){
$upd_chat = $pdo->prepare("INSERT INTO `chat_moderator` SET `msg` = :msg, `user_id` = :user_id, `ank_id` = :ank_id, `time` = :time");
$upd_chat->execute(array(':msg' => $msg, ':user_id' => $user['id'], ':ank_id' => $ank_id, ':time' => time())); 
header ('Location: /chat_moderator');
}else{
header('Location: /chat_moderator');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /chat_moderator');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Удаление сообщения из чата-#
switch($act){
case 'delete':
if(isset($_GET['msg_id']) and isset($_GET['user_id'])){
$msg_id = check($_GET['msg_id']);
$user_id = check($_GET['user_id']);
if(!preg_match('/^[0-9]+$/u',$_GET['msg_id'])) $error = 'Некорректный идентификатор!';
if(!preg_match('/^[0-9]+$/u',$_GET['user_id'])) $error = 'Некорректный идентификатор!';
if(!isset($error)){
#-Выборка сообщения-#
$sel_chat_msg = $pdo->prepare("SELECT * FROM `chat_moderator` WHERE `id` = :msg_id AND `user_id` = :user_id");
$sel_chat_msg->execute(array(':msg_id' => $msg_id, ':user_id' => $user_id));
if($sel_chat_msg-> rowCount() != 0){
$msg = $sel_chat_msg->fetch(PDO::FETCH_LAZY);
#-Удаление сообщения-#
$del_chat = $pdo->prepare("DELETE FROM `chat_moderator` WHERE `id` = :msg_id");
$del_chat->execute(array(':msg_id' => $msg['id']));
header('Location: /chat_moderator');
$_SESSION['ok'] = 'Сообщение удалено!';
exit();
}else{
header('Location: /chat_moderator');
$_SESSION['err'] = 'Нет такого сообщения!';
exit();	
}
}else{
header('Location: /chat');
$_SESSION['err'] = $error;
exit();		
}
}else{
header('Location: /chat_moderator');
$_SESSION['err'] = 'Нет такого сообщения!';
exit();		
}
}
?>