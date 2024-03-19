<?php
require_once '../../system/system.php';

#-Лишение прав-#
switch($act){
case 'depr':
if(isset($_GET['all_id']) and ($user['prava'] == 1 or $user['prava'] == 3)){
$all_id = check($_GET['all_id']);

#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `prava` FROM `users` WHERE `id` = :all_id AND `id` != :user_id AND (`prava` = 2 OR `prava` = 3)");
$sel_users->execute(array(':all_id' => $all_id, ':user_id' => $user['id']));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден';
$all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Нет ошибок-#
if(!isset($error)){
#-Лишение прав-#
$upd_users = $pdo->prepare("UPDATE `users` SET `prava` = 0 WHERE `id` = :all_id LIMIT 1");
$upd_users->execute(array(':all_id' => $all['id']));
header("Location: /hero/$all[id]");
exit();
}else{
header("Location: /hero/$all[id]");
$_SESSION['err'] = $error;
exit();		
}
}else{
header('Location: /');
$_SESSION['err'] = 'Ошибка!';
exit();		
}
}
?>