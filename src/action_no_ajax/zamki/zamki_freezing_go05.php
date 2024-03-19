<?php
require_once '../../system/system.php';
echo only_reg();
echo zamki_level();

#-Заморозка в замках-#
switch($act){
case 'freezing':
#-Проверяем что есть сражения-#
$sel_zamki = $pdo->query("SELECT * FROM `zamki` WHERE `statys` = 1");
if($sel_zamki-> rowCount() == 0) $error = 'Ошибка!';
#-Проверяем что участвуем в сражении-#
$sel_zamki_u = $pdo->prepare("SELECT `id`, `user_id`, `storona`, `quatity_freezing`, `time_freezing` FROM `zamki_users` WHERE `user_id` = :user_id AND `quatity_freezing` = 0");
$sel_zamki_u->execute(array(':user_id' => $user['id']));
if($sel_zamki_u-> rowCount() == 0) $error = 'Ошибка!';
#-Достаточно ли золота-#
$sel_zamki_g = $pdo->prepare("SELECT `id`, `max_uron_id` FROM `zamki` WHERE `max_uron_id` = :user_id");
$sel_zamki_g->execute(array(':user_id' => $user['id']));
if($sel_zamki_g-> rowCount() == 0){
if($user['gold'] < 50) $error = 'Недостаточно золота!';
}

#-Если нет ошибок-#
if(!isset($error)){
$zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY);	

#-Сторона противоположна даному игроку-#
if($zamki_u['storona'] == 'right'){$storona = 'left';}else{$storona = 'right';}
#-Выборка рандомно игрока для заморозки-#
$sel_zamki_m = $pdo->prepare("SELECT `id`, `time_freezing`, `user_id`, `storona` FROM `zamki_users` WHERE `time_freezing` = 0 AND `user_id` != :user_id AND `storona` = :storona ORDER BY RAND()");
$sel_zamki_m->execute(array(':user_id' => $user['id'], ':storona' => $storona));
if($sel_zamki_m-> rowCount() != 0){
$zamki_m = $sel_zamki_m->fetch(PDO::FETCH_LAZY);	
#-Выборка данных игрока-#	
$sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $zamki_m['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Заморозка игрока-#
$upd_zamki_m = $pdo->prepare("UPDATE `zamki_users` SET `time_freezing` = :time_freezing WHERE `user_id` = :all_id");
$upd_zamki_m->execute(array(':time_freezing' => time()+30, ':all_id' => $all['id']));
#-Запись лога-#
$ins_log = $pdo->prepare("INSERT INTO `zamki_log` SET `log` = :log, `storona` = :storona, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/body/freezing.png' alt=''/> $user[nick] заморозил(а) $all[nick]", ':storona' => $zamki_u['storona'], ':time' => time()));

#-Минус золота-#
$sel_zamki_g = $pdo->prepare("SELECT `id`, `max_uron_id` FROM `zamki` WHERE `max_uron_id` = :user_id");
$sel_zamki_g->execute(array(':user_id' => $user['id']));
if($sel_zamki_g-> rowCount() == 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-50, ':user_id' => $user['id']));
}
#-Можно использовать только 1 раз-#
$upd_zamki_u = $pdo->prepare("UPDATE `zamki_users` SET `quatity_freezing` = 1 WHERE `user_id` = :user_id LIMIT 1");
$upd_zamki_u->execute(array(':user_id' => $user['id']));
header('Location: /zamki_battle');
exit();	
}else{
header('Location: /zamki_battle');
$_SESSION['err'] = 'Нет подходящего игрока!';
exit();	
}
}else{
header('Location: /zamki_battle');
$_SESSION['err'] = $error;
exit();	
}
}
?>