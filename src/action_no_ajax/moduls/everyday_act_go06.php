<?php
require_once '../../system/system.php';
echo only_reg();
#-Награда за первый день-#
switch($act){
case 'day1':
if($user['every_num'] == 1 and $user['every_statys'] == 0){
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = 1, `user_id` = :user_id, `time` = :time");
$ins_chest->execute(array(':user_id' => $user['id'], ':time' => time()));
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}

#-Награда за второй день-#
switch($act){
case 'day2':
if($user['every_num'] == 2 and $user['every_statys'] == 1){
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+15, ':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}

#-Награда за третий день-#
switch($act){
case 'day3':
if($user['every_num'] == 3 and $user['every_statys'] == 2){
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+30, ':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}

#-Награда за четвертый день-#
switch($act){
case 'day4':
if($user['every_num'] == 4 and $user['every_statys'] == 3){
#-Зелье-#
#-Проверяем есть ли такое зелье у игрока-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `potions_id` = 5 AND `user_id` = :user_id");
$sel_potions_me->execute(array(':user_id' => $user['id']));
if($sel_potions_me-> rowCount() == 0){
#-Если нету то создаем таблицу-#
$ins_potions_me = $pdo->prepare("INSERT INTO `potions_me` SET `quatity` = 1, `user_id` = :user_id, `potions_id` = 5, `time` = :time");
$ins_potions_me->execute(array(':user_id' => $user['id'], ':time' => time()));
}else{
#-Если есть то редактируем-#
$potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY);
$upd_potions_me = $pdo->prepare("UPDATE `potions_me` SET `quatity` = :quatity WHERE `user_id` = :user_id AND `potions_id` = 5");
$upd_potions_me->execute(array(':quatity' => $potions_me['quatity'] + 1, ':user_id' => $user['id']));
}
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}

#-Награда за пятый день-#
switch($act){
case 'day5':
if($user['every_num'] == 5 and $user['every_statys'] == 4){
#-Зелье-#
#-Проверяем есть ли такое зелье у игрока-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `potions_id` = 6 AND `user_id` = :user_id");
$sel_potions_me->execute(array(':user_id' => $user['id']));
if($sel_potions_me-> rowCount() == 0){
#-Если нету то создаем таблицу-#
$ins_potions_me = $pdo->prepare("INSERT INTO `potions_me` SET `quatity` = 1, `user_id` = :user_id, `potions_id` = 6, `time` = :time");
$ins_potions_me->execute(array(':user_id' => $user['id'], ':time' => time()));
}else{
#-Если есть то редактируем-#
$potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY);
$upd_potions_me = $pdo->prepare("UPDATE `potions_me` SET `quatity` = :quatity WHERE `user_id` = :user_id AND `potions_id` = 6");
$upd_potions_me->execute(array(':quatity' => $potions_me['quatity'] + 1, ':user_id' => $user['id']));
}
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}

#-Награда за шестой день-#
switch($act){
case 'day6':
if($user['every_num'] == 6 and $user['every_statys'] == 5){
#-Зелье-#
#-Проверяем есть ли такое зелье у игрока-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `potions_id` = 4 AND `user_id` = :user_id");
$sel_potions_me->execute(array(':user_id' => $user['id']));
if($sel_potions_me-> rowCount() == 0){
#-Если нету то создаем таблицу-#
$ins_potions_me = $pdo->prepare("INSERT INTO `potions_me` SET `quatity` = 1, `user_id` = :user_id, `potions_id` = 4, `time` = :time");
$ins_potions_me->execute(array(':user_id' => $user['id'], ':time' => time()));
}else{
#-Если есть то редактируем-#
$potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY);
$upd_potions_me = $pdo->prepare("UPDATE `potions_me` SET `quatity` = :quatity WHERE `user_id` = :user_id AND `potions_id` = 4");
$upd_potions_me->execute(array(':quatity' => $potions_me['quatity'] + 1, ':user_id' => $user['id']));
}
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}

#-Награда за седьмой день-#
switch($act){
case 'day7':
if($user['every_num'] == 7 and $user['every_statys'] == 6){
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = 3, `user_id` = :user_id, `time` = :time");
$ins_chest->execute(array(':user_id' => $user['id'], ':time' => time()));
#-Игрок-#
$upd_users = $pdo->prepare("UPDATE `users` SET `every_statys` = :every_statys WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':every_statys' => $user['every_statys']+1, ':user_id' => $user['id']));
header('Location: /everyday');
exit();		
}else{
header('Location: /everyday');
$_SESSION['err'] = 'Награда получена!';
exit();	
}
}
?>