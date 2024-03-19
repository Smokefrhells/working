<?php
require_once '../../system/system.php';
echo only_reg();
echo hunting_campaign();

#-Автоматическая атака охота-#
switch($act){
case 'attc':
if(isset($_GET['loc'])){
$loc = check($_GET['loc']);
#-Выбор локации-#
$sel_hunting = $pdo->prepare("SELECT * FROM `hunting` WHERE `id` = :id");
$sel_hunting->execute(array(':id' => $loc));
$hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);
#-Отдых или нет-#
$sel_hunting_t = $pdo->prepare("SELECT * FROM `hunting_time` WHERE `user_id` = :user_id AND `hunting_id` = :hunting_id");
$sel_hunting_t->execute(array(':user_id' => $user['id'], ':hunting_id' => $hunting['id']));
if($sel_hunting_t->rowCount() != 0) $error = 'Локация недоступна!';

#-Первая-#
if($hunting['id'] == 1){
$batt = $user['hunting_1'];
$hunting_o = 10-$user['hunting_1'];
$hunting_g = round($hunting_o/2, 0);
$hunting_1 = 10;
$bhunting_b_1 = 10;
}
#-Вторая-#
if($hunting['id'] == 2){
$batt = $user['hunting_2'];
$hunting_o = 10-$user['hunting_2'];
$hunting_g = round($hunting_o, 0);
$hunting_2 = 10;
$bhunting_b_2 = 10;
}
#-Третья-#
if($hunting['id'] == 3){
$batt = $user['hunting_3'];
$hunting_o = 10-$user['hunting_3'];
$hunting_g = round($hunting_o+2, 0);
$hunting_3 = 10;
$bhunting_b_3 = 10;
}
#-Четвертая-#
if($hunting['id'] == 4){
$batt = $user['hunting_4'];
$hunting_o = 10-$user['hunting_4'];
$hunting_g = round($hunting_o+4, 0);
$hunting_4 = 10;
$bhunting_b_4 = 10;
}
#-Пятая-#
if($hunting['id'] == 5){
$batt = $user['hunting_5'];
$hunting_o = 10-$user['hunting_5'];
$hunting_g = round($hunting_o+8, 0);
$hunting_5 = 10;
$bhunting_b_5 = 10;
}
#-Шестая-#
if($hunting['id'] == 6){
$batt = $user['hunting_6'];
$hunting_o = 10-$user['hunting_6'];
$hunting_g = round($hunting_o+14, 0);
$hunting_6 = 10;
$bhunting_b_6 = 10;
}
#-Седьмая-#
if($hunting['id'] == 7){
$batt = $user['hunting_7'];
$hunting_o = 10-$user['hunting_7'];
$hunting_g = round($hunting_o+20, 0);
$hunting_7 = 10;
$bhunting_b_7 = 10;
}

#-Есть ли бои-#
if($batt >= 10) $error = 'Нет боёв!';
#-Достаточно золота-#
if($user['gold'] < $hunting_g) $error = 'Недостаточно золота!';
#-Нет ошибок-#
if(!isset($error)){
#-Параметры игрока-#
$user_param = $user['sila']+$user['zashita']+$user['health']+$user['s_sila']+$user['s_zashita']+$user['s_health']+$user['sila_bonus']+$user['zashita_bonus']+$user['health_bonus'];

#-Повторение боёв-#
for($b=0; $b < $hunting_o; $b++){
#-Выборка монстров из полученной локации обычные-#
$sel_monsters = $pdo->prepare("SELECT * FROM `monsters` WHERE `location` = :location ORDER BY RAND()");
$sel_monsters->execute(array(':location' => $loc));
$monsters = $sel_monsters->fetch(PDO::FETCH_LAZY);
#-Параметры монстра-#
$monsters_param = $monsters['sila']+$monsters['zashita']+$monsters['health'];
	
#-Опыт-#
$exp_no = rand($monsters['exp_1'], $monsters['exp_2']);
if($user['premium'] == 0){$exp = $exp_no;}
if($user['premium'] == 1){$exp = $exp_no * 2;}
if($user['premium'] == 2){$exp = $exp_no * 3;}
#-Акция на опыт-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 13");
if($sel_stock-> rowCount() != 0){
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);
$exp = round($exp+(($exp/100)*$stock['prosent']), 0);
}

#-ПОБЕДА-#
if($user_param >= $monsters_param){
#-Опыт-#
$exp_a_no = $exp_a_no+$exp_no;
$exp_a = $exp_a+$exp;
#-Серебро-#
$silver_no = rand($monsters['silver_1'], $monsters['silver_2']);
if($user['premium'] == 0){$silver = $silver_no;}
if($user['premium'] == 1){$silver = round((($silver_no * 0.25) + $silver_no), 0);}
if($user['premium'] == 2){$silver = round((($silver_no * 0.50) + $silver_no), 0);}
#-Кристаллы-#
$rand_2 = rand(0, 100);
if($rand_2 >= 80){
if($hunting['user_id'] == $user['id']){
$q1 = 20*$hunting['id'];
$q2 = 200*$hunting['id'];
}else{
$q1 = 10*$hunting['id'];
$q2 = 100*$hunting['id'];
}
$crystal = rand($q1,$q2);
}
#-Ключ-#
$rand = rand(0, 100);
if($rand < 3){
$key = 1;
}else{
$key = 0;
}
$silver_a_no = $silver_a_no+$silver_no;
$silver_a = $silver_a+$silver;
$pobeda_a = $pobeda_a+1;
$crystal_a = $crystal_a+$crystal;
$key_a = $key_a+$key;
}else{
#-ПОРАЖЕНИЕ-#	
#-Опыт-#
$exp_a_no = $exp_a_no+$exp_no;
$exp_a = $exp_a+$exp;
$progrash_a = $progrash_a+1;
}
}

#-Проверка параметров-#
if(!isset($silver_a)){$silver_a = 0;}
if(!isset($pobeda_a)){$pobeda_a = 0;}
if(!isset($progrash_a)){$progrash_a = 0;}
if(!isset($crystal_a)){$crystal_a = 0;}

#-ЕЖЕДНЕВНЫЕ ЗАДАНИЯ ОХОТА-#
$sel_tasks = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 1");
$sel_tasks->execute(array(':user_id' => $user['id']));
if($sel_tasks-> rowCount() != 0){
$tasks = $sel_tasks->fetch(PDO::FETCH_LAZY);
$t_p = $tasks['quatity']+$pobeda_a;
#-Если еще не выполнено-#
if($t_p < 9){
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity WHERE `id` = :id LIMIT 1");
$upd_tasks->execute(array(':quatity' => $tasks['quatity']+1, ':id' => $tasks['id']));	
}else{
if($tasks['statys'] == 0){
$exp_tasks = 3500;
$silver_tasks = 5000;
$gold_tasks = 3;
$tasks_q = 1;
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity, `statys` = 1 WHERE `user_id` = :user_id AND `type` = 1 LIMIT 1");
$upd_tasks->execute(array(':quatity' => 10, ':user_id' => $user['id']));
$_SESSION['ok'] = '<b>Охота</b>: <img src="/style/images/many/gold.png">3 <img src="/style/images/many/silver.png">5000 <img src="/style/images/user/exp.png">3500';
}
}
}

#-Зачисление награды-#
$upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `crystal` = :crystal, `gold` = :gold, `key` = :key, `hunting_pobeda` = :hunting_pobeda, `hunting_progrash` = :hunting_progrash, `tasks` = :tasks WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':exp' => $user['exp']+$exp_a+$exp_tasks, ':exp_clan' => $exp_a+$exp_tasks, ':silver' => $user['silver']+$silver_a+$silver_tasks, ':crystal' => $user['crystal']+$crystal_a, ':gold' => ($user['gold']-$hunting_g)+$gold_tasks, ':key' => $user['key']+$key_a, ':hunting_pobeda' => $user['hunting_pobeda']+$pobeda_a, ':hunting_progrash' => $user['hunting_progrash']+$progrash_a, ':tasks' => $user['tasks']+$tasks_q, ':id' => $user['id']));	
#-Ставим время-#
$ins_hunting_t = $pdo->prepare("INSERT INTO `hunting_time` SET `hunting_id` = :hunting_id, `hunting_time` = :hunting_time, `user_id` = :user_id");
$ins_hunting_t->execute(array(':hunting_id'=> $hunting['id'], ':hunting_time' => time()+$hunting['time_hunting'], ':user_id' => $user['id']));
#-Кол-во боёв-#
if($hunting['id'] == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_1` = 10, `hunting_b_1` = :hunting_b_1 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_1' => $user['hunting_b_1']+$hunting_o, ':user_id' => $user['id']));
}
if($hunting['id'] == 2){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_2` = 10, `hunting_b_2` = :hunting_b_2 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_2' => $user['hunting_b_2']+$hunting_o, ':user_id' => $user['id']));
}
if($hunting['id'] == 3){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_3` = 10, `hunting_b_3` = :hunting_b_3 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_3' => $user['hunting_b_3']+$hunting_o, ':user_id' => $user['id']));
}
if($hunting['id'] == 4){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_4` = 10, `hunting_b_4` = :hunting_b_4 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_4' => $user['hunting_b_4']+$hunting_o, ':user_id' => $user['id']));
}
if($hunting['id'] == 5){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_5` = 10, `hunting_b_5` = :hunting_b_5 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_5' => $user['hunting_b_5']+$hunting_o, ':user_id' => $user['id']));
}
if($hunting['id'] == 6){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_6` = 10, `hunting_b_6` = :hunting_b_6 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_6' => $user['hunting_b_6']+$hunting_o, ':user_id' => $user['id']));
}
if($hunting['id'] == 7){
$upd_users = $pdo->prepare("UPDATE `users` SET `hunting_7` = 10, `hunting_b_7` = :hunting_b_7 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':hunting_b_7' => $user['hunting_b_7']+$hunting_o, ':user_id' => $user['id']));
}

#-Опыт без премиума-#
if($user['premium'] == 1 or $user['premium'] == 2){
$exp_no_p = "<span class='white'><strike>$exp_a_no</strike></span> "; //Опыт без премиума
}
#-Серебро без премиума-#
if($user['premium'] == 1 or $user['premium'] == 2){
$silver_no_p = "<span class='white'><strike>$silver_a_no</strike></span> "; //Серебро без премиума	
}
header('Location: /select_location');
$_SESSION['notif'] = "<center><img src='/style/images/body/ok.png' alt=''/>Побед: $pobeda_a <img src='/style/images/body/error.png' alt=''/>Поражений: $progrash_a<br/><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_a <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver_a <img src='/style/images/many/crystal.png' alt=''/>$crystal_a <img src='/style/images/body/key.png' alt=''/>$key_a</center>";
}else{
header('Location: /select_location');
$_SESSION['err'] = $error;
exit();
}
}
}
?>