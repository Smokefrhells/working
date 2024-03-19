<?php
require_once '../../system/system.php';
echo only_reg();
echo hunting_campaign();

/*Бой в охоте*/
switch($act){
case 'attc':
#-Выборка данных о бое-#
$sel_hunting_battle = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id");
$sel_hunting_battle->execute(array(':user_id' => $user['id']));
if($sel_hunting_battle-> rowCount() != 0){
$battle = $sel_hunting_battle->fetch(PDO::FETCH_LAZY);
#-Выборка данных о монстре-#
$sel_monsters = $pdo->prepare("SELECT * FROM `monsters` WHERE `id` = :monstr_id");
$sel_monsters->execute(array(':monstr_id' => $battle['monstr_id']));
$monsters = $sel_monsters->fetch(PDO::FETCH_LAZY);
#-Выбор локации-#
$sel_hunting = $pdo->prepare("SELECT `id`, `user_id` FROM `hunting` WHERE `id` = :id");
$sel_hunting->execute(array(':id' => $monsters['location']));
$hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);
#-Бои какой локации-#
if($hunting['id'] == 1){$batt = $user['hunting_1'];}
if($hunting['id'] == 2){$batt = $user['hunting_2'];}
if($hunting['id'] == 3){$batt = $user['hunting_3'];}
if($hunting['id'] == 4){$batt = $user['hunting_4'];}
if($hunting['id'] == 5){$batt = $user['hunting_5'];}
if($hunting['id'] == 6){$batt = $user['hunting_6'];}
if($hunting['id'] == 7){$batt = $user['hunting_7'];}
#-Урон игрока-#
$v_user_uron = ((($user['sila']+$user['s_sila']+$user['sila_bonus']) * 0.4) * 5);
$v_monstr_zashita = (($monsters['zashita'] * 0.2) * 10);
if($v_user_uron > $v_monstr_zashita){
$r_user_uron_1 = ($v_user_uron - $v_monstr_zashita);
$r_user_uron_2 = ($v_user_uron - ($v_monstr_zashita / 2));
}else{
$r_user_uron_1 = ($v_user_uron / 5);
$r_user_uron_2 = ($v_user_uron / 4);
}
$user_uron = rand($r_user_uron_1, $r_user_uron_2);
$user_uron = round($user_uron, 0);
#-Урон монстра-#
$v_monstr_uron = (($monsters['sila'] * 0.5) * 5);
$v_user_zashita = ((($user['zashita']+$user['s_zashita']+$user['zashita_bonus']) * 0.2) * 10);
if($v_monstr_uron > $v_user_zashita){
$r_monstr_uron_1 = ($v_monstr_uron - $v_user_zashita);
$r_monstr_uron_2 = ($v_monstr_uron - ($v_user_zashita / 2));
}else{
$r_monstr_uron_1 = ($v_monstr_uron / 5);
$r_monstr_uron_2 = ($v_monstr_uron / 4);
}
$monstr_uron = rand($r_monstr_uron_1, $r_monstr_uron_2);
$monstr_uron = round($monstr_uron, 0);
#-Награда за бой-#
#-Ключ-#
$rand = rand(0, 100);
if($rand < 3){
$key = 1;
$key_i = "<img src='/style/images/body/key.png' alt='Ключ'/>1";
}else{
$key = 0;
}

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
$crystal_inf = "<img src='/style/images/many/crystal.png'/>$crystal";
}else{
$crystal = 0;
}

#-Серебро и опыт-#
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
if($user['premium'] == 1 or $user['premium'] == 2){
$exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
}	

$silver_no = rand($monsters['silver_1'], $monsters['silver_2']);
if($user['premium'] == 0){$silver = $silver_no;}
if($user['premium'] == 1){$silver = round((($silver_no * 0.25) + $silver_no), 0);}
if($user['premium'] == 2){$silver = round((($silver_no * 0.50) + $silver_no), 0);}
if($user['premium'] == 1 or $user['premium'] == 2){
$silver_no_p = "<span class='white'><strike>$silver_no</strike></span> "; //Серебро без премиума	
}

$monstr_health = $battle['monstr_t_health'] - $user_uron;
$user_health = $battle['user_t_health'] - $monstr_uron;
#-Заносим результаты в базу-#

