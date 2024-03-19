<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Прокачка сокровищницы клана-#
switch($act){
case 'kash':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `gold`, `treasury_lvl`, `level` FROM `clan` WHERE `id` = :clan_id AND `level` >= 10");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден или слишком мал уровень!';
#-Проверяем что мы состоим в этом клане и есть права на прокачку-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или нет прав!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
$gold = (($clan['amulet_lvl'] *400)+1000);
#-Проверяем что уровень не максимальный-#
if($clan['treasury_lvl'] < 10){
#-Проверяем достаточно ли золота в клане-#
if($clan['gold'] >= $gold){
#-Отнимаем золото в клане-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = :gold, `treasury_lvl` = :treasury_lvl WHERE `id` = :clan_id");
$upd_clan->execute(array(':gold' => $clan['gold']-$gold, ':treasury_lvl' => $clan['treasury_lvl']+1, ':clan_id' => $clan['id']));	
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 7, ':log' => "Уровень Сокровищницы повышен до ".($clan['treasury_lvl']+1)."", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/building/$clan[id]");
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Недостаточно золота в казне!';
exit();
}
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Уровень сокровищницы максимальный!';
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