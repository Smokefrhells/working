<?php
require_once '../../../system/system.php';
echo only_reg();
echo pets_level();

#-Атака питомцев-#
switch($act){
case 'attc':
#-Данные боя питомца игрока-#
$sel_pets_d_me = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `user_id` = :user_id AND `statys` = 2");
$sel_pets_d_me->execute(array(':user_id' => $user['id']));
if($sel_pets_d_me->rowCount() == 0) $error = 'Бой не найден!';
$pets_d_me = $sel_pets_d_me->fetch(PDO::FETCH_LAZY);
#-Данные боя питомца врага-#
$sel_pets_d_ank = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `battle_id` = :battle_id AND `user_id` != :user_id AND `statys` = 2");
$sel_pets_d_ank->execute(array(':battle_id' => $pets_d_me['battle_id'], ':user_id' => $user['id']));
if($sel_pets_d_ank->rowCount() == 0) $error = 'Ошибка данных оппонента!';
$pets_d_ank = $sel_pets_d_ank->fetch(PDO::FETCH_LAZY);
#-Время задержки-#
$t = ((int)(time()-$_SESSION["telecod_ip"]));
if($t < 2){
header('Location: /pets_duel');
$_SESSION['err'] = 'Промах!';
exit();	
}
$_SESSION["telecod_ip"]=time();

#-Нет ошибок-#
if(!isset($error)){

#-Активный питомец игрока-#
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1 AND `battle` = 1");
$sel_pets_me->execute(array(':user_id' => $user['id']));
$pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);	
#-Данные питомца игрока-#	
$sel_pets_u = $pdo->prepare("SELECT `id`, `name`, `sila`, `zashita`, `health` FROM `pets` WHERE `id` = :id");
$sel_pets_u->execute(array(':id' => $pets_me['pets_id']));
$pets_u = $sel_pets_u->fetch(PDO::FETCH_LAZY);	
#-Активный питомец врага-#
$sel_pets_ank = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1 AND `battle` = 1");
$sel_pets_ank->execute(array(':user_id' => $pets_d_ank['user_id']));
$pets_ank = $sel_pets_ank->fetch(PDO::FETCH_LAZY);	
#-Данные питомца врага-#	
$sel_pets_a = $pdo->prepare("SELECT `id`, `name`, `sila`, `zashita`, `health` FROM `pets` WHERE `id` = :id");
$sel_pets_a->execute(array(':id' => $pets_ank['pets_id']));
$pets_a = $sel_pets_a->fetch(PDO::FETCH_LAZY);	

#-Способности питомца игрока-#
if($pets_me['pets_id'] == 1 or $pets_me['pets_id'] == 3 or $pets_me['pets_id'] == 5){
$prosent = rand(0, 100);
#-Сработала способность или нет-#	
if($prosent <= $pets_me['abi_prosent']){
#-Мышь (Везение)-#
if($pets_me['pets_id'] == 1){
$abi_thief = 1;
}
#-Лев (Крит)-#
if($pets_me['pets_id'] == 3){
$abi_crete = 1;	
}
#-Гелаус (Вампиризм)-#	
if($pets_me['pets_id'] == 5){
$abi_treatment = 1;
}	
}else{
$prosent = 0;
$abi_thief = 0;
$abi_crete = 0;	
$abi_treatment = 0;
}	
}

#-Способности питомца врага-#
if($pets_ank['pets_id'] == 2 or $pets_ank['pets_id'] == 4){
$prosent = rand(0, 100);
#-Сработала способность или нет-#	
if($prosent <= $pets_ank['abi_prosent']){
#-Волк (Поглощение)-#
if($pets_ank['pets_id'] == 2){
$abi_absorb = 1;		
}
#-Змееглав (Уворот)-#
if($pets_ank['pets_id'] == 4){
$abi_dodge = 1;		
}
}else{
$prosent = 0;
$abi_absorb = 0;
$abi_dodge = 0;
}	
}

#-Урон питомца игрока-#
$v_pets_uron_1 = ((($pets_me['b_param']+$pets_u['sila']) * 0.2));
$v_pets_uron_2 = ((($pets_me['b_param']+$pets_u['sila']) * 0.25));
$v_pets_uron = rand($v_pets_uron_1, $v_pets_uron_2);

$v_ank_zashita_1 = ((($pets_ank['b_param']+$pets_a['zashita']) * 0.05));
$v_ank_zashita_2 = ((($pets_ank['b_param']+$pets_a['zashita']) * 0.06));
$v_ank_zashita = rand($v_ank_zashita_1, $v_ank_zashits_2);

if($v_pets_uron > $v_ank_zashita){
$r_pets_uron_1 = $v_pets_uron - $v_ank_zashita;
}else{
$v_1 = 	($v_pets_uron/2);
$v_2 = 	($v_pets_uron/2.5);
$r_pets_uron_1 = rand($v_1, $v_2);
}
$pets_uron = round($r_pets_uron_1, 0);
#-Крит-#
if($abi_crete == 1){$pets_uron = $pets_uron * 2;}
#-Поглощение-#
if($abi_absorb == 1){$pets_d_uron = $pets_uron;$pets_uron = round($pets_uron/2, 0);}
#-Уворот-#
if($abi_dodge == 1){$pets_uron = 0;}

#-Здоровье питомца врага после атаки-#
$health_uron = $pets_d_ank['pets_t_health']- $pets_uron;

#-Опыт во время боя-#
$exp_no = rand((50*($pets_me['pets_id']+8)), (75*($pets_me['pets_id']+12)));
if($user['premium'] == 0){$exp_me = $exp_no;}
if($user['premium'] == 1){$exp_me = $exp_no * 2;}
if($user['premium'] == 2){$exp_me = $exp_no * 3;}
#-Везение-#
if($abi_thief == 1){$exp_me = '<u>'.($exp_me * 2).'</u>';}

                                                                  #-ПОБЕДА-#
if($pets_uron >= $pets_d_ank['pets_t_health']){

#-Выборка боя-#
$sel_pets_duel = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `battle_id` = :battle_id AND `statys` = 2");
$sel_pets_duel->execute(array(':battle_id' => $pets_d_me['battle_id']));
while($pets_duel = $sel_pets_duel->fetch(PDO::FETCH_LAZY)){
#-Статус не в бою-#	
$upd_pets_me = $pdo->prepare("UPDATE `pets_me` SET `battle` = 0 WHERE `user_id` = :user_id");	
$upd_pets_me->execute(array(':user_id' => $pets_duel['user_id']));	

if($pets_duel['user_id'] == $user['id']){
#-Победитель-#
$upd_users = $pdo->prepare("UPDATE `users` SET `pets_pobeda` = `pets_pobeda`+1, `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+$user['pets_rang'], ':user_id' => $pets_duel['user_id']));
}else{
#-Проигравший-#
$upd_users = $pdo->prepare("UPDATE `users` SET `pets_progrash` = `pets_progrash`+1 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $pets_duel['user_id']));
}
}
#-Удаление лога-#
$del_pets_log = $pdo->prepare("DELETE FROM `pets_duel_log` WHERE `battle_id` = :battle_id");
$del_pets_log->execute(array(':battle_id' => $pets_d_me['battle_id']));
#-Удаление боя-#
$del_pets_duel = $pdo->prepare("DELETE FROM `pets_duel` WHERE `battle_id` = :battle_id");
$del_pets_duel->execute(array(':battle_id' => $pets_d_me['battle_id']));
header('Location: /pets_duel');
$_SESSION['ok'] = "Победа: <img src='/style/images/many/gold.png' alt=''/>+$user[pets_rang]";
exit();
}else{
	
#-Зачисление опыта и серебра-#
$upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':exp' => $user['exp']+$exp_me, ':user_id' => $user['id']));
#-Минус здоровья-#	
$upd_pets_d = $pdo->prepare("UPDATE `pets_duel` SET `pets_uron` = :pets_uron, `pets_t_health` = :pets_t_health WHERE `id` = :id LIMIT 1");
$upd_pets_d->execute(array(':pets_uron' => $pets_uron, ':pets_t_health' => $health_uron, ':id' => $pets_d_ank['id']));
#-Лог обычного удара-#
if($abi_crete == 0 and $abi_absorb == 0 and $abi_dodge == 0 and $abi_treatment == 0){
$ins_log = $pdo->prepare("INSERT INTO `pets_duel_log` SET `log` = :log, `battle_id` = :battle_id, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/body/attack.png' alt=''/> $pets_u[name] нанес(ла) $pets_uron урона <img src='/style/images/user/exp.png' alt=''/>+$exp_me", ':battle_id' => $pets_d_me['battle_id'], ':user_id' => $user['id'], ':time' => time()));	
}

#-Лог способности Везение-#
if($abi_thief == 1){
$ins_log = $pdo->prepare("INSERT INTO `pets_duel_log` SET `log` = :log, `battle_id` = :battle_id, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/pets/ability/thief.gif' alt=''/> $pets_u[name] удвоила награду", ':battle_id' => $pets_d_me['battle_id'], ':user_id' => $user['id'], ':time' => time()));	
}

