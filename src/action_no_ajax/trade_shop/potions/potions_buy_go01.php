<?php
require_once '../../../system/system.php';
echo only_reg();

switch($act){
case 'buy':
if(isset($_GET['id']) and isset($_POST['num'])){
#-Только если не в бою-#
if($user['battle'] == 0){
$id = check($_GET['id']);
$num = check($_POST['num']);	
#-Проверяем ввод цифры-#
if(!preg_match('/^([0-9])+$/u',$_POST['num'])) $error = 'Введите цифру!';
if(!preg_match('/^([0-9])+$/u',$_GET['id'])) $error = 'Не верный идентификатор!';
#-Не более 100-#
if($num > 100) $error = 'Не более 100 шт. за раз';
#-Выборка данных о зелье-#
$sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id");
$sel_potions->execute(array(':id' => $id));
#-Только если есть такое зелье-#
if($sel_potions-> rowCount() != 0){
#-И если нет ошибок-#
if(!isset($error)){
$potions = $sel_potions->fetch(PDO::FETCH_LAZY);
#-Высчитываем деньги которые требуються-#
$many_silver = $num * $potions['silver'];
$many_gold = $num * $potions['gold'];
#-Проверка хватает ли денег-#
if($user['gold'] >= $many_gold){
#-Отнимаем деньги у пользователя-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold'] - $many_gold, ':id' => $user['id'])); 
#-Проверяем есть ли такое зелье у игрока-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `potions_id` = :potions_id AND `user_id` = :user_id");
$sel_potions_me->execute(array(':potions_id' => $potions['id'], ':user_id' => $user['id']));
if($sel_potions_me-> rowCount() == 0){
#-Если нету то создаем таблицу-#
$ins_potions_me = $pdo->prepare("INSERT INTO `potions_me` SET `quatity` = :quatity, `user_id` = :user_id, `potions_id` = :potions_id, `time` = :time");
$ins_potions_me->execute(array(':quatity' => $num, ':user_id' => $user['id'], ':potions_id' => $potions['id'], ':time' => time()));
}else{
#-Если есть то редактируем-#
$potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY);
$upd_potions_me = $pdo->prepare("UPDATE `potions_me` SET `quatity` = :quatity WHERE `user_id` = :user_id AND `potions_id` = :potions_id");
$upd_potions_me->execute(array(':quatity' => $potions_me['quatity'] + $num, ':user_id' => $user['id'], ':potions_id' => $potions['id']));
}
header("Location: /potions");
$_SESSION['ok'] = 'Успешная покупка!';
exit();
}else{
header("Location: /potions");
$_SESSION['err'] = 'Недостаточно денег!';
exit();
}
}else{
header ("Location: /potions");
$_SESSION['err'] = $error;
exit();	
}
}else{
header("Location: /potions");
$_SESSION['err'] = 'Зелье не найдено!';
exit();
}
}else{
header("Location: /potions");
}
}else{
header("Location: /potions");
$_SESSION['err'] = 'Введите данные!';
exit();
}
}
?>