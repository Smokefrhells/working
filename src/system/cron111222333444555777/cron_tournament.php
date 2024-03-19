<?php
require_once '../system.php';
#-CRON задание Обновление каждое воскресенье в 12:00-#

#-Получение зарплаты для модераторов-#
$sel_moder = $pdo->query("SELECT `id`, `prava`, `gold` FROM `users` WHERE `prava` = 2");
if($sel_moder-> rowCount() != 0){
while($moder = $sel_moder->fetch(PDO::FETCH_LAZY)){
//Зачисляем золото
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :moder_id");
$upd_users->execute(array(':gold' => $moder['gold']+300, ':moder_id' => $moder['id']));
//Лог
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Благодарю за порядок в игре: <img src='/style/images/many/gold.png'>300", ':user_id' => $moder['id'], ':time' => time()));
}
}

#-Турнир Статуэтки-#
for($i = 1; $i <= 10; $i++){
$u = $i - 1;
$sel_users_t = $pdo->query("SELECT `id`, `level`, `figur`, `gold` FROM `users` WHERE `figur` > 0 ORDER BY `figur` DESC LIMIT $u, 1");
if($sel_users_t-> rowCount() != 0){
$users_t = $sel_users_t->fetch(PDO::FETCH_LAZY);
//1 место
if($i == 1) $gold = 800;
//2 место
if($i == 2) $gold = 700;
//3 место
if($i == 3) $gold = 600;
//4 место
if($i == 4) $gold = 500;
//5 место
if($i == 5) $gold = 400;
//6 место
if($i == 6) $gold = 350;
//7 место
if($i == 7) $gold = 300;
//8 место
if($i == 8) $gold = 250;
//9 место
if($i == 9) $gold = 200;
//10 место
if($i == 10) $gold = 100;
if(isset($gold)){
#-Зачисляем золото игрокам-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $users_t['gold']+$gold, ':id' => $users_t['id'])); 
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы заняли $i место в Турнире статуэток: <img src='/style/images/many/gold.png'>$gold золота", ':user_id' => $users_t['id'], ':time' => time()));
#-Статуэтки и опыт по нулям-#
if($i == 10){
$upd_users_t = $pdo->query("UPDATE `users` SET `figur` = 0, `exp` = 0 WHERE `level` = 100");
}
}
}else{
$upd_users_t = $pdo->query("UPDATE `users` SET `figur` = 0, `exp` = 0 WHERE `level` = 100");
}
}

#-Турнир Дуэли-#
for($i = 1; $i <= 10; $i++){
$u = $i - 1;
$sel_users_d = $pdo->query("SELECT `id`, `duel_t`, `gold` FROM `users` WHERE `duel_t` > 0 ORDER BY `duel_t` DESC LIMIT $u, 1");
if($sel_users_d-> rowCount() != 0){
$users_d = $sel_users_d->fetch(PDO::FETCH_LAZY);
//1 место
if($i == 1) $gold = 800;
//2 место
if($i == 2) $gold = 700;
//3 место
if($i == 3) $gold = 600;
//4 место
if($i == 4) $gold = 500;
//5 место
if($i == 5) $gold = 400;
//6 место
if($i == 6) $gold = 350;
//7 место
if($i == 7) $gold = 300;
//8 место
if($i == 8) $gold = 250;
//9 место
if($i == 9) $gold = 200;
//10 место
if($i == 10) $gold = 100;
if(isset($gold)){
#-Зачисляем золото игрокам-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $users_d['gold']+$gold, ':id' => $users_d['id'])); 
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы заняли $i место в Турнире дуэлей: <img src='/style/images/many/gold.png'>$gold золота", ':user_id' => $users_d['id'], ':time' => time()));
#-Статуэтки и опыт по нулям-#
if($i == 10){
$upd_users_d = $pdo->query("UPDATE `users` SET `duel_t` = 0");
}
}
}else{
$upd_users_d = $pdo->query("UPDATE `users` SET `duel_t` = 0");
}
}

