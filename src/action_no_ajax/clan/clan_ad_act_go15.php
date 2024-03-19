<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
/*Высылаем объявление игрокам*/
switch($act){
case 'send':
if(isset($_POST['ad']) and isset($_GET['clan_id'])){
$ad = check($_POST['ad']); //Объявление
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем объявление-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['ad'])) $error = 'Некорректное объявление!';
if(mb_strlen($ad) > 2000) $error = 'Объявление в пределах 2000 символов';

#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `user_id`, `clan_id`, `prava` FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или у вас нет прав!';
#-Если нет ошибок-#
if(!isset($error)){
	
#-Записываем объявление клану-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `ad` = :ad, `ad_time` = :time WHERE `id` = :clan_id LIMIT 1");
$upd_clan->execute(array(':ad' => "$user[nick]: $ad", ':time' => time(), ':clan_id' => $clan_id));
#-Записываем объявление в историю клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 10, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a>: $ad", ':clan_id' => $clan_id, ':time' => time())); 
#-Выборка игроков клана-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id` FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u->execute(array(':clan_id' => $clan_id));
while($clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY)){
$upd_users = $pdo->prepare("UPDATE `users` SET `clan_ad` = 1 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $clan_u['user_id']));		
}
header("Location: /clan/ad/$clan_id");
}else{
header("Location: /clan/ad/$clan_id");
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