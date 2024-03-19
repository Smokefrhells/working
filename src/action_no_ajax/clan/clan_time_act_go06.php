<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Ускоряем время клана-#
switch($act){
case 'accel':
$clan_id = check($_GET['clan_id']);
#-Проверка хватает ли золота-#
if($user['gold'] >= 1000){
#-Минусуем золото и чистим время-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `clan_time` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold'] - 1000, ':id' => $user['id'])); 
header("Location: /clan/view/$clan_id");
}else{
header("Location: /clan/view/$clan_id");
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}
?>