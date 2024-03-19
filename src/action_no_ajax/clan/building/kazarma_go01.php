<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Пополнение казны клана золотом-#
switch($act){
case 'kash':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `gold`, `quatity_user` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане и есть права на прокачку-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или нет прав!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
#-Количество необходимого золота-#
if($clan['quatity_user'] < 40){
$gold = (($clan['quatity_user'] *25)+200);
}else{
$gold = (($clan['quatity_user'] *45)+500);	
}
#-Проверяем что уровень не максимальный-#
if($clan['quatity_user'] < 100){
#-Проверяем достаточно ли золота в клане-#
if($clan['gold'] >= $gold){
#-Отнимаем золото в клане-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = :gold, `quatity_user` = :quatity WHERE `id` = :clan_id");
$upd_clan->execute(array(':gold' => $clan['gold']-$gold, ':quatity' => $clan['quatity_user']+5, ':clan_id' => $clan['id']));	
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 7, ':log' => "Уровень Казарм повышен до ".($clan['quatity_user']+5)." человек", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/building/$clan[id]");
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Недостаточно золота в казне!';
exit();
}
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Уровень казарм максимальный!';
exit();	
}
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}
?>