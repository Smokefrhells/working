<?php
require_once 'system.php';
                                                               #-ГЕРОЙ-#
//Время праздников
if($user['celebration_time'] != 0){
$upd_celebration = $pdo->prepare("UPDATE `users` SET `celebration_time` = 0 WHERE `celebration_time` < :time AND `id` = :user_id");
$upd_celebration->execute(array(':time' => time(), ':user_id' => $user['id']));
}															  
//Обменник
if($user['obmenik_time'] != 0){
$upd_obmenik = $pdo->prepare("UPDATE `users` SET `obmenik_time` = 0 WHERE `obmenik_time` < :time AND `id` = :user_id");
$upd_obmenik->execute(array(':time' => time(), ':user_id' => $user['id']));
}
//Премиум
if($user['premium'] != 0){
$upd_premium = $pdo->prepare("UPDATE `users` SET `premium` = 0, `premium_time` = 0 WHERE `premium_time` < :time AND (`premium` = 1 OR `premium` = 2) AND `id` = :user_id");
$upd_premium->execute(array(':time' => time(), ':user_id' => $user['id']));
}
//Блок
if($user['block'] != 0){
$upd_block = $pdo->prepare("UPDATE `users` SET `block` = 0 WHERE `block` < :time AND `id` = :user_id");
$upd_block->execute(array(':time' => time(), ':user_id' => $user['id']));
}
//Бан
if($user['ban'] != 0){
$upd_ban = $pdo->prepare("UPDATE `users` SET `ban` = 0 WHERE `ban` < :time AND `id` = :user_id");
$upd_ban->execute(array(':time' => time(), ':user_id' => $user['id']));
}
//Бонус
if(isset($user['bonus'])){
$upd_bonus = $pdo->prepare("UPDATE `users` SET `bonus` = 0 WHERE `bonus` < :time AND `id` = :user_id");
$upd_bonus->execute(array(':time' => time(), ':user_id' => $user['id']));
}
//Создание следующего топика
if($user['topic_time'] != 0){
$upd_topic = $pdo->prepare("UPDATE `users` SET `topic_time` = 0 WHERE `topic_time` < :time AND `id` = :user_id");
$upd_topic->execute(array(':time' => time(), ':user_id' => $user['id']));
}
//Бонус параметров
if($user['sila_time'] != 0){
$upd_sila = $pdo->prepare("UPDATE `users` SET `sila_time` = 0, `sila_bonus` = 0 WHERE `sila_time` < :time AND `id` = :user_id");
$upd_sila->execute(array(':time' => time(), ':user_id' => $user['id']));
}
if($user['zashita_time'] != 0){
$upd_zashita = $pdo->prepare("UPDATE `users` SET `zashita_time` = 0, `zashita_bonus` = 0 WHERE `zashita_time` < :time AND `id` = :user_id");
$upd_zashita->execute(array(':time' => time(), ':user_id' => $user['id']));
}
if($user['health_time'] != 0){
$upd_health = $pdo->prepare("UPDATE `users` SET `health_time` = 0, `health_bonus` = 0 WHERE `health_time` < :time AND `id` = :user_id");
$upd_health->execute(array(':time' => time(), ':user_id' => $user['id']));
}

#-Акция и скидки-#
$del_stock = $pdo->prepare("DELETE FROM `stock` WHERE `time` < :time");
$del_stock->execute(array(':time' => time()));

#-Время боссов-#
$del_boss_t = $pdo->prepare("DELETE FROM `boss_time` WHERE `user_id` = :user_id AND `type` = :type AND `time` < :time");
$del_boss_t->execute(array(':user_id' => $user['id'], ':type' => 2, ':time' => time()));

                                                             #-ДУЭЛИ-#
#-Время отдыха дуэлей-#
$sel_duel_t = $pdo->prepare("SELECT * FROM `duel_time` WHERE `duel_time` < :time AND `user_id` = :user_id");
$sel_duel_t->execute(array(':time' => time(), ':user_id' => $user['id']));

