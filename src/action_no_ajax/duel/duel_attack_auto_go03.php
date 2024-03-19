<?php
require_once '../../system/system.php';
echo only_reg();
echo duel_level();
#-Автоматическая атака дуэли-#
switch($act){
case 'attc':
#-Минимальный и максимальный уровень-#
$min_level = $user['level'] - 5;
$max_level = $user['level'] + 5;	
#-Есть ли доступные бои-#
$user_duel = floor($user['level']/2);
$user_atk = $user_duel-$user['duel_b'];
if($user['duel_b'] > $user_duel) $error = 'У вас нет боёв!';
#-Достаточно золота-#
if($user['gold'] < $user_atk) $error = 'Недостаточно золота!';
#-Есть ли подходящие игроки-#
$sel_opponent = $pdo->prepare("SELECT `id`, `level`, `save` FROM `users` WHERE `level` > :min_level AND `level` < :max_level AND `id` != :user_id AND `save` = 1");
$sel_opponent->execute(array(':min_level' => $min_level, ':max_level' => $max_level, ':user_id' => $user['id']));
if($sel_opponent-> rowCount() < 3) $error = 'Мало игроков вашего уровня!';

#-Нет ошибок-#
if(!isset($error)){
#-Параметры игрока-#
$user_param = $user['sila']+$user['zashita']+$user['health']+$user['s_sila']+$user['s_zashita']+$user['s_health']+$user['sila_bonus']+$user['zashita_bonus']+$user['health_bonus'];

#-Повторение боёв-#
for($b=0; $b < $user_atk; $b++){
	
#-Выбираем игрока для дуэли-#
$sel_users = $pdo->prepare("SELECT `id`, `level`, `save`, `sila`, `zashita`, `health`, `s_sila`, `s_zashita`, `s_health`, `sila_bonus`, `zashita_bonus`, `health_bonus` FROM `users` WHERE `level` > :min_level AND `level` < :max_level AND `id` != :user_id AND `save` = 1 ORDER BY RAND()");
$sel_users->execute(array(':min_level' => $min_level, ':max_level' => $max_level, ':user_id' => $user['id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Параметры опонента-#
$ank_param = $all['sila']+$all['zashita']+$all['health']+$all['s_sila']+$all['s_zashita']+$all['s_health']+$all['sila_bonus']+$all['zashita_bonus']+$all['health_bonus'];

#-Опыт-#
$exp_no = rand((($all['level']+20)*30), (($all['level']+80)*60)); //Победа
if($user['premium'] == 0){$exp = $exp_no;}
if($user['premium'] == 1){$exp = $exp_no * 2;}
if($user['premium'] == 2){$exp = $exp_no * 3;}
	
                                                                    #-ПОБЕДА-#
if($user_param >= $ank_param){
#-Опыт-#
$exp_a_no = $exp_a_no+$exp_no;
$exp_a = $exp_a+$exp;
#-Серебро-#
$n1 = (($all['level']*20)+800);
$n2 = (($all['level']*100)+1000);
$silver_no = rand($n1, $n2);
if($user['premium'] == 0){$silver = $silver_no;}
if($user['premium'] == 1){$silver = round((($silver_no * 0.25) + $silver_no), 0);}
if($user['premium'] == 2){$silver = round((($silver_no * 0.50) + $silver_no), 0);}
#-Ключ-#
$rand = rand(0, 100);
if($rand > 97){
$key = 1;
}else{
$key = 0;
}
$silver_a_no = $silver_a_no+$silver_no;
$silver_a = $silver_a+$silver;
$pobeda_a = $pobeda_a+1;
$key_a = $key_a+$key;
}else{	                                                           #-ПОРАЖЕНИЕ-#	
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

#-ЕЖЕДНЕВНЫЕ ЗАДАНИЯ ДУЭЛИ-#
$sel_tasks = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 2");
$sel_tasks->execute(array(':user_id' => $user['id']));
if($sel_tasks-> rowCount() != 0){
$tasks = $sel_tasks->fetch(PDO::FETCH_LAZY);
$t_p = $tasks['quatity']+$pobeda_a;
#-Если еще не выполнено-#
if($t_p < 9){
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity WHERE `id` = :id LIMIT 1");
$upd_tasks->execute(array(':quatity' => $tasks['quatity']+$pobeda_a, ':id' => $tasks['id']));	
}else{
if($tasks['statys'] == 0){
$exp_tasks = 4500;
$silver_tasks = 8000;
$gold_tasks = 5;
$tasks_q = 1;
$upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity, `statys` = 1 WHERE `user_id` = :user_id AND `type` = 2 LIMIT 1");
$upd_tasks->execute(array(':quatity' => 10, ':user_id' => $user['id']));
$_SESSION['ok'] = '<b>Дуэли оффлайн</b>: <img src="/style/images/many/gold.png" alt=""/>5 <img src="/style/images/many/silver.png" alt=""/>8000 <img src="/style/images/user/exp.png" alt=""/>4500';
}
}
}

#-Зачисление награды-#
$upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `gold` = :gold, `key` = :key, `duel_pobeda` = :duel_pobeda, `duel_progrash` = :duel_progrash, `duel_t` = :duel_t, `duel_b` = :duel_b, `tasks` = :tasks WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':exp' => $user['exp']+$exp_a+$exp_tasks, ':exp_clan' => $exp_a+$exp_tasks, ':silver' => $user['silver']+$silver_a+$silver_tasks, ':gold' => ($user['gold']-$user_atk)+$gold_tasks, ':key' => $user['key']+$key_a, ':duel_pobeda' => $user['duel_pobeda']+$pobeda_a, ':duel_progrash' => $user['duel_progrash']+$progrash_a, ':duel_t' => $user['duel_t']+$pobeda_a, ':duel_b' => $user_duel, ':tasks' => $user['tasks']+$tasks_q, ':id' => $user['id']));	
#-Ставим время-#
$ins_duel_t = $pdo->prepare("INSERT INTO `duel_time` SET `duel_time` = :duel_time, `user_id` = :user_id");
$ins_duel_t->execute(array(':duel_time' => time()+1800, ':user_id' => $user['id']));

#-Опыт без премиума-#
if($user['premium'] == 1 or $user['premium'] == 2){
$exp_no_p = "<span class='white'><strike>$exp_a_no</strike></span> "; //Опыт без премиума
}
#-Серебро без премиума-#
if($user['premium'] == 1 or $user['premium'] == 2){
$silver_no_p = "<span class='white'><strike>$silver_a_no</strike></span> "; //Серебро без премиума	
}
header('Location: /duel');
$_SESSION['notif'] = "<center><img src='/style/images/body/ok.png' alt=''/>Побед: $pobeda_a <img src='/style/images/body/error.png' alt=''/>Поражений: $progrash_a<br/><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_a <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver_a <img src='/style/images/body/key.png' alt=''/>$key_a</center>";
}else{
header('Location: /duel_battle');
$_SESSION['err'] = $error;
exit();
}
}
?>