<?php
require_once '../../system/system.php';
echo only_reg();
#-Вступление в бой в пирамиде-#
switch($act){
case 'join':
#-Проверка что бой существует-#
$sel_pyramid_b = $pdo->query("SELECT * FROM `pyramid_battle_b` WHERE `statys` = 1");
if($sel_pyramid_b->rowCount() == 0) $error = 'Бой еще не начат или уже подходит к концу!';
#-Не должны участвовать в бою-#	
$sel_pyramid_u = $pdo->prepare("SELECT * FROM `pyramid_battle_u` WHERE `user_id` = :user_id");
$sel_pyramid_u->execute(array(':user_id' => $user['id']));
if($sel_pyramid_u-> rowCount() != 0) $error = 'Вы уже участвуете в бою!';	
#-Проверка что есть активный питомец-#
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1");
$sel_pets_me->execute(array(':user_id' => $user['id']));
if($sel_pets_me-> rowCount() == 0) $error = 'У вас нет выбраного питомца!';
#-Если нет ошибок-#	
if(!isset($error)){
$pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);
#-Выборка данных питомца-#
$sel_pets = $pdo->prepare("SELECT * FROM `pets` WHERE `id` = :pets_id");
$sel_pets->execute(array(':pets_id' => $pets_me['pets_id']));
$pets = $sel_pets->fetch(PDO::FETCH_LAZY);	
#-Запись данных-#	
$ins_pyramid_u = $pdo->prepare("INSERT INTO `pyramid_battle_u` SET `name` = :name, `images`= :images, `sila` = :sila, `zashita` = :zashita, `health` = :health, `max_health` = :health, `user_id` = :user_id");
$ins_pyramid_u->execute(array(':name' => $pets['name'], ':images' => $pets['images'], ':sila' => $pets['sila'], ':zashita' => $pets['zashita'], ':health' => $pets['health']+$pets_me['b_param'], ':user_id' => $user['id']));	
header('Location: /pyramid');
exit();		
}else{
header('Location: /pyramid');
$_SESSION['err'] = $error;
exit();	
}
}