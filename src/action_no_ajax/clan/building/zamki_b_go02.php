<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Замки-#
switch($act){
case 'kash':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `gold`, `silver`, `zashita`, `zashita_lvl` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане и есть права на прокачку-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или нет прав!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
#-Уровень прокачки-#
if($clan['zashita_lvl'] < 100){
$zashita = 20000*$clan['zashita_lvl'];
#-Прокачка за золото-#
if($clan['zashita_lvl'] == 10 or $clan['zashita_lvl'] == 20 or $clan['zashita_lvl'] == 30 or $clan['zashita_lvl'] == 40 or $clan['zashita_lvl'] == 50 or $clan['zashita_lvl'] == 60 or $clan['zashita_lvl'] == 70 or $clan['zashita_lvl'] == 80 or $clan['zashita_lvl'] == 90 or $clan['zashita_lvl'] == 100){
$gold = (($clan['zashita_lvl'] * 320)+800);
if($clan['gold'] >= $gold){
#-Отнимаем золото в клане-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = :gold, `zashita` = :zashita, `zashita_lvl` = :zashita_lvl WHERE `id` = :clan_id");
$upd_clan->execute(array(':gold' => $clan['gold']-$gold, ':zashita' => $clan['zashita']+$zashita, ':zashita_lvl' => $clan['zashita_lvl']+1, ':clan_id' => $clan['id']));	
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 7, ':log' => "Уровень Замков повышен.", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/building/$clan[id]");
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Недостаточно золота в казне!';
exit();
}
}else{
$silver = (($clan['zashita_lvl'] *30000)+2000);	
if($clan['silver'] >= $silver){
#-Отнимаем серебро в клане-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `silver` = :silver, `zashita` = :zashita, `zashita_lvl` = :zashita_lvl WHERE `id` = :clan_id");
$upd_clan->execute(array(':silver' => $clan['silver']-$silver, ':zashita' => $clan['zashita']+$zashita, ':zashita_lvl' => $clan['zashita_lvl']+1, ':clan_id' => $clan['id']));	
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 7, ':log' => "Уровень Замков повышен до ".($clan['zashita_lvl']+1)."", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/building/$clan[id]");
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Недостаточно серебра в казне!';
exit();
}
}
}else{
header("Location: /clan/building/$clan[id]");
$_SESSION['err'] = 'Уровень замков максимальный!';
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