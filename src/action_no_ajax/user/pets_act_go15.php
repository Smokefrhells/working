<?php
require_once '../../system/system.php';
echo only_reg();

#-Выбор питомца-#
switch($act){
case 'active':
if(isset($_GET['pets_id'])){
$pets_id = check($_GET['pets_id']);

#-Проверка что есть питомец-#	
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `id` = :pets_id AND `user_id` = :user_id AND `active` = 0 AND `battle` = 0");	
$sel_pets_me->execute(array(':pets_id' => $pets_id, ':user_id' => $user['id']));
if($sel_pets_me-> rowCount() == 0) $error = 'Питомец не найден!';
#-Проверка что статуса бой-#
$sel_pets_me_b = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1 AND `battle` = 1");	
$sel_pets_me_b->execute(array(':user_id' => $user['id']));
if($sel_pets_me_b-> rowCount() != 0) $error = 'Вы участвуете в бою!';


#-Если нет ошибок-#
if(!isset($error)){
$upd_pets_a = $pdo->prepare("UPDATE `pets_me` SET `active` = 0 WHERE `user_id` = :user_id");	
$upd_pets_a->execute(array(':user_id' => $user['id']));
$upd_pets = $pdo->prepare("UPDATE `pets_me` SET `active` = 1 WHERE `id` = :pets_id LIMIT 1");	
$upd_pets->execute(array(':pets_id' => $pets_id));
header('Location: /pets');
exit();	
}else{
header('Location: /pets');
$_SESSION['err'] = $error;
exit();	
}	
}else{
header('Location: /pets');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}

#-Тренировка питомца-#
switch($act){
case 'traing':
if(isset($_GET['pets_id'])){
$pets_id = check($_GET['pets_id']);

#-Проверка что есть питомец-#	
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `id` = :pets_id AND `user_id` = :user_id AND `max_level` < 100 AND `battle` = 0");	
$sel_pets_me->execute(array(':pets_id' => $pets_id, ':user_id' => $user['id']));
if($sel_pets_me-> rowCount() == 0) $error = 'Питомец не найден или в бою!';
#-Проверка что статуса бой-#
$sel_pets_me_b = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1 AND `battle` = 1");	
$sel_pets_me_b->execute(array(':user_id' => $user['id']));
if($sel_pets_me_b-> rowCount() != 0) $error = 'Вы участвуете в бою!';

#-Если нет ошибок-#
if(!isset($error)){
$pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);	
$param = $pets_me['max_level']*$pets_me['pets_id'];

#-Прокачка за золото-#
if($pets_me['b_level'] == 3){
$gold = (($pets_me['max_level'] * 2)*$pets_me['pets_id']);
if($user['gold'] >= $gold){
#-Минус золота-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");	
$upd_users->execute(array(':gold' => $user['gold']-$gold, ':user_id' => $user['id']));
#-Плюс к параметрам-#
$upd_pets = $pdo->prepare("UPDATE `pets_me` SET `b_param` = :b_param, `b_level` = 0, `max_level` = :max_level WHERE `id` = :pets_id LIMIT 1");	
$upd_pets->execute(array(':b_param' => $pets_me['b_param']+$param, ':max_level' => $pets_me['max_level']+1, ':pets_id' => $pets_me['id']));
header('Location: /pets');
exit();	
}else{
header('Location: /pets');
$_SESSION['err'] = 'Недостаточно золота!';
exit();		
}

}else{
$silver = round(((627 * $pets_me['max_level'])) * $pets_me['pets_id'], 0);
if($user['silver'] >= $silver){
#-Минус серебро-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']-$silver, ':user_id' => $user['id']));
#-Плюс к параметрам-#
$upd_pets = $pdo->prepare("UPDATE `pets_me` SET `b_param` = :b_param, `b_level` = :b_level, `max_level` = :max_level WHERE `id` = :pets_id LIMIT 1");	
$upd_pets->execute(array(':b_param' => $pets_me['b_param']+$param, ':b_level' => $pets_me['b_level']+1, ':max_level' => $pets_me['max_level']+1, ':pets_id' => $pets_me['id']));
header('Location: /pets');
exit();	
}else{
header('Location: /pets');
$_SESSION['err'] = 'Недостаточно серебра!';
exit();		
}
}
}else{
header('Location: /pets');
$_SESSION['err'] = $error;
exit();	
}	
}else{
header('Location: /pets');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}


#-Тренировка способности-#
switch($act){
case 'abi_traing':
if(isset($_GET['pets_id'])){
$pets_id = check($_GET['pets_id']);

#-Проверка что есть питомец-#	
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `id` = :pets_id AND `user_id` = :user_id AND `battle` = 0");	
$sel_pets_me->execute(array(':pets_id' => $pets_id, ':user_id' => $user['id']));
if($sel_pets_me-> rowCount() == 0) $error = 'Питомец не найден или в бою!';
#-Проверка что статуса бой-#
$sel_pets_me_b = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1 AND `battle` = 1");	
$sel_pets_me_b->execute(array(':user_id' => $user['id']));
if($sel_pets_me_b-> rowCount() != 0) $error = 'Вы участвуете в бою!';

#-Если нет ошибок-#
if(!isset($error)){
$pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);	
#-Золото на тренировку-#
$abi_gold = ($pets_me['pets_id']*$pets_me['abi_prosent'])*5;
#-Максимальное кол-во процентов-#
if($pets_me['pets_id'] == 1){$abi_prosent = 35;}
if($pets_me['pets_id'] == 2){$abi_prosent = 30;}	
if($pets_me['pets_id'] == 3){$abi_prosent = 30;}	
if($pets_me['pets_id'] == 4){$abi_prosent = 25;}
if($pets_me['pets_id'] == 5){$abi_prosent = 20;}	

#-Достаточно золота-#
if($user['gold'] >= $abi_gold){
#-Способность не прокачана на максимум-#
if($pets_me['abi_prosent'] < $abi_prosent){
#-Минус золота-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");	
$upd_users->execute(array(':gold' => $user['gold']-$abi_gold, ':user_id' => $user['id']));
#-Плюс процент-#
$upd_pets_me = $pdo->prepare("UPDATE `pets_me` SET `abi_prosent` = :abi_prosent WHERE `id` = :pets_id LIMIT 1");
$upd_pets_me->execute(array(':abi_prosent' => $pets_me['abi_prosent']+1, ':pets_id' => $pets_me['id']));
header('Location: /pets');
exit();	
}else{
header('Location: /pets');
$_SESSION['err'] = 'Способность прокачана на максимум!';
exit();	
}
}else{
header('Location: /pets');
$_SESSION['err'] = 'Недостаточно золота!';
exit();		
}
}else{
header('Location: /pets');
$_SESSION['err'] = $error;
exit();	
}
}else{
header('Location: /pets');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}
?>