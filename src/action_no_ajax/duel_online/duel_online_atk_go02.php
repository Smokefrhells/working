<?php
require_once '../../system/system.php';
echo only_reg();
#-Атака в онлайн дуэли-#
switch($act){
case 'attc':
if($user['level'] >= 5){
#-Время задержки-#
$t = ((int)(time()-$_SESSION["telecod_ip"]));
if ($t < 1){
header('Location: /duel_online');
$_SESSION['err'] = 'Слишком часто!';
exit();	
}
$_SESSION["telecod_ip"]=time();
#-Выборка данных о бое-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() != 0){
$duel = $sel_duel->fetch(PDO::FETCH_LAZY);
#-Определяем id врага-#
if($duel['ank_id'] == $user['id']){
$user_id = $duel['ank_id'];
$ank_id = $duel['user_id'];
$ank_health = $duel['user_health'];
$ank_t_health = $duel['user_t_health'];
$user_health = $duel['ank_health'];
$user_t_health = $duel['ank_t_health'];
}else{
$user_id = $duel['user_id'];
$user_health = $duel['user_health'];
$user_t_health = $duel['user_t_health'];
$ank_health = $duel['ank_health'];
$ank_t_health = $duel['ank_t_health'];
$ank_id = $duel['ank_id'];
}
#-Оппонент-#
$sel_users_op = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users_op->execute(array(':id' => $ank_id));
$opponent = $sel_users_op->fetch(PDO::FETCH_LAZY);
#-Наш герой-#
$sel_users_me = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users_me->execute(array(':id' => $user_id));
$me = $sel_users_me->fetch(PDO::FETCH_LAZY);
#-Урон игрока-#
$v_user_uron = ((($me['sila']+$me['s_sila']+$me['sila_bonus']) * 0.5) * 10);
$v_ank_zashita = ((($opponent['zashita']+$opponent['s_zashita']+$opponent['zashita_bonus']) * 0.2) * 10);
if($v_user_uron > $v_ank_zashita){
$r_user_uron_1 = ($v_user_uron - $v_ank_zashita);
$r_user_uron_2 = ($v_user_uron - ($v_ank_zashita / 2));
}else{
$r_user_uron_1 = ($v_user_uron / 5);
$r_user_uron_2 = ($v_user_uron / 4);
}
$user_uron = rand($r_user_uron_1, $r_user_uron_2);
$user_uron = round($user_uron, 0);


#-Награда за бой-#
#-Опыт-#
$exp_no = rand((($opponent['level']+50)*30), (($opponent['level']+150)*60)); //Победа
#-Серебро-#
$n1 = (($opponent['level']*100)+800);
$n2 = (($opponent['level']*150)+100);
$silver_no = rand($n1, $n2);
#-Серебро и опыт за победу-#
if($user['premium'] == 0){$exp_me = $exp_no;}
if($user['premium'] == 1){$exp_me = $exp_no * 2;}
if($user['premium'] == 2){$exp_me = $exp_no * 3;}
if($user['premium'] == 1 or $user['premium'] == 2){
$exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
}	

if($user['premium'] == 0){$silver_me = $silver_no;}
if($user['premium'] == 1){$silver_me = round((($silver_no * 0.25) + $silver_no), 0);}
if($user['premium'] == 2){$silver_me = round((($silver_no * 0.50) + $silver_no), 0);}
if($user['premium'] == 1 or $user['premium'] == 2){
$silver_no_p = "<span class='white'><strike>$silver_no</strike></span> "; //Серебро без премиума	
}

#-Опыт за поражение-#
$exp_p_no = rand((($user['level']+30)*15), (($user['level']+60)*50)); 
if($opponent['premium'] == 0){$exp_opp = $exp_p_no;}
if($opponent['premium'] == 1){$exp_opp = $exp_p_no * 2;}
if($opponent['premium'] == 2){$exp_opp = $exp_p_no * 3;}
if($opponent['premium'] == 1 or $opponent['premium'] == 2){
$exp_prog_no_p = "<span class='white'><strike>$exp_p_no</strike></span> "; //Опыт без премиума
}	

#-Урон-#
$health_uron = $ank_t_health - $user_uron;
#-Заносим результаты в базу-#
                                                                        #-ПОБЕДА-#
if($user_uron >= $ank_t_health){
#-Ключи нашего игрока-#
$rand_me = rand(0, 100);
if($rand_me > 96){
$key_me = 1;
$key_i_me = "<img src='/style/images/body/key.png' alt=''/>1";
}else{
$key_me = 0;
}
#-Ключи оппонента-#
$rand_opp = rand(0, 100);
if($rand_opp > 96){
$key_opp = 1;
$key_i_opp = "<img src='/style/images/body/key.png' alt=''/>1";
}else{
$key_opp = 0;
}

#-ЕЖЕДНЕВНЫЕ ЗАДАНИЯ ДУЭЛИ-#
$sel_tasks = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 3");
$sel_tasks->execute(array(':user_id' => $user['id']));
if($sel_tasks-> rowCount() != 0){
$tasks = $sel_tasks->fetch(PDO::FETCH_LAZY);
#-Если еще не выполнено-#
if($tasks['quatity'] < 9){
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity WHERE `id` = :id LIMIT 1");
$upd_tasks->execute(array(':quatity' => $tasks['quatity']+1, ':id' => $tasks['id']));	
}else{
if($tasks['statys'] == 0){
$exp_tasks = 6500;
$silver_tasks = 12000;
$gold_tasks = 10;
$tasks_q = 1;
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity, `statys` = 1 WHERE `user_id` = :user_id AND `type` = 3 LIMIT 1");
$upd_tasks->execute(array(':quatity' => $tasks['quatity']+1, ':user_id' => $user['id']));
$_SESSION['ok'] = '<b>Дуэли онлайн</b>: <img src="/style/images/many/gold.png" alt=""/>10 <img src="/style/images/many/silver.png" alt=""/>12000 <img src="/style/images/user/exp.png" alt=""/>6500';
}
}
}

#-Удаляем дуэли-#
$del_duel = $pdo->prepare("DELETE FROM `duel_online` WHERE `id` = :id");
$del_duel->execute(array(':id' => $duel['id']));	
#-Удаляем лог дуэлей-#
$del_duel_l = $pdo->prepare("DELETE FROM `duel_log` WHERE `duel_id` = :duel_id");
$del_duel_l->execute(array(':duel_id' => $duel['id']));
#-Получает награду герой-#
$upd_users_me = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `gold` = :gold, `duel_pobeda` = :duel_pobeda, `duel_t` = :duel_t, `key` = :key, `tasks` = :tasks WHERE `id` = :user_id LIMIT 1");
$upd_users_me->execute(array(':battle' => 0, ':exp' => $me['exp']+$exp_me+$exp_tasks, ':exp_clan' => $exp_me+$exp_tasks, ':silver' => $me['silver']+$silver_me+$silver_tasks, ':gold' => $user['gold']+$gold_tasks, ':duel_pobeda' => $me['duel_pobeda']+1, ':duel_t' => $me['duel_t']+1, ':key' => $me['key']+$key_me, ':tasks' => $me['tasks']+$tasks_q, ':user_id' => $me['id']));	
#-Лог-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event ->execute(array(':type' => 4, ':log' => "<span style='color: #00a800;'>Победа</span> в бою с <a href='/hero/$opponent[id]' style='display:inline;text-decoration:underline;padding:0px;'>$opponent[nick]</a><br/><img src='/style/images/body/gift.png' alt=''/>Ваша награда: <span style='color: #ecbc7d;'><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_me <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver_me $key_i_me</span>", ':user_id' => $user_id, ':time' => time())); 
#-Получает награду оппонент-#
$upd_users_op = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `exp` = :exp, `exp_clan` = :exp_clan, `duel_progrash` = :duel_progrash, `key` = :key WHERE `id` = :ank_id LIMIT 1");
$upd_users_op->execute(array(':battle' => 0, ':exp' => $opponent['exp']+$exp_progrash, ':exp_clan' => $exp_progrash, ':duel_progrash' => $opponent['duel_progrash']+1, ':key' => $opponent['key']+$key_opp, ':ank_id' => $opponent['id']));	
#-Лог-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event ->execute(array(':type' => 4, ':log' => "<span style='color: #ff0000;'>Поражение</span> в бою с <a href='/hero/$me[id]' style='display:inline;text-decoration:underline;padding:0px;'>$me[nick]</a><br/><img src='/style/images/body/gift.png' alt=''/>Ваша награда: <span style='color: #ecbc7d;'><img src='/style/images/user/exp.png' alt=''/>$exp_prog_no_p$exp_opp $key_i_opp</span>", ':user_id' => $ank_id, ':time' => time())); 
#-Считаем бои нашего героя-#
$me_level = floor($me['level']/2);
$duel_lvl_1 = $me_level - 1;
if($me['duel_b'] < $duel_lvl_1){
$upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = :duel_b WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':duel_b' => $me['duel_b']+1, ':id' => $me['id']));	
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = :duel_b WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':duel_b' => $me['duel_b']+1, ':id' => $me['id']));	
$ins_duel_t = $pdo->prepare("INSERT INTO `duel_time` SET `duel_time` = :duel_time, `user_id` = :user_id");
$ins_duel_t->execute(array(':duel_time' => time()+1800, ':user_id' => $me['id']));
}
#-Считаем бои оппонента-#
$opponent_level = floor($opponent['level']/2);
$duel_lvl_2 = $opponent_level - 1;
if($opponent['duel_b'] < $duel_lvl_2){
$upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = :duel_b WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':duel_b' => $opponent['duel_b']+1, ':id' => $opponent['id']));	
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = :duel_b WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':duel_b' => $opponent['duel_b']+1, ':id' => $opponent['id']));		
$ins_duel_t = $pdo->prepare("INSERT INTO `duel_time` SET `duel_time` = :duel_time, `user_id` = :user_id");
$ins_duel_t->execute(array(':duel_time' => time()+1800, ':user_id' => $opponent['id']));
}
header('Location: /duel');
$_SESSION['notif'] = "<center><div class='pobeda'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Победа <img src='/style/images/body/league.png' alt=''/></span></div><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_me <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver_me $key_i_me</center>";
exit();	
}else{
                                                             #-ПРОДОЛЖЕНИЕ БОЯ-#
if($duel['ank_id'] == $user['id']){
$upd_duel = $pdo->prepare("UPDATE `duel_online` SET `user_health` = :user_health, `user_t_health` = :user_t_health  WHERE `id` = :id LIMIT 1");
$upd_duel->execute(array(':user_health' => $duel['user_t_health'], ':user_t_health' => $health_uron, ':id' => $duel['id']));		
}else{
$upd_duel = $pdo->prepare("UPDATE `duel_online` SET `ank_health` = :ank_health, `ank_t_health` = :ank_t_health  WHERE `id` = :id LIMIT 1");
$upd_duel->execute(array(':ank_health' => $duel['ank_t_health'], ':ank_t_health' => $health_uron, ':id' => $duel['id']));		
}
#-Записываем лог дуэли-#
if($me['pol'] == 1){$at = 'нанёс';}else{$at = 'нанесла';}
$ins_log = $pdo->prepare("INSERT INTO `duel_log` SET `duel_id` = :duel_id, `user_id` = :user_id, `log` = :log, `time` = :time");
$ins_log->execute(array(':duel_id' => $duel['id'], ':user_id' => $me['id'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> $me[nick] $at $user_uron урона", ':time' => time()));		
header('Location: /duel_online');
}
}else{
header('Location: /duel_online');
}
}else{
header('Location: /duel');
}
}
?>