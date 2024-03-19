<?php
require_once '../../system/system.php';
echo only_reg();
echo duel_level();
#-Ускоряем время дуэли-#
switch($act){
case 'accel':
#-Проверяем что стоит время-#
$sel_duel_t = $pdo->prepare("SELECT * FROM `duel_time` WHERE `user_id` = :user_id");
$sel_duel_t->execute(array(':user_id' => $user['id']));
if($sel_duel_t->rowCount() != 0){
$duel_time = $sel_duel_t->fetch(PDO::FETCH_LAZY);
#-Сколько времени осталось-#
$duel_ostatok = $duel_time['duel_time']-time();
$battle_d = floor($user['level']/2);
#-Золото за ускорение времени-#
$min = round((((($duel_ostatok/60%60) * 60) /85) + $battle_d), 0);
if($min < 1){
$gold_time = 1;
}else{
$gold_time = round($min, 0);
}
#-Проверка хватает ли денег-#
if($user['gold'] >= $gold_time){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `duel_b` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold'] - $gold_time, ':id' => $user['id'])); 
$del_duel_t = $pdo->prepare("DELETE FROM `duel_time` WHERE `user_id` = :user_id LIMIT 1");
$del_duel_t->execute(array(':user_id' => $user['id']));
header('Location: /duel');
}else{
header('Location: /duel');
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header('Location: /duel');
}
}
?>