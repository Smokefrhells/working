<?php
require_once '../../system/system.php';
echo admin();

#-Закрытие игры-#
switch($act){
case 'close':
$sel_close = $pdo->query("SELECT * FROM `close` WHERE `close` = 1");
if($sel_close-> rowCount() == 0){
$ins_close = $pdo->prepare("INSERT INTO `close` SET `close` = :close, `user_id` = :user_id, `time` = :time");
$ins_close->execute(array(':close' => 1, ':user_id' => $user['id'], ':time' => time()));
}else{
$del_close = $pdo->query("DELETE FROM `close` WHERE `close` = 1");
}
header('Location: /admin');
}

#-Подарок-#
switch($act){
case 'podarok':
if(isset($_POST['quatity']) and isset($_POST['type'])){
$quatity = check($_POST['quatity']);
$type = check($_POST['type']);
if(!preg_match('/^[0-9]+$/u',$_POST['quatity'])) $error = 'Некорректная сума!';
if(!in_array($type, array('1','2','3', '4'))) $error = 'Неверный тип!';
#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `podarok` = :quatity, `type_podarok` = :type");
$upd_users->execute(array(':quatity' => $quatity, ':type' => $type));	
header('Location: /admin');
$_SESSION['ok'] = 'Подарок установлен!';
exit();
}else{
header('Location: /admin_bonus');
$_SESSION['ok'] = $error;
exit();	
}
}else{
header('Location: /admin');
$_SESSION['ok'] = 'Ошибка!';
exit();	
}
}

#-Удалить подарок-#
switch($act){
case 'delete_podarok':
$upd_users = $pdo->prepare("UPDATE `users` SET `podarok` = :quatity, `type_podarok` = :type");
$upd_users->execute(array(':quatity' => 0, ':type' => 0));	
header('Location: /admin');
$_SESSION['ok'] = 'Подарок убран!';
exit();
}

#-Зачисление золота-#
switch($act){
case 'gold':
if(isset($_POST['ank_id']) and isset($_POST['quatity']) and $user['prava'] == 1){
$ank_id = check($_POST['ank_id']);
$quatity = check($_POST['quatity']);
if(!preg_match('/^[0-9]+$/u',$_POST['quatity'])) $error = 'Некорректная сума бонуса!';
#-Если нет ошибок-#
if(!isset($error)){
#-Проверяем есть ли игрок-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() != 0){
$ank = $sel_users->fetch(PDO::FETCH_LAZY);
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['gold']+$quatity, ':ank_id' => $ank['id']));
#-Есть ли реферер у игрока-#	
if($ank['ref_id'] != 0){
#-Есть ли такой реферер-#	
$sel_users_r = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :ref_id");
$sel_users_r->execute(array(':ref_id' => $ank['ref_id']));
if($sel_users_r-> rowCount() != 0){
#-Высчитываем 15%-#
$gold_pr = (($quatity*15)/100);
$gold = round($gold_pr, 0);
$referer = $sel_users_r->fetch(PDO::FETCH_LAZY);
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :quatity, `referer_gold` = :ref_gold WHERE `id` = :referer_id");
$upd_users->execute(array(':quatity' => $referer['gold']+$gold, ':ref_gold' => $referer['referer_gold']+$gold, ':referer_id' => $referer['id']));
}
}
header('Location: /admin_gold');
$_SESSION['ok'] = 'Золото зачислено!';
exit();
}else{
header('Location: /admin_gold');
$_SESSION['ok'] = 'Игрок не найден!';
exit();	
}
}else{
header('Location: /admin_gold');
$_SESSION['ok'] = $error;
exit();	
}
}else{
header('Location: /admin');
$_SESSION['ok'] = 'Ошибка!';
exit();	
}
}

#-Зачисление ресурсов-#
switch($act){
case 'res':
if(isset($_POST['ank_id']) and isset($_POST['quatity']) and isset($_POST['type']) and $user['prava'] == 1){
$ank_id = check($_POST['ank_id']);
$quatity = check($_POST['quatity']);
$type = check($_POST['type']);
if(!preg_match('/^[0-9]+$/u',$_POST['quatity'])) $error = 'Некорректная сума бонуса!';
#-Если нет ошибок-#
if(!isset($error)){
#-Проверяем есть ли игрок-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() != 0){
$ank = $sel_users->fetch(PDO::FETCH_LAZY);
#-Ключи-#
if($type == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `key` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['key']+$quatity, ':ank_id' => $ank['id']));
}
#-Серебро-#
if($type == 2){
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['key']+$quatity, ':ank_id' => $ank['id']));
}
#-Золото-#
if($type == 3){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['key']+$quatity, ':ank_id' => $ank['id']));
}
#-Кристаллы-#
if($type == 4){
$upd_users = $pdo->prepare("UPDATE `users` SET `crystal` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['key']+$quatity, ':ank_id' => $ank['id']));
}
header('Location: /admin');
$_SESSION['ok'] = 'Ресурсы зачислены!';
exit();
}else{
header('Location: /admin_gold');
$_SESSION['ok'] = 'Игрок не найден!';
exit();	
}
}else{
header('Location: /admin_gold');
$_SESSION['ok'] = $error;
exit();	
}
}else{
header('Location: /admin');
$_SESSION['ok'] = 'Ошибка!';
exit();	
}
}
?>