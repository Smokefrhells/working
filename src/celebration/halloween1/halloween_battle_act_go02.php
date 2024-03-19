<?php
require_once '../../system/system.php';
echo only_reg();
#-Атака князя на хэллоуин-#
switch($act){
case 'attc':
if($user['celebration_time'] == 0){
$rand_p = '';
#-Опыт-#
$exp = rand((($user['level']+20)*150), (($user['level']+80)*200)); //Победа

#-Получение награды-#
$rand_p = rand(0, 100);
#-Серебро-#
if($rand_p > 0){
$gold = '';
$silver = 100000;
}
if($rand_p > 20){
$gold = '';
$silver = 250000;
}
#-Золото-#
if($rand_p > 40){
$silver = '';
$gold = 100;
}
if($rand_p > 70){
$silver = '';
$gold = 200;
}
if($rand_p > 80){
$silver = '';
$gold = 500;
}
if($rand_p > 87){
$silver = '';
$gold = 1000;
}
if($rand_p > 95){
$silver = '';
$gold = 2500;
}

if($silver > 0){
$silver_i = "<img src='/style/images/many/silver.png' alt=''/>$silver";
}else{
$silver_i = '';
}
if($gold > 0){
$gold_i = "<img src='/style/images/many/gold.png' alt=''/>$gold";
}else{
$gold_i = '';
}
#-Зачисление награды-#
$upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `gold` = :gold, `celebration_time` = :celebration_time WHERE `id` = :user_id");
$upd_users->execute(array(':exp' => $user['exp']+$exp, ':exp_clan' => $exp, ':silver' => $user['silver']+$silver, ':gold' => $user['gold']+$gold, ':celebration_time' => time()+10800, ':user_id' => $user['id']));
header('Location: /halloween_battle');
$_SESSION['notif'] = "<center><img src='/style/images/user/exp.png' alt=''/>$exp $silver_i $gold_i</center>";
exit();
}else{
header('Location: /halloween_battle');
$_SESSION['err'] = 'Приходите позже!';
exit();
}
}
?>