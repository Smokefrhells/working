<?php
require_once '../../system/system.php';
echo admod();

#-Молчанка-#
switch($act){
case 'ban':
if(isset($_GET['user_id'])){
$user_id = check($_GET['user_id']);
$redicet = check($_GET['redicet']);

#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `prava`, `ban`, `violation`, `cause` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $user_id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден';
$all = $sel_users->fetch(PDO::FETCH_LAZY);
if(!preg_match('/^[0-9]+$/u',$_GET['user_id'])) $error = 'Только цифры!';

if($all['ban'] == 0){
if(!preg_match('/[0-9]/',$_POST['hour'])) $error = 'Только цифры!';
if(mb_strlen($_POST['cause']) < 1)  $error = 'Укажите причину молчанки!';
if(mb_strlen($_POST['cause']) > 255)  $error = 'Слишком длинное сообщение!';
}

#-Нет ошибок-#
if(!isset($error)){

#-ID не должен быть равен нашему и это не админ-#
if($all['id'] != $user['id'] and $all['prava'] != 1 and $all['prava'] != 2 and $all['prava'] != 3){
	
#-Выбираем действие бан или разбан-#
if($all['ban'] == 0){
#-Сколько часов-#
if(isset($_POST['hour'])){
$time_ban = check($_POST['hour']) * 3600; //Переводим часы в секунды
$cause = check($_POST['cause']); //Причина молчанки
#-Баним игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `ban` = :ban, `violation` = :violation, `cause` = :cause WHERE `id` = :id");
$upd_users->execute(array(':ban' => time()+$time_ban, ':violation' => $all['violation']+1, ':cause' => $cause, ':id' => $all['id']));
#-Записываем уведомление в обычный чат-#
$ins_chat_o = $pdo->prepare("INSERT INTO `chat` SET  `type` = :type, `msg` = :msg, `user_id` = :user_id, `time` = :time");
$ins_chat_o->execute(array(':type' => 1, ':msg' => "На игрока $all[nick] наложена молчанка.<br/>Причина: $cause", ':user_id' => $user['id'], ':time' => time()));

$msg='на вас наложена молчанка игроком '.$user['nick'].' причина: '.$cause;//вот
$ins_mail = $pdo->prepare("INSERT INTO `mail` SET `msg` = :msg, `send_id` = :send_id, `recip_id` = :recip_id, `time` = :time");	
$ins_mail->execute(array(':msg' => $msg, ':send_id' => 2, ':recip_id' => $all['id'], ':time' => time()));
#-Есть ли переписка или нет-#
$sel_mail_k = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :user_id AND `kont_id` = :all_id");
$sel_mail_k->execute(array(':all_id' => $all['id'], ':user_id' => 2));
if($sel_mail_k-> rowCount() == 0){
$ins_mail_k1 = $pdo->prepare("INSERT INTO `mail_kont` SET `new` = :new, `user_id` = :user_id, `kont_id` = :kont_id, `time` = :time");	
$ins_mail_k1->execute(array(':new' => 0, ':user_id' => 2, ':kont_id' => $all['id'], ':time' => time()));
}else{
$upd_mail_m = $pdo->prepare("UPDATE `mail_kont` SET `time` = :time WHERE `user_id` = :user_id AND `kont_id` = :kont_id");	
$upd_mail_m->execute(array(':time' => time(), ':kont_id' => $all['id'], ':user_id' => 2));
}

$sel_mail_k2 = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :all_id AND `kont_id` = :user_id");
$sel_mail_k2->execute(array(':all_id' => $all['id'], ':user_id' => 2));
if($sel_mail_k2-> rowCount() == 0){
$ins_mail_k2 = $pdo->prepare("INSERT INTO `mail_kont` SET `new` = :new, `user_id` = :kont_id, `kont_id` = :user_id, `time` = :time");	
$ins_mail_k2->execute(array(':new' => 1, ':kont_id' => $all['id'], ':user_id' => 2, ':time' => time()));	
}else{
$upd_mail_k = $pdo->prepare("UPDATE `mail_kont` SET `new` = :new, `time` = :time WHERE `user_id` = :kont_id AND `kont_id` = :user_id");	
$upd_mail_k->execute(array(':time' => time(), ':new' => 1, ':kont_id' => $all['id'], ':user_id' => 2));
}
#-Скрываем сообщения этого игрока-#
$upd_users = $pdo->prepare("UPDATE `chat` SET `close` = 1 WHERE `user_id` = :all_id");
$upd_users->execute(array(':all_id' => $all['id']));
header("Location: $redicet");
exit();
}else{
header("Location: /moder_ban?ank_id=$all[id]");
$_SESSION['err'] = 'Ошибка!';
exit();
}

}else{
#-Разбан игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `ban` = :ban, `violation` = :violation WHERE `id` = :id");
$upd_users->execute(array(':ban' => 0, ':violation' => $all['violation']-1, ':id' => $all['id']));
#-Записываем уведомление в обычный чат-#
$ins_chat_o = $pdo->prepare("INSERT INTO `chat` SET `type` = :type, `msg` = :msg, `user_id` = :user_id, `time` = :time");
$ins_chat_o->execute(array(':type' => 1, ':msg' => 'Из игрока '.$all['nick'].' снята молчанка.', ':user_id' => $user['id'], ':time' => time()));

$msg='c вас снята молчанка игроком '.$user['nick'];//вот
$ins_mail = $pdo->prepare("INSERT INTO `mail` SET `msg` = :msg, `send_id` = :send_id, `recip_id` = :recip_id, `time` = :time");	
$ins_mail->execute(array(':msg' => $msg, ':send_id' => 2, ':recip_id' => $all['id'], ':time' => time()));
#-Есть ли переписка или нет-#
$sel_mail_k = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :user_id AND `kont_id` = :all_id");
$sel_mail_k->execute(array(':all_id' => $all['id'], ':user_id' => 2));
if($sel_mail_k-> rowCount() == 0){
$ins_mail_k1 = $pdo->prepare("INSERT INTO `mail_kont` SET `new` = :new, `user_id` = :user_id, `kont_id` = :kont_id, `time` = :time");	
$ins_mail_k1->execute(array(':new' => 0, ':user_id' => 2, ':kont_id' => $all['id'], ':time' => time()));
}else{
$upd_mail_m = $pdo->prepare("UPDATE `mail_kont` SET `time` = :time WHERE `user_id` = :user_id AND `kont_id` = :kont_id");	
$upd_mail_m->execute(array(':time' => time(), ':kont_id' => $all['id'], ':user_id' => 2));
}

$sel_mail_k2 = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :all_id AND `kont_id` = :user_id");
$sel_mail_k2->execute(array(':all_id' => $all['id'], ':user_id' => 2));
if($sel_mail_k2-> rowCount() == 0){
$ins_mail_k2 = $pdo->prepare("INSERT INTO `mail_kont` SET `new` = :new, `user_id` = :kont_id, `kont_id` = :user_id, `time` = :time");	
$ins_mail_k2->execute(array(':new' => 1, ':kont_id' => $all['id'], ':user_id' => 2, ':time' => time()));	
}else{
$upd_mail_k = $pdo->prepare("UPDATE `mail_kont` SET `new` = :new, `time` = :time WHERE `user_id` = :kont_id AND `kont_id` = :user_id");	
$upd_mail_k->execute(array(':time' => time(), ':new' => 1, ':kont_id' => $all['id'], ':user_id' => 2));
}
#-Показываем сообщения этого игрока-#
$upd_users = $pdo->prepare("UPDATE `chat` SET `close` = 0 WHERE `user_id` = :all_id");
$upd_users->execute(array(':all_id' => $all['id']));
header("Location: $redicet");
exit();
}
}else{
header("Location: $redicet");
$_SESSION['err'] = 'Ошибка!';
exit();	
}
}else{
header("Location: $redicet");
$_SESSION['err'] = $error;
exit();		
}
}else{
header("Location: $redicet");
$_SESSION['err'] = 'Ошибка!';
exit();		
}
}
?>