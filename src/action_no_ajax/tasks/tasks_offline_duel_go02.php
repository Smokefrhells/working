<?php
require_once '../../system/system.php';
echo only_reg();
#-Берем задание-#
switch($act){
case 'take':
#-Проверяем что еще не брали такое задание-#
$sel_duel = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 2");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
$ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_tasks->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /daily_tasks');
exit();
}else{
header('Location: /daily_tasks');
$_SESSION['err'] = 'Вы уже взяли это задание!';
exit();
}
}
?>