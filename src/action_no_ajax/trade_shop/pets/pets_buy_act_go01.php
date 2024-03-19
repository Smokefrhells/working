<?php
require_once '../../../system/system.php';
echo only_reg();

#-Покупка питомца-#
switch($act){
case 'buy':
if($user['level'] >= 20){
$pets_id = check($_GET['pets_id']);

#-Существует ли питомец и достаточно золота-#
$sel_pets = $pdo->prepare("SELECT * FROM `pets` WHERE `id` = :pets_id AND `gold` <= :user_gold AND `no_magaz` = 0");
$sel_pets->execute(array(':pets_id' => $pets_id, ':user_gold' => $user['gold']));
if($sel_pets-> rowCount() == 0) $error = 'Питомец не найден или недостаточно золота!';
#-Не должно быть такого же питомца-#
$sel_pets_me = $pdo->prepare("SELECT `id`, `pets_id`, `user_id` FROM `pets_me` WHERE `pets_id` = :pets_id AND `user_id` = :user_id");
$sel_pets_me->execute(array(':pets_id' => $pets_id, ':user_id' => $user['id']));
if($sel_pets_me-> rowCount() != 0) $error = 'У вас уже есть такой питомец!';

#-Если нет ошибок-#
if(!isset($error)){
$pets = $sel_pets->fetch(PDO::FETCH_LAZY);	
#-Минус средств-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-$pets['gold'], ':user_id' => $user['id']));
#-Запись питомца-#
$ins_pets_me = $pdo->prepare("INSERT INTO `pets_me` SET `pets_id` = :pets_id, `user_id` = :user_id");
$ins_pets_me->execute(array(':pets_id' => $pets['id'], ':user_id' => $user['id']));
header('Location: /magazin_pets');
$_SESSION['ok'] = 'Куплено!';
exit();	
}else{
header('Location: /magazin_pets');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /magazin_pets');
$_SESSION['err'] = 'Доступно с 20 уровня!';
exit();
}
}
?>