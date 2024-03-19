<?php
require_once '../../system/system.php';
echo only_reg();
#-Берем задание-#
switch($act){
case 'take':
if(isset($_GET['type_id'])){
$type_id = check($_GET['type_id']);
#Проверяем есть ли такой босс
$sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `id` = :type_id AND `type` != 4");
$sel_boss->execute(array(':type_id' => $type_id));
if($sel_boss-> rowCount() == 0) $error = 'Босс не найден!';
#-Проверяем что еще не брали такое задание-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = :type_id");
$sel_boss_t->execute(array(':user_id' => $user['id'], ':type_id' => $type_id));
if($sel_boss_t-> rowCount() != 0) $error = 'Вы уже взяли это задание!';
#-Если нет ошибок-#
if(!isset($error)){
$ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `type_id` = :type_id, `user_id` = :user_id, `time` = :time");
$ins_tasks->execute(array(':type' => 4, ':type_id' => $type_id, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /daily_tasks');
exit();
}else{
header('Location: /daily_tasks');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /daily_tasks');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}
?>