#-Лог способности Поглощение-#
if($abi_absorb == 1){
$ins_log = $pdo->prepare("INSERT INTO `pets_duel_log` SET `log` = :log, `battle_id` = :battle_id, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/pets/ability/absorb.gif' alt=''/> $pets_u[name] нанес <u>$pets_uron</u> из $pets_d_uron урона <img src='/style/images/user/exp.png' alt=''/>+$exp_me", ':battle_id' => $pets_d_me['battle_id'], ':user_id' => $user['id'], ':time' => time()));
}

#-Лог способности Крит-#
if($abi_crete == 1){
$ins_log = $pdo->prepare("INSERT INTO `pets_duel_log` SET `log` = :log, `battle_id` = :battle_id, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/pets/ability/crete.gif' alt=''/> $pets_u[name] нанес <u>$pets_uron</u> урона <img src='/style/images/user/exp.png' alt=''/>+$exp_me", ':battle_id' => $pets_d_me['battle_id'], ':user_id' => $user['id'], ':time' => time()));
}

#-Лог способности Уворот-#
if($abi_dodge == 1){
$ins_log = $pdo->prepare("INSERT INTO `pets_duel_log` SET `log` = :log, `battle_id` = :battle_id, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/pets/ability/dodge.gif' alt=''/> $pets_a[name] увернулся", ':battle_id' => $pets_d_me['battle_id'], ':user_id' => $pets_d_ank['user_id'], ':time' => time()));
}

