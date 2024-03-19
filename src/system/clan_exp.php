<?php
require_once 'system.php';
#-Только если есть клан-#
if($user['clan_id'] != 0){
#-Засчитываем в клан опыт-#
if($user['exp_clan'] > 0){ //Если есть опыт то записываем его в клан
#-Если есть клан-#
$sel_clan_ue = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_ue->execute(array(':user_id' => $user['id']));
if($sel_clan_ue-> rowCount() != 0){
$clan_ue = $sel_clan_ue->fetch(PDO::FETCH_LAZY);

#-Выборка клана-#	
$sel_clan_e = $pdo->prepare("SELECT `id`, `level`, `exp`, `figur`, `time` FROM `clan` WHERE `id` = :clan_id");
$sel_clan_e->execute(array(':clan_id' => $clan_ue['clan_id']));
$clan_e = $sel_clan_e->fetch(PDO::FETCH_LAZY);
#-Прибавляем опыт клану-#
$upd_clan_e = $pdo->prepare("UPDATE `clan` SET `exp` = :exp WHERE `id` = :clan_id LIMIT 1");
$upd_clan_e->execute(array(':exp' => $clan_e['exp']+$user['exp_clan'], ':clan_id' => $clan_e['id']));
#-Прибавляем опыт игроку клана-#
$upd_clan_ue = $pdo->prepare("UPDATE `clan_users` SET `exp` = :exp WHERE `user_id` = :user_id AND `clan_id` = :clan_id LIMIT 1");
$upd_clan_ue->execute(array(':exp' => $clan_ue['exp']+$user['exp_clan'], ':user_id' => $user['id'], ':clan_id' => $clan_e['id']));	
#-Обнуляем опыт игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `exp_clan` = :exp_clan WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':exp_clan' => 0, ':id' => $user['id']));

$level_clan = file(H."/system/clan_exp.txt");
$exp_clan = trim($level_clan[$clan_e['level']]);
#-Получает клан новый уровень или нет-#
if($clan_e['exp'] >= $exp_clan AND $clan_e['level'] < 150){
$exp_ost = $clan_e['exp']-$exp_clan;
$upd_clan_e2 = $pdo->prepare("UPDATE `clan` SET `exp` = :exp_ost, `level` = :level WHERE `id` = :clan_id LIMIT 1");
$upd_clan_e2->execute(array(':exp_ost' => $exp_ost, ':level' => $clan_e['level']+1, ':clan_id' => $clan_e['id']));	
}
#-Статуэтки-#
if($clan_e['level'] == 150 and $clan_e['exp'] >= 1000000){
$exp_ost = $clan_e['exp']-1000000;
$upd_clan = $pdo->prepare("UPDATE `clan` SET `figur` = :figur, `exp` = :exp_ost WHERE `id` = :id LIMIT 1");
$upd_clan ->execute(array(':figur' => $clan_e['figur']+1, ':exp_ost' => $exp_ost, ':id' => $clan_e['id'])); 
}
}
}
}
?>