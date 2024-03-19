<?php
require_once '../system.php';
#-Задание на удаление записей через 24 часа-#
 //        ini_set('error_reporting', E_ALL);
 //       ini_set('display_errors', true);
/*ГЕРОЙ*/
//ЛОГ
$del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `time` < :time");
$del_event->execute(array(':time' => time()-86400));
//Не сохраненых игроков
$del_us = $pdo->prepare("DELETE FROM `users` WHERE `time_online` < :time AND `save` = :save");
$del_us->execute(array(':time' => time()-1209600, ':save' => 0));
//Сундуки
$del_chest = $pdo->prepare("DELETE FROM `chest` WHERE `time` < :time");
$del_chest->execute(array(':time' => time()-432000));
//Чат
$del_chat = $pdo->prepare("DELETE FROM `chat` WHERE `time` < :time");
$del_chat->execute(array(':time' => time()-172800));
//Чат для модераторов
$del_chat_m = $pdo->prepare("DELETE FROM `chat_moderator` WHERE `time` < :time");
$del_chat_m->execute(array(':time' => time()-172800));
//Ежедневные задания
$del_tasks = $pdo->query("DELETE FROM `daily_tasks`");
//Сообщения
$del_mail = $pdo->prepare("DELETE FROM `mail` WHERE `time` < :time");
$del_mail->execute(array(':time' => time()-864000));
$del_mail_k = $pdo->prepare("DELETE FROM `mail_kont` WHERE `time` < :time");
$del_mail_k->execute(array(':time' => time()-864000));

/*КЛАНЫ*/
//Заявки в клан
$del_clan_a = $pdo->prepare("DELETE FROM `clan_application` WHERE `time` < :time");
$del_clan_a->execute(array(':time' => time()-172800));
//Клановый чат
$del_clan_chat = $pdo->prepare("DELETE FROM `clan_chat` WHERE `time` < :time");
$del_clan_chat->execute(array(':time' => time()-259200));
//Лог клана
$del_clan_log = $pdo->prepare("DELETE FROM `clan_log` WHERE `time` < :time");
$del_clan_log->execute(array(':time' => time()-1209600));



                                                             #-РЕЙТИНГ ОПЫТА В КЛАНЕ-#
#-Выборка кланов-#
$sel_clan = $pdo->query("SELECT `id`, `amulet_lvl`, `tour_exp_1`, `tour_exp_2`, `tour_exp_3`, `tour_exp_all`, `gold` FROM `clan`");
if($sel_clan-> rowCount() != 0){
while($clan = $sel_clan->fetch(PDO::FETCH_LAZY))  
{
#-Считаем количество активных игроков-#
$sel_clan_u = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id AND `exp` > 0 LIMIT 10");
$sel_clan_u->execute(array(':clan_id' => $clan['id']));
$amount_c = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Кол-во активных игроков должно быть не меньше 3-#
if($amount_c[0] >= 3){
$gold_top = $clan['tour_exp_1']+$clan['tour_exp_2']+$clan['tour_exp_3'];
$lis = $amount_c[0]-$clan['amulet_lvl'];
if($lis > 0){
$gold_kazna = (($amount_c[0]-(3+$lis))*$clan['tour_exp_all'])+$gold_top;	
}else{
$gold_kazna = (($amount_c[0]-3)*$clan['tour_exp_all'])+$gold_top;	
}
#-Достаточно ли золота в казне-#
if($clan['gold'] >= $gold_kazna){
#-Отнимаем золото из казны-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = :gold WHERE `id` = :clan_id LIMIT 1");
$upd_clan->execute(array(':gold' => $clan['gold']-$gold_kazna, ':clan_id' => $clan['id'])); 

#-Зачисляем награду-#
for($i = 1; $i <= 10; $i++){
$u = $i - 1;
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `exp` > 0 ORDER BY `exp` DESC LIMIT $u, 1");
$sel_clan_u2->execute(array(':clan_id' => $clan['id']));
$clan_u2 = $sel_clan_u2->fetch(PDO::FETCH_LAZY);
if($i == 1)$gold = $clan['tour_exp_1'];
if($i == 2)$gold = $clan['tour_exp_2'];
if($i == 3)$gold = $clan['tour_exp_3'];
if($i == 4 or $i == 5 or $i == 6 or $i == 7 or $i == 8 or $i == 9 or $i == 10){
if($clan['amulet_lvl'] >= $i){
$gold = $clan['tour_exp_all'];
}else{
$gold = 0;	
}
}
if($gold != 0){
#-Зачисляем золото игрокам-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = `gold` + :gold WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $gold, ':id' => $clan_u2['user_id'])); 
#-Записываем лог-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы заняли $i место в Рейтинге опыта: <img src='/style/images/many/gold.png'>$gold золота", ':user_id' => $clan_u2['user_id'], ':time' => time()));
}
}
}else{
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 2, ':log' => "Рейтинг опыта: Недостаточно золота в казне!", ':clan_id' => $clan['id'], ':time' => time())); 
}
}
}
}
#-Обнуляем ПЕТОМЦЕВ-#
$petbou = $pdo->query("UPDATE `users` SET `pets_boi` = 15");
#-Обнуляем опыт у игроков с кланов-#
$upd_clan_u = $pdo->query("UPDATE `clan_users` SET `exp` = 0");

/*Ежедневная награда*/
#-Обнуление ежедневой награды-#
$upd_users_nul = $pdo->query("UPDATE `users` SET `every_num` = 1, `every_statys` = 0 WHERE (`every_num` = 7 AND `every_statys` = 7) OR (`every_num` != `every_statys`)");
#-Следующая ежедневная награда-#
$upd_users_next = $pdo->query("UPDATE `users` SET `every_num` = `every_num` + 1 WHERE `every_num` = `every_statys` AND `every_num` != 7");

#-Проверка аватаров на существование-#
$sel_users = $pdo->query("SELECT `id`, `avatar` FROM `users` WHERE `avatar` != ''");
while($us = $sel_users->fetch(PDO::FETCH_LAZY)){
$filename = H."style/avatar/".$us['avatar'];
if(!file_exists($filename)){
$upd_users = $pdo->prepare("UPDATE `users` SET `avatar` = '' WHERE `id` = :user_id");
$upd_users->execute(array(':user_id' => $us['id']));
}
}

#-Обнуление колеса фортуны-#
$upd_users= $pdo->query("UPDATE `users` SET `fortuna` = 0");
#-Обнуление лога фортуны-#
$del_fortuna_log= $pdo->query("DELETE FROM `fortuna_log`");
?>