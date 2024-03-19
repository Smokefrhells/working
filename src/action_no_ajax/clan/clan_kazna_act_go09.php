<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
#-Пополнение казны клана золотом-#
switch($act){
case 'repl_gold':
if(isset($_GET['clan_id']) and isset($_POST['gold'])){
$clan_id = check($_GET['clan_id']); //ID клана
$gold = check($_POST['gold']); //Золото
#-Проверяем что только цифры-#
if(!preg_match('/^[0-9]+$/u',$_POST['gold'])) $error = 'Введите значение!';
#-Достаточно ли золота у игрока-#
if($user['gold'] < $gold) $error = 'Недостаточно золота!';
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `gold`, `treasury_lvl` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); //Игрок клана
$gold_lvl = (((($user['level'])*5)+25)+$clan['treasury_lvl']*200);
$gold_ost = $gold_lvl - $clan_u['gold_t'];
#-Проверяем сколько закинуто золота-#
if($clan_u['gold_t'] < $gold_lvl){
#-Проверяем кол-во золота не превышает границы-#
if($gold <= $gold_ost){
#-отнимаем золото у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id");
$upd_users->execute(array(':gold' => $user['gold']-$gold, ':id' => $user['id']));
#-Пополняем казну клана-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = :gold WHERE `id` = :clan_id");
$upd_clan->execute(array(':gold' => $clan['gold']+$gold, ':clan_id' => $clan['id']));
$gold_time = $clan_u['gold_t']+$gold;
if($gold_time == $gold_lvl){
#-Прибавляем к данным игрока и ставим время-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `gold` = :gold, `gold_t` = :gold_t, `gold_time` = :time WHERE `id` = :clan_u_id");
$upd_clan_u->execute(array(':gold' => $clan_u['gold']+$gold, ':gold_t' => $clan_u['gold_t']+$gold, ':time' => time()+43200, ':clan_u_id' => $clan_u['id']));
}else{
#-Прибавляем к данным игрока-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `gold` = :gold, `gold_t` = :gold_t WHERE `id` = :clan_u_id");
$upd_clan_u->execute(array(':gold' => $clan_u['gold']+$gold, ':gold_t' => $clan_u['gold_t']+$gold, ':clan_u_id' => $clan_u['id']));	
}
#-Лог-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 3, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> пополнил(а) казну: <img src='/style/images/many/gold.png' alt=''/>$gold", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/kazna/$clan[id]");
}else{
header("Location: /clan/kazna/$clan[id]");
$_SESSION['err'] = 'Доступно: '.$gold_ost.' золота';
exit();
}
}else{
header("Location: /clan/kazna/$clan[id]");
$_SESSION['err'] = 'Достигнут лимит!';
exit();	
}
}else{
header("Location: /clan/kazna/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}

#-Пополнение казны клана серебром-#
switch($act){
case 'repl_silver':
if(isset($_GET['clan_id']) and isset($_POST['silver'])){
$clan_id = check($_GET['clan_id']); //ID клана
$silver = check($_POST['silver']); //Серебро
#-Проверяем что только цифры-#
if(!preg_match('/^[0-9]+$/u',$_POST['silver'])) $error = 'Введите значение!';
#-Достаточно ли золота у игрока-#
if($user['silver'] < $silver) $error = 'Недостаточно серебра!';
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `silver`, `treasury_lvl` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY); //Клан
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); //Игрок клана
$silver_lvl = ((($user['level']*10000)+$user['level']*35)+$clan['treasury_lvl']*100000);
$silver_ost = $silver_lvl - $clan_u['silver_t'];
#-Проверяем сколько закинуто золота-#
if($clan_u['silver_t'] < $silver_lvl){
#-Проверяем кол-во золота не превышает границы-#
if($silver <= $silver_ost){
#-отнимаем серебро у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :id");
$upd_users->execute(array(':silver' => $user['silver']-$silver, ':id' => $user['id']));
#-Пополняем казну клана-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `silver` = :silver WHERE `id` = :clan_id");
$upd_clan->execute(array(':silver' => $clan['silver']+$silver, ':clan_id' => $clan['id']));
$silver_time = $clan_u['silver_t']+$silver;
if($silver_time == $silver_lvl){
#-Прибавляем к данным игрока и ставим время-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `silver` = :silver, `silver_t` = :silver_t, `silver_time` = :time WHERE `id` = :clan_u_id");
$upd_clan_u->execute(array(':silver' => $clan_u['silver']+$silver, ':silver_t' => $clan_u['silver_t']+$silver, ':time' => time()+43200, ':clan_u_id' => $clan_u['id']));
}else{
#-Прибавляем к данным игрока-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `silver` = :silver, `silver_t` = :silver_t WHERE `id` = :clan_u_id");
$upd_clan_u->execute(array(':silver' => $clan_u['silver']+$silver, ':silver_t' => $clan_u['silver_t']+$silver, ':clan_u_id' => $clan_u['id']));
}
#-Лог-#
if($user['pol'] == 1){$b = 'пополнил';}else{$b = 'пополнила';}
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 3, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> $b казну: <img src='/style/images/many/silver.png' alt='Монеты'/>$silver", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/kazna/$clan[id]");
}else{
header("Location: /clan/kazna/$clan[id]");
$_SESSION['err'] = 'Доступно: '.$silver_ost.' серебра';
exit();
}
}else{
header("Location: /clan/kazna/$clan[id]");
$_SESSION['err'] = 'Достигнут лимит!';
exit();	
}
}else{
header("Location: /clan/kazna/$clan_id");
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