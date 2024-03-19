<?php
require_once H.'system/system.php';
#-Проверяем есть босс или нет-#
$sel_reid_b = $pdo->query("SELECT * FROM `reid_boss`");
#-Если нет то создаем-#
if($sel_reid_b -> rowCount() == 0){
$rand_b = rand(0, 100);
if($rand_b >= 0){
$name = 'Огненный дракон';
$img = 1;
$type = 3;
$sila = 200;
$zashita = 300;
$health = 800000;
}
if($rand_b >= 50){
$name = 'Клазавр';
$img = 2;
$type = 4;
$sila = 300;
$zashita = 700;
$health = 150000;	
}
if($rand_b >= 70){
$name = 'Зомби';
$img = 3;
$type = 5;
$sila = 300;
$zashita = 700;
$health = 2500000;
}
if($rand_b >= 85){
$name = 'Некромант';
$img = 4;
$type = 6;
$sila = 300;
$zashita = 700;
$health = 3500000;	
}
if($rand_b >= 95){
$name = 'Гианот';
$img = 5;
$type = 7;
$sila = 300;
$zashita = 700;
$health = 5000000;	
}

#-Создаем рейд-#
$ins_reid = $pdo->prepare("INSERT INTO `reid_boss` SET `name` = :name, `sila` = :sila, `zashita` = :zashita, `max_health` = :health, `health` = :health, `t_health` = :health, `images` = :images, `type` = :type, `time` = :time");
$ins_reid->execute(array(':name' => $name, ':sila' => $sila, ':zashita' => $zashita, ':health' => $health, ':images' => $img, ':type' => $type, ':time' => time()+21600));
}else{
	
$reid = $sel_reid_b->fetch(PDO::FETCH_LAZY);
#-Начинаем рейд-#
if($reid['statys'] == 0 and $reid['time'] < time()){
#-Статус бой всем участникам рейда-#
$sel_reid_u = $pdo->query("SELECT * FROM `reid_users`");
while($reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY)){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :id LIMIT 1");	
$upd_users->execute(array(':id' => $reid_u['user_id']));
}
#-Ставим время-#
$upd_reid = $pdo->prepare("UPDATE `reid_boss` SET `statys` = 1, `time` = :time WHERE `id` = :id LIMIT 1");	
$upd_reid->execute(array(':time' => time()+7200, ':id' => $reid['id']));
#-Чистка лога-#
$del_reid_l = $pdo->query("DELETE FROM `reid_log`");
#-Лог-#
$ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/body/reid.png' alt=''/> <span style='color: #00a800;'>Рейд начался</span>", ':time' => time()));
}

#-Окончание рейда-#
if($reid['statys'] == 1 and $reid['time'] < time()){
#-Убираем статус боя всем участникам-#
$sel_reid_u = $pdo->query("SELECT * FROM `reid_users`");
while($reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY)){
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 0, `reid_progrash` = (`reid_progrash`+1) WHERE `id` = :id LIMIT 1");	
$upd_users->execute(array(':id' => $reid_u['user_id']));
$del_reid_u = $pdo->prepare("DELETE FROM `reid_users` WHERE `id` = :id");
$del_reid_u->execute(array(':id' => $reid_u['id']));
}
#-Удаление босса-#
$del_reid_b = $pdo->query("DELETE FROM `reid_boss`");
#-Лог-#
$ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<img src='/style/images/body/time.png' alt=''/> <span style='color: #ff0000;'>Время вышло</span>", ':time' => time()));
}
}
?>