<?php
require_once '../../../system/system.php';
echo only_reg();
echo pets_level();

#-Начало дуэльного поединка-#
switch($act){
case 'start':
#-Проверка что игрок не участвует в бою-#
$sel_pets_duel = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `user_id` = :user_id");
$sel_pets_duel->execute(array(':user_id' => $user['id']));
if($sel_pets_duel->rowCount() != 0) $error = 'Вы уже участвуете в бою!';
#-Проверка что есть активный питомец-#
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1");
$sel_pets_me->execute(array(':user_id' => $user['id']));
if($sel_pets_me-> rowCount() == 0) $error = 'У вас не выбраного питомца!';
if($user['pets_boi']<1)$error = 'У вас закончились бои!';
#-Если нет ошибок-#
if(!isset($error)){
$pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);	
#-Выборка данных питомца-#
$sel_pets = $pdo->prepare("SELECT `id`, `sila`, `zashita`, `health` FROM `pets` WHERE `id` = :pets_id");
$sel_pets->execute(array(':pets_id' => $pets_me['pets_id']));
$pets = $sel_pets->fetch(PDO::FETCH_LAZY);	
$pets_health = $pets['health']+$pets_me['b_param'];	//Здоровье питомца	
	
#-Подборка по параметрам-#
$max_health = $pets_health+3000;
$min_health = $pets_health-3000;
$sel_pets_duel_b1 = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `statys` = 0 AND `pets_t_health` <= :max_health AND `pets_t_health` >= :min_health");
$sel_pets_duel_b1->execute(array(':max_health' => $max_health, ':min_health' => $min_health));
if($sel_pets_duel_b1->rowCount() == 0){
#-Выборка любого врага если нет по параметрам-#
$sel_pets_duel_b2 = $pdo->query("SELECT * FROM `pets_duel` WHERE `statys` = 0");
if($sel_pets_duel_b2->rowCount() == 0){
$battle_id = rand(0, 1000);	
}else{
$pets_duel_b2 = $sel_pets_duel_b2->fetch(PDO::FETCH_LAZY);
$battle_id = $pets_duel_b2['battle_id'];
}
}else{
$pets_duel_b1 = $sel_pets_duel_b1->fetch(PDO::FETCH_LAZY);
$battle_id = $pets_duel_b1['battle_id'];
}

#-Создание боя-#
$ins_towers = $pdo->prepare("INSERT INTO `pets_duel` SET `pets_t_health` = :pets_health, `user_id` = :user_id, `pets_id` = :pets_id, `battle_id` = :battle_id");
$ins_towers->execute(array(':pets_health' => $pets_health, ':user_id' => $user['id'], ':pets_id' => $pets_me['pets_id'], ':battle_id' => $battle_id));	
#-Статус боя-#
$upd_pets_me = $pdo->prepare("UPDATE `pets_me` SET `battle` = 1 WHERE `id` = :pets_id LIMIT 1");
$upd_pets_me->execute(array(':pets_id' => $pets_me['id']));
$upd_pets_me = $pdo->prepare("UPDATE `users` SET `pets_boi` = :boi WHERE `id` = :user_id LIMIT 1");
$upd_pets_me->execute(array(':user_id' => $user['id'], ':boi' => $user['pets_boi']-1));
header('Location: /pets_duel');
}else{
header('Location: /pets_duel');
$_SESSION['err'] = $error;
exit();
}
}
?>