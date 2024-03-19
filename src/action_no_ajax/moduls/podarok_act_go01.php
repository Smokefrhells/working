<?php
require_once '../../system/system.php';
switch($act){
case 'give':
#-Проверяем есть ли бонус-#
if($user['podarok'] != 0){
#-Ключи-#
if($user['type_podarok'] == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `key` = :key, `podarok` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':key' => $user['key']+$user['podarok'], ':id' => $user['id']));	
}
#-Серебро-#
if($user['type_podarok'] == 2){
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `podarok` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+$user['podarok'], ':id' => $user['id']));	
}
#-Золото-#
if($user['type_podarok'] == 3){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `podarok` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+$user['podarok'], ':id' => $user['id']));
}
#-Кристаллы-#
if($user['type_podarok'] == 4){
$upd_users = $pdo->prepare("UPDATE `users` SET `crystal` = :crystal, `podarok` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':crystal' => $user['crystal']+$user['podarok'], ':id' => $user['id']));
}
header('Location: /');
$_SESSION['ok'] = 'Подарок получен!';
exit();
}else{
header('Location: /');
$_SESSION['err'] = 'Подарков нет!';
exit();
}
}
?>