#-Турнир Колизей-#
for($i = 1; $i <= 10; $i++){
$u = $i - 1;
$sel_users_c = $pdo->query("SELECT `id`, `coliseum_t`, `gold` FROM `users` WHERE `coliseum_t` > 0 ORDER BY `coliseum_t` DESC LIMIT $u, 1");
if($sel_users_c-> rowCount() != 0){
$users_c = $sel_users_c->fetch(PDO::FETCH_LAZY);
//1 место
if($i == 1) $gold = 800;
//2 место
if($i == 2) $gold = 700;
//3 место
if($i == 3) $gold = 600;
//4 место
if($i == 4) $gold = 500;
//5 место
if($i == 5) $gold = 400;
//6 место
if($i == 6) $gold = 350;
//7 место
if($i == 7) $gold = 300;
//8 место
if($i == 8) $gold = 250;
//9 место
if($i == 9) $gold = 200;
//10 место
if($i == 10) $gold = 100;
if(isset($gold)){
#-Зачисляем золото игрокам-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $users_c['gold']+$gold, ':id' => $users_c['id'])); 
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы заняли $i место в Турнире колизей: <img src='/style/images/many/gold.png'>$gold золота", ':user_id' => $users_c['id'], ':time' => time()));
#-Статуэтки и опыт по нулям-#
if($i == 10){
$upd_users_c = $pdo->query("UPDATE `users` SET `coliseum_t` = 0");
}
}
}else{
$upd_users_c = $pdo->query("UPDATE `users` SET `coliseum_t` = 0");
}
}

#-Турнир Башни-#
for($i = 1; $i <= 10; $i++){
$u = $i - 1;
$sel_users_to = $pdo->query("SELECT `id`, `towers_t`, `gold` FROM `users` WHERE `towers_t` > 0 ORDER BY `towers_t` DESC LIMIT $u, 1");
if($sel_users_to-> rowCount() != 0){
$users_to = $sel_users_to->fetch(PDO::FETCH_LAZY);
//1 место
if($i == 1) $gold = 800;
//2 место
if($i == 2) $gold = 700;
//3 место
if($i == 3) $gold = 600;
//4 место
if($i == 4) $gold = 500;
//5 место
if($i == 5) $gold = 400;
//6 место
if($i == 6) $gold = 350;
//7 место
if($i == 7) $gold = 300;
//8 место
if($i == 8) $gold = 250;
//9 место
if($i == 9) $gold = 200;
//10 место
if($i == 10) $gold = 100;
if(isset($gold)){
#-Зачисляем золото игрокам-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $users_to['gold']+$gold, ':id' => $users_to['id'])); 
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы заняли $i место в Турнире башни: <img src='/style/images/many/gold.png'>$gold золота", ':user_id' => $users_to['id'], ':time' => time()));
#-Статуэтки и опыт по нулям-#
if($i == 10){
$upd_users_to = $pdo->query("UPDATE `users` SET `towers_t` = 0");
}
}
}else{
$upd_users_to = $pdo->query("UPDATE `users` SET `towers_t` = 0");
}
}

#-КЛАНОВЫЕ ТУРНИРЫ-#

#-Турнир Статуэтки-#
for($i = 1; $i <= 10; $i++){
$u = $i - 1;
$sel_clan_t = $pdo->query("SELECT `id`, `level`, `figur`, `gold` FROM `clan` WHERE `figur` > 0 ORDER BY `figur` DESC LIMIT $u, 1");
if($sel_clan_t-> rowCount() != 0){
$clan_t = $sel_clan_t->fetch(PDO::FETCH_LAZY);
//1 место
if($i == 1) $gold = 3000;
//2 место
if($i == 2) $gold = 2500;
//3 место
if($i == 3) $gold = 2250;
//4 место
if($i == 4) $gold = 2000;
//5 место
if($i == 5) $gold = 1750;
//6 место
if($i == 6) $gold = 1500;
//7 место
if($i == 7) $gold = 1250;
//8 место
if($i == 8) $gold = 1000;
//9 место
if($i == 9) $gold = 750;
//10 место
if($i == 10) $gold = 500;
if(isset($gold)){
#-Зачисляем золото кланам-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = :gold WHERE `id` = :id");
$upd_clan->execute(array(':gold' => $clan_t['gold']+$gold, ':id' => $clan_t['id'])); 
$ins_clan_log = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_log->execute(array(':type' => 8, ':log' => "Клан занял $i место в Турнире статуэток: <img src='/style/images/many/gold.png'>$gold золота", ':clan_id' => $clan_t['id'], ':time' => time()));
#-Статуэтки и опыт по нулям-#
if($i == 10){
$upd_clan_t = $pdo->query("UPDATE `clan` SET `figur` = 0, `exp` = 0 WHERE `level` = 150");
}
}
}else{
$upd_clan_t = $pdo->query("UPDATE `clan` SET `figur` = 0, `exp` = 0 WHERE `level` = 150");
}
}
?>