#-ПОБЕДА-#
if($user_uron >= $battle['monstr_t_health']){
	
#-ЕЖЕДНЕВНЫЕ ЗАДАНИЯ ОХОТА-#
$sel_tasks = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 1");
$sel_tasks->execute(array(':user_id' => $user['id']));
if($sel_tasks-> rowCount() != 0){
$tasks = $sel_tasks->fetch(PDO::FETCH_LAZY);
#-Если еще не выполнено-#
if($tasks['quatity'] < 9){
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity WHERE `id` = :id LIMIT 1");
$upd_tasks->execute(array(':quatity' => $tasks['quatity']+1, ':id' => $tasks['id']));	
}else{
if($tasks['statys'] == 0){
$exp_tasks = 3500;
$silver_tasks = 5000;
$gold_tasks = 3;
$tasks_q = 1;
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity, `statys` = 1 WHERE `user_id` = :user_id AND `type` = 1 LIMIT 1");
$upd_tasks->execute(array(':quatity' => $tasks['quatity']+1, ':user_id' => $user['id']));
$_SESSION['ok'] = '<b>Охота</b>: <img src="/style/images/many/gold.png">3 <img src="/style/images/many/silver.png">5000 <img src="/style/images/user/exp.png">3500';
}
}
}

#-Обучение-#
if($user['start'] == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `start` = 2 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $user['id']));
}

$del_hunting_b = $pdo->prepare("DELETE FROM `hunting_battle` WHERE `id` = :id");
$del_hunting_b->execute(array(':id' => $battle['id']));	
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `key` = :key, `crystal` = :crystal, `gold` = :gold, `hunting_pobeda` = :hunting_pobeda, `tasks` = :tasks WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 0, ':exp' => $user['exp']+$exp+$exp_tasks, ':exp_clan' => $exp+$exp_tasks, ':silver' => $user['silver']+$silver+$silver_tasks, ':gold' => $user['gold']+$gold_tasks, ':key' => $user['key']+$key, ':crystal' => $user['crystal']+$crystal, ':hunting_pobeda' => $user['hunting_pobeda']+1, ':tasks' => $user['tasks']+$tasks_q, ':id' => $user['id']));	
header('Location: /start_hunting?act=battle&loc='.$hunting['id'].'');
$_SESSION['notif'] = "<center><div class='pobeda'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Победа <img src='/style/images/body/league.png' alt='Мечи'/></span></div><img src='/style/images/user/exp.png' alt='Звезда'/>$exp_no_p$exp <img src='/style/images/many/silver.png' alt='Монеты'/>$silver_no_p$silver $crystal_inf $key_i <br/>Боев: $batt из 10</center>";
exit();	
}else{
#-ПОРАЖЕНИЕ-#
if($monstr_uron >= $battle['user_t_health']){
$del_hunting_b = $pdo->prepare("DELETE FROM `hunting_battle` WHERE `id` = :id");
$del_hunting_b->execute(array(':id' => $battle['id']));	
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `exp` = :exp, `exp_clan` = :exp_clan, `key` = :key, `hunting_progrash` = :hunting_progrash WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 0, ':exp' => $user['exp']+$exp, ':exp_clan' => $exp, ':key' => $user['key']+$key, ':hunting_progrash' => $user['hunting_progrash']+1, ':id' => $user['id']));	
header('Location: /start_hunting?act=battle&loc='.$hunting['id'].'');
$_SESSION['notif'] = "<center><div class='porashenie'><span class='line_notif'><img src='/style/images/body/league.png' alt='Мечи'/> Поражение <img src='/style/images/body/league.png' alt=''/></span></div><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp $key_i<br/>Боев: $batt из 10</center>";
exit();	
}else{
#-ПРОДОЛЖЕНИЕ БОЯ-#
$upd_hunting_b = $pdo->prepare("UPDATE `hunting_battle` SET `monstr_health` = :monstr_health, `user_health` = :user_health, `monstr_t_health` = :monstr_t_health, `user_t_health` = :user_t_health, `monstr_t_health` = :monstr_t_health  WHERE `id` = :id LIMIT 1");
$upd_hunting_b->execute(array(':monstr_health' => $battle['monstr_t_health'], ':user_health' => $battle['user_t_health'],  ':user_t_health' => $user_health, ':monstr_t_health' => $monstr_health, ':id' => $battle['id']));		
header('Location: /hunting_battle');
$_SESSION['notif'] = '<img src="/style/images/body/attack.png"/> <span class="green">Вы</span> нанесли '.$user_uron.' урона<br/><img src="/style/images/body/attack.png"/> <span class="red">'.$monsters['name'].'</span> нанёс вам '.$monstr_uron.' урона';
exit();	
}
}
}else{
header('Location: /select_location');
}
}
?>