$sql = "SELECT count(*) FROM `duel_time` WHERE `duel_time` < :time AND `user_id` = :user_id"; 
$result = $pdo->prepare($sql); 
$result->execute(array(':time' => time(), ':user_id' => $user['id']));
$number_of_rows = $result->fetchColumn();
//echo($number_of_rows.'l');
if($number_of_rows != 0){
$duel_t = $sel_duel_t->fetch(PDO::FETCH_LAZY);
#-Удаление записей-#
//echo('l');
$upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = 0 WHERE `id` = :id");
$upd_users->execute(array(':id' => $user['id']));
$del_duel_t = $pdo->prepare("DELETE FROM `duel_time` WHERE `id` = :id");
$del_duel_t->execute(array(':id' => $duel_t['id']));
}
#-Очередь дуэлей-#
$del_duel_on = $pdo->prepare("DELETE FROM `duel_online` WHERE `time` < :time AND `statys` = 0 AND (`user_id` = :user_id OR `ank_id` = :user_id)");
$del_duel_on->execute(array(':time' => time(), ':user_id' => $user['id']));
#-Задержка дуэлей-#
$del_duel_delay = $pdo->prepare("DELETE FROM `duel_delay` WHERE `time` < :time AND `user_id` = :user_id");
$del_duel_delay->execute(array(':time' => time(), ':user_id' => $user['id']));

                                                             #-КЛАНЫ-#
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_time` = 0 WHERE `clan_time` < :time AND `id` = :user_id");
$upd_users->execute(array(':time' => time(), ':user_id' => $user['id']));
$upd_clan_g = $pdo->prepare("UPDATE `clan_users` SET `gold_time` = 0, `gold_t` = 0 WHERE `gold_time` < :time AND `gold_time` != 0 AND `user_id` = :user_id");
$upd_clan_g->execute(array(':time' => time(), ':user_id' => $user['id']));
$upd_clan_s = $pdo->prepare("UPDATE `clan_users` SET `silver_time` = 0, `silver_t` = 0 WHERE `silver_time` < :time AND `silver_time` != 0 AND `user_id` = :user_id");
$upd_clan_s->execute(array(':time' => time(), ':user_id' => $user['id']));

                                                             #-ОХОТА-#
#-Время окончания охоты-#
$sel_hunting_t = $pdo->prepare("SELECT * FROM `hunting_time` WHERE `hunting_time` < :time AND `user_id` = :user_id");
$sel_hunting_t->execute(array(':time' => time(), ':user_id' => $user['id']));
if($sel_hunting_t->rowCount() != 0){
$hunting_time = $sel_hunting_t->fetch(PDO::FETCH_LAZY);
if($hunting_time['hunting_time'] < time()){
if($hunting_time['hunting_id'] == 1){
$hunting_t = 'hunting_1';
}
if($hunting_time['hunting_id'] == 2){
$hunting_t = 'hunting_2';
}
if($hunting_time['hunting_id'] == 3){
$hunting_t = 'hunting_3';
}
if($hunting_time['hunting_id'] == 4){
$hunting_t = 'hunting_4';
}
if($hunting_time['hunting_id'] == 5){
$hunting_t = 'hunting_5';
}
if($hunting_time['hunting_id'] == 6){
$hunting_t = 'hunting_6';
}
if($hunting_time['hunting_id'] == 7){
$hunting_t = 'hunting_7';
}
$upd_users = $pdo->prepare("UPDATE `users` SET `$hunting_t` = :hunting WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':hunting' => 0, ':id' => $user['id']));
$del_hunting_t = $pdo->prepare("DELETE FROM `hunting_time` WHERE `id` = :id AND `user_id` = :user_id");
$del_hunting_t->execute(array(':id' => $hunting_time['id'], ':user_id' => $user['id']));
}
}

#-Состоим ли в бою за локацию-#
$sel_hunting_u1 = $pdo->prepare("SELECT `id`, `user_id`, `location` FROM `hunting_battle_u` WHERE `user_id` = :user_id");
$sel_hunting_u1 ->execute(array(':user_id' => $user['id']));
if($sel_hunting_u1->rowCount() != 0){
$hunting_u1 = $sel_hunting_u1->fetch(PDO::FETCH_LAZY);
#-Данные локации-#
$sel_hunting_z2 = $pdo->prepare("SELECT * FROM `hunting` WHERE `id` = :location AND `statys_battle` = 0 AND `level`+14 < :user_level");
$sel_hunting_z2 ->execute(array(':location' => $hunting_u1['location'], ':user_level' => $user['level']));
if($sel_hunting_z2->rowCount() != 0){
#-Удаляем бой-#
$del_hunting_u = $pdo->prepare("DELETE FROM `hunting_battle_u` WHERE `location` = :location AND `user_id` = :user_id");
$del_hunting_u->execute(array(':location' => $hunting_u1['location'], ':user_id' => $user['id']));
}
}

#-Есть ли локации и сравниваем уровни-#
$sel_hunting_z1 = $pdo->query("SELECT `id`, `user_id`, `level`, `statys_battle` FROM `hunting` WHERE `user_id` != 0");
if($sel_hunting_z1->rowCount() != 0){
while($hunting_z1 = $sel_hunting_z1->fetch(PDO::FETCH_LAZY)){
#-Выборка данных игрока-#
$sel_u_param = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
$sel_u_param->execute(array(':user_id' => $hunting_z1['user_id']));
$u = $sel_u_param->fetch(PDO::FETCH_LAZY);
#-Если уровень больше тогда убираем локацию-#
if($u['level'] > $hunting_z1['level']+14 and $hunting_z1['statys_battle'] == 0){
#-Отнимаем параметры-#
$upd_users_v = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
$upd_users_v->execute(array(':s_sila' => $u['s_sila']-params($hunting_z1['id']), ':s_zashita' => $u['s_zashita']-params($hunting_z1['id']), ':s_health' => $u['s_health']-params($hunting_z1['id']), ':id' => $u['id']));
#-Убираем локацию из захваченных-#
$upd_hunting_z1 = $pdo->prepare("UPDATE `hunting` SET `user_id` = 0, `time_battle` = 0 WHERE `id` = :id");
$upd_hunting_z1->execute(array(':id' => $hunting_z1['id']));
#-Удаляем бой-#
$del_hunting_u = $pdo->prepare("DELETE FROM `hunting_battle_u` WHERE `location` = :location");
$del_hunting_u->execute(array(':location' => $hunting_z1['id']));
}
}
}     



#-Удаление боя в пирамиде-#    
$sel_pyramid_del = $pdo->prepare("SELECT * FROM `pyramid_battle_b` WHERE `time` < :time");
$sel_pyramid_del->execute(array(':time' => time()));
if($sel_pyramid_del->rowCount() != 0){
$del_pyramid_b = $pdo->query("DELETE FROM `pyramid_battle_b`");
$del_pyramid_l = $pdo->query("DELETE FROM `pyramid_battle_l`");
$del_pyramid_u = $pdo->query("DELETE FROM `pyramid_battle_u`");
}
#-Заморозка-#
$upd_pyramid_zamor = $pdo->prepare("UPDATE `pyramid_battle_u` SET `zamor` = 0 WHERE `zamor` < :time");
$upd_pyramid_zamor->execute(array(':time' => time()));                                                                                                           
?>