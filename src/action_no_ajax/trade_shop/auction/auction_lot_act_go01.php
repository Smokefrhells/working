<?php
require_once '../../../system/system.php';
echo only_reg();

#-Покупка лота в аукционе-#
switch($act){
case 'buy':
if($user['level'] >= 60){
if($user['lot'] == 0){$gold =  300;}else{$gold = $user['lot']*700;} //Необходимое кол-во золота

#-Меньше 5 лотов-#
if($user['lot'] >= 5) $error = 'Максимальное число лотов!';
#-Достаточно ли золота-#
if($user['gold'] < $gold) $error = 'Недостаточно золота!';

#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `lot` = :lot WHERE `id` = :user_id LIMIT 1");	
$upd_users->execute(array(':gold' => $user['gold']-$gold, ':lot' => $user['lot']+1, ':user_id' => $user['id']));	
header('Location: /lot');
$_SESSION['ok'] = 'Лот открыт!';
exit();
}else{
header('Location: /lot');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /trade_shop');
}
}
?>