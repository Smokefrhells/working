<?php
require_once '../../system/system.php';
echo only_reg();
echo admin();

#-Відача комплекта-#
switch($act){
case 'get':
if(isset($_GET['weapon']) and isset($_GET['id']) and $user['prava'] == 1){
$weapon = check($_GET['weapon']);
$ank_id = check($_GET['id']);
#-Проверка что игрок не найден-#
$sel_users = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $_GET['id']));
if($sel_users->rowCount() == 0) $error = 'Игрок не найден!';

#-Если нет ошибок-#
if(!isset($error)){
$weapon_id = $weapon;
for($i=0; $i < 6; $i++)
{
$weapon_id = $weapon_id+1;
$type_ar = $i+1;
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :ank_id, `state` = 0, `time` = 0");
$ins_weapon_me->execute(array(':type' => $type_ar, ':weapon_id' => $weapon_id, ':ank_id' => $ank_id));
}		
header("Location: /admin_armor?id=$ank_id");
$_SESSION['ok'] = 'Комплект выдан!';
exit();
}else{
header('Location: /admin');
$_SESSION['err'] = $error;
exit();	
}
}else{
header('Location: /admin');
exit();
}
}
?>