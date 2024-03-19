<?php
require_once '../../system/system.php';
echo only_reg();
echo towers_level();

#-Выход из очереди башен-#
switch($act){
case 'exit_osh':
#-Проверка что игрок стоит в очереди-#
$sel_towers = $pdo->prepare("SELECT `id`, `user_id`, `statys`, `type` FROM `towers` WHERE `user_id` = :user_id AND `statys` = 0");
$sel_towers->execute(array(':user_id' => $user['id']));
if($sel_towers->rowCount() == 0) $error = 'Выход невозможен!';

#-Нет ошибок-#
if(!isset($error)){
$towers = $sel_towers->fetch(PDO::FETCH_LAZY);	
if($user['level'] <= 100){$gold = 85;$silver = 25000;}
if($user['level'] <= 75){$gold = 65;$silver = 15000;}
if($user['level'] <= 50){$gold = 45;$silver = 10000;}
if($user['level'] <= 25){$gold = 25;$silver = 5000;}

#-Возврат средств-#
if($towers['type'] == 'gold'){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");	
$upd_users->execute(array(':gold' => $user['gold']+$gold, ':user_id' => $user['id']));	
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");	
$upd_users->execute(array(':silver' => $user['silver']+$silver, ':user_id' => $user['id']));		
}
#-Удаление боя-#
$del_towers = $pdo->prepare("DELETE FROM `towers` WHERE `user_id` = :user_id");
$del_towers->execute(array(':user_id' => $user['id']));
header('Location: /towers');
}else{
header('Location: /towers');
$_SESSION['err'] = $error;
exit();
}
}

#-Выход из статистики башен-#
switch($act){
case 'exit_stk':
#-Проверка что игрок участвовал в этом бою-#
$sel_towers = $pdo->prepare("SELECT `id`, `user_id`, `statys` FROM `towers` WHERE `user_id` = :user_id AND `statys` = 3");
$sel_towers->execute(array(':user_id' => $user['id']));
if($sel_towers->rowCount() == 0) $error = 'Данные не найдены!';

#-Нет ошибок-#
if(!isset($error)){
#-Удаление боя-#
$del_towers = $pdo->prepare("DELETE FROM `towers` WHERE `user_id` = :user_id");
$del_towers->execute(array(':user_id' => $user['id']));
header('Location: /towers');
}else{
header('Location: /towers');
$_SESSION['err'] = $error;
exit();
}
}
?>