#-Лог способности Вампиризм-#
if($abi_treatment == 1){
$abi_health = round(($pets_me['b_param']+$pets_u['health']) * 0.30, 0);
#-Добавление здоровья-#
if(($abi_health+$pets_d_me['pets_t_health']) <= ($pets_me['b_param']+$pets_u['health'])){
$max_health = ($pets_d_me['pets_t_health']+$abi_health);
$log_health = $abi_health;
}else{
$max_health = ($pets_me['b_param']+$pets_u['health']);
$log_health = 'Max';
}
$upd_pets_d = $pdo->prepare("UPDATE `pets_duel` SET `pets_t_health` = :pets_t_health WHERE `id` = :id LIMIT 1");
$upd_pets_d->execute(array(':pets_t_health' => $max_health, ':id' => $pets_d_me['id']));	
#-Запись лога-#
$ins_log = $pdo->prepare("INSERT INTO `pets_duel_log` SET `log` = :log, `battle_id` = :battle_id, `user_id` = :user_id, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/pets/ability/treatment.gif' alt=''/> $pets_u[name] восстановил <u>$log_health</u> здоровья", ':battle_id' => $pets_d_me['battle_id'], ':user_id' => $user['id'], ':time' => time()));
}
header('Location: /pets_duel');
}
}else{
header('Location: /pets_duel');
$_SESSION['err'] = $error;
exit();
}
}
?>