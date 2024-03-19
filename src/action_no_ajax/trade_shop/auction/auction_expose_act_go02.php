<?php
require_once '../../../system/system.php';
echo only_reg();

#-Выставление снаряжения на аукцион-#
switch($act){
case 'expose':
if($user['level'] >= 60 and isset($_GET['weapon_id']) and isset($_POST['gold']) and isset($_POST['silver'])){
$weapon_id = check($_GET['weapon_id']);
$gold = check($_POST['gold']);
$silver = check($_POST['silver']);

#-Проверка снаряжения-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id AND `runa` = 0 AND `state` = 0 AND `auction` = 0");
$sel_weapon_me->execute(array(':id' => $weapon_id, ':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() == 0) $error = 'Снаряжение не найдено!';
#-Кол-во занятых лотов-#
$sel_count_w = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `auction` = 1 AND `user_id` = :user_id");
$sel_count_w->execute(array(':user_id' => $user['id']));
$count_w  = $sel_count_w->fetch(PDO::FETCH_LAZY);
if(($user['lot']-$count_w[0]) <= 0) $error = 'Нет свободных лотов!';
#-Проверка что цифры-#
if(!preg_match('/^[0-9]+$/u',$_POST['gold'])) $error = 'Введены не цифры!';
if(!preg_match('/^[0-9]+$/u',$_POST['silver'])) $error = 'Введены не цифры!';

#-Если нет ошибок-#
if(!isset($error)){
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);		
$sel_weapon = $pdo->prepare("SELECT `id`, `gold`, `silver`, `no_magaz` FROM `weapon` WHERE `id` = :id");
$sel_weapon->execute(array(':id' => $weapon_me['weapon_id']));
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);	
#-Цена не больше той что в магазине-#
if($gold > 0 and $gold <= $weapon['gold'] and $silver > 0 and $silver <= $weapon['silver']){
#-Выставление на продажу-#
$upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `auction` = 1, `gold` = :gold, `silver` = :silver, `time` = :time WHERE `id` = :weapon_id LIMIT 1");
$upd_weapon_me->execute(array(':gold' => $gold, ':silver' => $silver, ':time' => time()+86400, ':weapon_id' => $weapon_id));
header('Location: /lot');
$_SESSION['ok'] = 'Выставлено!';
exit();	
}else{
header("Location: /lot?expose=$weapon_id");
$_SESSION['err'] = 'Ошибка цены!';
exit();
}
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