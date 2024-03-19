<?php
require_once '../../system/system.php';
echo only_reg();
#-Удаление уведомления-#
switch($act){
case 'del':
if(isset($_GET['event_id'])){
$event_id = check($_GET['event_id']);
$redicet = check($_GET['redicet']);
#-Проверяем что есть такое уведомление-#
$sel_event = $pdo->prepare("SELECT `id`, `user_id` FROM `event_log` WHERE `id` = :event_id AND `user_id` = :user_id");
$sel_event->execute(array(':event_id' => $event_id, ':user_id' => $user['id']));
if($sel_event-> rowCount() != 0){
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `id` = :event_id AND `user_id` = :user_id");
$del_event->execute(array(':event_id' => $event_id, ':user_id' => $user['id']));
header("Location: $redicet");
}else{
header("Location: $redicet");
$_SESSION['err'] = 'Уведомление не найдено!';
exit();		
}
}else{
header("Location: $redicet");
$_SESSION['err'] = 'Ошибка!';
exit();		
}
}

#-Удаление всех уведомлений одного типа-#
switch($act){
case 'del_all':
if(isset($_GET['type'])){
$type = check($_GET['type']);
$redicet = check($_GET['redicet']);
#-Проверяем что есть такое уведомление-#
$sel_event = $pdo->prepare("SELECT `id`, `user_id`, `type` FROM `event_log` WHERE `type` = :type AND `user_id` = :user_id");
$sel_event->execute(array(':type' => $type, ':user_id' => $user['id']));
if($sel_event-> rowCount() != 0){
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `type` = :type AND `user_id` = :user_id");
$del_event->execute(array(':type' => $type, ':user_id' => $user['id']));
header("Location: $redicet");
}else{
header("Location: $redicet");
exit();		
}
}else{
header("Location: $redicet");
$_SESSION['err'] = 'Ошибка!';
exit();		
}
}


#-Приглашать в клан или нет-#
switch($act){
case 'clan':
if($user['ev_clan'] == 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_clan` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_clan` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
header('Location: /setting');
exit();
}

#-Звать на помощь-#
switch($act){
case 'help':
if($user['ev_help'] == 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_help` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_help` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
header('Location: /setting');
exit();
}

#-Писать сообщения или нет-#
switch($act){
case 'mail':
if($user['ev_mail'] == 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_mail` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
if($user['ev_mail'] == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_mail` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
if($user['ev_mail'] == 2){
$upd_users = $pdo->prepare("UPDATE `users` SET `ev_mail` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
header('Location: /setting');
exit();
}

#-Скрываем клановое обьявление-#
switch($act){
case 'del_ad':
$redicet = check($_GET['redicet']);
if($user['clan_ad'] == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_ad` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
header("Location: $redicet");
exit();
}
?>