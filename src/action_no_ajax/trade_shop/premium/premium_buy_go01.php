<?php
require_once '../../../system/system.php';
echo only_reg();
echo save();
#-Покупка и продление премиум аккаунта-#
switch($act){
case 'buy':
if(isset($_GET['type']) and isset($_POST['num'])){
$type = check($_GET['type']); //Тип премиума
$num = check($_POST['num']); //Кол-во дней
	
#-Ация на премиум-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 6");
if($sel_stock-> rowCount() == 0){
$prem_1 = 150 * $num; //Золото за Серебряный премиум
$prem_2 = 250 * $num; //Золото за Золотой премиум
}else{
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);
$prem_1 = round((150 - ((150 * $stock['prosent'])/100)), 0) * $num; //Золото за Серебряный премиум
$prem_2 = round((250 - ((250 * $stock['prosent'])/100)), 0) * $num; //Золото за Золотой премиум
}

if(!preg_match('/^[0-9]+$/',$_POST['num'])){
//$upd_users_bl = $pdo->prepare("UPDATE `users` SET `block` = :block, `cause` = :cause WHERE `id` = :id");
//$upd_users_bl->execute(array(':block' => time()+10, ':cause' => 'Использование бага на накрутку.', ':id' => $user['id'])); 
header('Location: /premium');
//$_SESSION['err'] = 'Вот и попался(ась)...';
$_SESSION['err'] = 'Так нельзя:D';
exit();
//$error = 'Вот и попался(ась)...';
$error = 'Так нельзя:D';
}
#-Тип премиум аккаунта-#
if(!in_array($type, array('1','2'))) $error = 'Неверный тип премиум аккаунта!';
#-Кол-во дней-#
if($num > 90) $error = 'Не более 90 дней за раз!';
#-Хватает ли золота-#
if($type == 1){
if($user['gold'] < $prem_1) $error = 'Недостаточно золота!';
}
if($type == 2){
if($user['gold'] < $prem_2) $error = 'Недостаточно золота!';
}

#-Если нет ошибок-#
if(!isset($error)){
#-Покупка премиума-#
if($user['premium'] == 0){
if($type == 1){
$time_prem = $num * 86400;
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `premium` = :premium, `premium_time` = :premium_time WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - $prem_1, ':premium' => 1, ':premium_time' => time()+$time_prem, ':id' => $user['id'])); 
}
if($type == 2){
$time_prem = $num * 86400;
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `premium` = :premium, `premium_time` = :premium_time WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - $prem_2, ':premium' => 2, ':premium_time' => time()+$time_prem, ':id' => $user['id'])); 
}
}else{
#-Продление премиума-#
if($user['premium'] == 1 and $type == 1){
$time_prem = $num * 86400;
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `premium_time` = :premium_time WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - $prem_1, ':premium_time' => $user['premium_time']+$time_prem, ':id' => $user['id'])); 
}
if($user['premium'] == 2 and $type == 2){
$time_prem = $num * 86400;
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `premium_time` = :premium_time WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - $prem_2, ':premium_time' => $user['premium_time']+$time_prem, ':id' => $user['id'])); 
}
}
header('Location: /premium');
}else{
header('Location: /premium');
$_SESSION['err'] = $error;
exit();	
}
}else{
header('Location: /premium');
$_SESSION['err'] = 'Введите данные!';
exit();
}
}
?>