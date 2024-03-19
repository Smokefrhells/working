<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
#-Отправляем заявку в друзья-#
switch($act){
case 'send':
if(isset($_GET['id'])){
$id = check($_GET['id']); //ID игрока
#-Проверяем что есть такой ID-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден!';
#-Проверяем чтоигрока нет в друзьях-#
$sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE (`friend_1` = :user_id AND `friend_2` = :all_id) OR (`friend_1` = :all_id AND `friend_2` = :user_id)");
$sel_friends->execute(array(':user_id' => $user['id'], ':all_id' => $id));
if($sel_friends-> rowCount() != 0) $error = 'Игрок уже в друзьях!';
#-Проверяем отправляли заявку или нет-#
$sel_event = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :ank_id AND `ank_id` = :user_id");
$sel_event->execute(array(':user_id' => $user['id'], ':ank_id' => $id));
if($sel_event-> rowCount() != 0) $error = 'Вы уже отправили заявку!';
#-Проверяем что не наш id-#
if($id == $user['id']) $error = 'Это ваш ID!';
#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `ank_id` = :ank_id, `time` = :time");
$ins_event ->execute(array(':type' => 6, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> хочет подружиться с вами", ':user_id' => $all['id'], ':ank_id' => $user['id'], ':time' => time()));
header("Location: /hero/$id");
exit();
}else{
header("Location: /hero/$id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
#-Принимаем заявку в друзья-#
switch($act){
case 'get':
if(isset($_GET['event_id'])){
$event_id = check($_GET['event_id']); //ID уведомления
$redicet = check($_GET['redicet']);
#-Проверяем что есть такое уведомление-#
$sel_event = $pdo->prepare("SELECT * FROM `event_log` WHERE `id` = :event_id");
$sel_event->execute(array(':event_id' => $event_id));
if($sel_event-> rowCount() == 0) $error = 'Уведомление не найдено!';
#-Если нет ошибок-#
if(!isset($error)){
$event = $sel_event->fetch(PDO::FETCH_LAZY);
#-Проверяем что игрока нет в друзьях-#
$sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE (`friend_1` = :user_id AND `friend_2` = :all_id) OR (`friend_1` = :all_id AND `friend_2` = :user_id)");
$sel_friends->execute(array(':user_id' => $user['id'], ':all_id' => $event['ank_id']));
if($sel_friends-> rowCount() == 0){
#-Добавляем в друзья-#
$ins_friends = $pdo->prepare("INSERT INTO `friends` SET `friend_1` = :user_id, `friend_2` = :ank_id, `time` = :time");
$ins_friends->execute(array(':user_id' => $user['id'], ':ank_id' => $event['ank_id'], ':time' => time()));
#-Удаляем уведомление-#
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE (`user_id` = :user_id AND `ank_id` = :ank_id) OR (`user_id` = :ank_id AND `user_id` = :ank_id) AND `type` = 6");
$del_event ->execute(array(':user_id' => $user['id'], ':ank_id' => $event['ank_id']));
header("Location: $redicet");
exit();
}else{
header("Location: $redicet");
$_SESSION['err'] = 'Игрок у вас в друзьях!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
#-Удаляем из друзей-#
switch($act){
case 'del':
if(isset($_GET['id'])){
$id = check($_GET['id']); //ID игрока
$redicet = check($_GET['redicet']);
#-Проверяем что есть такой ID-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден!';
#-Проверяем что игрок есть в друзьях-#
$sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE (`friend_1` = :user_id AND `friend_2` = :all_id) OR (`friend_1` = :all_id AND `friend_2` = :user_id)");
$sel_friends->execute(array(':user_id' => $user['id'], ':all_id' => $id));
if($sel_friends-> rowCount() == 0) $error = 'Игрока нет в друзьях!';
#-Если нет ошибок-#
if(!isset($error)){
$friends = $sel_friends->fetch(PDO::FETCH_LAZY);
#-Удаляем из друзей-#
$del_friends = $pdo->prepare("DELETE FROM `friends` WHERE `id` = :friends_id");
$del_friends->execute(array(':friends_id' => $friends['id']));
#-Уведомление что удалили из друзей-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `ank_id` = :ank_id, `time` = :time");
$ins_event ->execute(array(':type' => 7, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> удалил(а) вас из друзей", ':user_id' => $id, ':ank_id' => $user['id'], ':time' => time()));
header("Location: $redicet");
exit();
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>