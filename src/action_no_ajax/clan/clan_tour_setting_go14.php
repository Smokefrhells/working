<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();

#-Смена награды в турнире опыта-#
switch($act){
case 'tour_exp':
if(isset($_POST['mesto_1']) and isset($_POST['mesto_2']) and isset($_POST['mesto_3']) and isset($_POST['mesto_all']) and isset($_GET['clan_id'])){
$mesto_1 = check($_POST['mesto_1']); //1 место
$mesto_2 = check($_POST['mesto_2']); //2 место
$mesto_3 = check($_POST['mesto_3']); //3 место
$mesto_all = check($_POST['mesto_all']); //Остальные места
$clan_id = check($_GET['clan_id']); //ID клана

#-Проверяем что ввод правильный-#
if(!preg_match('/^[0-9]+$/u',$_POST['mesto_1'])) $error = 'Введите цифры!';
if($mesto_1 > 2000) $error = '1 место максимум 2000!';
if(!preg_match('/^[0-9]+$/u',$_POST['mesto_2'])) $error = 'Введите цифры!';
if($mesto_2 > 1500) $error = '2 место максимум 1500!';
if($mesto_2 > $mesto_1) $error = 'Награда за 2 место больше 1!';
if(!preg_match('/^[0-9]+$/u',$_POST['mesto_3'])) $error = 'Введите цифры!';
if($mesto_3 > 1000) $error = '3 место максимум 1000!';
if($mesto_3 > $mesto_2) $error = 'Награда за 3 место больше 2!';
if(!preg_match('/^[0-9]+$/u',$_POST['mesto_all'])) $error = 'Введите цифры!';
if($mesto_all > 500) $error = 'Остальные места максимум 500!';
if($mesto_all > $mesto_3) $error = 'Награда за остальные места больше чем за 3!';

#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `amulet_lvl` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане и есть права основателя-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` = 4");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или у вас нет прав!';

#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
if($clan['amulet_lvl'] >= 4){
$mesto_all = $mesto_all;
}else{
$mesto_all = 0;
}
$upd_clan = $pdo->prepare("UPDATE `clan` SET `tour_exp_1` = :tour_exp_1, `tour_exp_2` = :tour_exp_2, `tour_exp_3` = :tour_exp_3, `tour_exp_all` = :tour_exp_all WHERE `id` = :clan_id");
$upd_clan->execute(array(':tour_exp_1' => $mesto_1, ':tour_exp_2' => $mesto_2, ':tour_exp_3' => $mesto_3, ':tour_exp_all' => $mesto_all, ':clan_id' => $clan_id,));
header("Location: /clan/tour_exp/$clan_id?tour_exp_setting=on");
}else{
header("Location: /clan/tour_exp/$clan_id?tour_exp_setting=on");
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