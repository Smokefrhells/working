<?php
require_once '../../../system/system.php';
echo only_reg();

#-Удаление снаряжение с аукциона-#
switch($act){
case 'remove':
if($user['level'] >= 60 and isset($_GET['weapon_id'])){
$weapon_id = check($_GET['weapon_id']);

#-Проверка снаряжения-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id AND `auction` = 1 AND `state` = 0");
$sel_weapon_me->execute(array(':id' => $weapon_id, ':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() == 0) $error = 'Снаряжение не найдено!';

#-Если нет ошибок-#
if(!isset($error)){
#-Удаление с продажи-#
$upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `auction` = 0, `gold` = 0, `silver` = 0, `time` = 0 WHERE `id` = :weapon_id LIMIT 1");
$upd_weapon_me->execute(array(':weapon_id' => $weapon_id));
header('Location: /lot');
exit();	
}else{
header("Location: /lot?expose=$weapon_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /trade_shop');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>