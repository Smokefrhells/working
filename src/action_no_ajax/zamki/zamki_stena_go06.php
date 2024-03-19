<?php
require_once '../../system/system.php';
echo only_reg();
echo zamki_level();

#-Установка стены в замках-#
switch($act){
case 'stena':
#-Проверяем что есть сражения-#
$sel_zamki = $pdo->query("SELECT `id`, `stena_right`, `stena_left`, `health_max_right`, `health_max_left`, `statys` FROM `zamki` WHERE `statys` = 0");
if($sel_zamki-> rowCount() == 0) $error = 'Ошибка!';
#-Проверяем что участвуем в сражении-#
$sel_zamki_u = $pdo->prepare("SELECT `id`, `user_id`, `storona` FROM `zamki_users` WHERE `user_id` = :user_id");
$sel_zamki_u->execute(array(':user_id' => $user['id']));
if($sel_zamki_u-> rowCount() == 0) $error = 'Ошибка!';
#-Достаточно ли золота-#
if($user['gold'] < 100) $error = 'Недостаточно золота!';


#-Если нет ошибок-#
if(!isset($error)){
$zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
$zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY);	

if($zamki_u['storona'] == 'right'){$stena = $zamki['stena_right'];$health_max_s = $zamki['health_max_right'];}else{$stena = $zamki['stena_left'];$health_max_s = $zamki['health_max_left'];}

#-Стена не должна уже быть установлена-#
if($stena == 0){
$health_stena = round(($health_max_s * 0.25), 0);
#-Правые-#
if($zamki_u['storona'] == 'right'){
$upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_max_right` = :health_stena, `health_right` = :health_stena, `health_t_right` = :health_stena, `stena_right` = 1 LIMIT 1");
$upd_zamki->execute(array(':health_stena' => $zamki['health_max_right']+$health_stena));
}
#-Левые-#
if($zamki_u['storona'] == 'left'){
$upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_max_left` = :health_stena, `health_left` = :health_stena, `health_t_left` = :health_stena, `stena_left` = 1 LIMIT 1");
$upd_zamki->execute(array(':health_stena' => $zamki['health_max_left']+$health_stena));
}
}else{
header('Location: /zamki_battle');
$_SESSION['err'] = 'Стена уже установлена!';
exit();	
}
#-Минус золота-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-100, ':user_id' => $user['id']));
header('Location: /zamki_battle');
exit();	
}else{
header('Location: /zamki_battle');
$_SESSION['err'] = $error;
exit();	
}
}
?>