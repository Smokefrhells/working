<?php
require_once '../../system/system.php';
echo only_reg();
echo admin();

#-Відача питомца-#
switch($act){
case 'get':
if(isset($_GET['pets_id']) and isset($_GET['id']) and $user['prava'] == 1){
$pets_id = check($_GET['pets_id']);
$ank_id = check($_GET['id']);
#-Проверка что игрок не найден-#
$sel_users = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $_GET['id']));
if($sel_users->rowCount() == 0) $error = 'Игрок не найден!';
#-Проверка что питомец существует-#
$sel_pets = $pdo->prepare("SELECT `id` FROM `pets` WHERE `id` = :pets_id");
$sel_pets->execute(array(':pets_id' => $_GET['pets_id']));
if($sel_pets->rowCount() == 0) $error = 'Питомец не найден!';

#-Если нет ошибок-#
if(!isset($error)){
$ins_pets_me = $pdo->prepare("INSERT INTO `pets_me` SET `pets_id` = :pets_id, `user_id` = :ank_id");
$ins_pets_me->execute(array(':pets_id' => $pets_id, ':ank_id' => $ank_id));	
header("Location: /admin_pets?id=$ank_id");
$_SESSION['ok'] = 'Питомец выдан!';
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