<?php
require_once '../../system/system.php';
echo only_reg();

#-Комментирование тикета-#
switch($act){
case 'com':
if(isset($_POST['msg']) and isset($_GET['support_id'])){
$msg = check($_POST['msg']);
$support_id = check($_GET['support_id']);

#-Сообщение-#
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 5000) $error = 'Слишком длинное сообщение!';
#-Админ или обычный игрок-#
if($user['prava'] == 1 or $user['prava'] == 3){
$sel_support = $pdo->prepare("SELECT * FROM `support` WHERE `support_id` = :support_id AND `close` != 1");
$sel_support->execute(array(':support_id' => $support_id));	
if($sel_support-> rowCount() == 0) $error = 'Тикет не найден!';		
}else{
$sel_support = $pdo->prepare("SELECT * FROM `support` WHERE `support_id` = :support_id AND `user_id` = :user_id AND `close` != 1");
$sel_support->execute(array(':support_id' => $support_id, ':user_id' => $user['id']));	
if($sel_support-> rowCount() == 0) $error = 'Тикет не найден!';
}

#-Если нет ошибок-#
if(!isset($error)){
$support = $sel_support->fetch(PDO::FETCH_LAZY);

#-Если админ-#
if($user['prava'] == 1 or $user['prava'] == 3){
#-Добавление комментария-#
$ins_support = $pdo->prepare("INSERT INTO `support` SET `category` = :category, `msg` = :msg, `user_id` = :user_id, `support_id` = :support_id, `system` = 1, `new` = 1, `time` = :time");
$ins_support->execute(array(':category' => $support['category'], ':msg' => $msg, ':user_id' => $support['user_id'], ':support_id' => $support['support_id'],  ':time' => time())); 
#-Пометка что есть ответы-#
$upd_support = $pdo->prepare("UPDATE `support` SET `new` = 1 WHERE `support_id` = :support_id");
$upd_support->execute(array(':support_id' => $support['support_id']));
#-Запись лога игроку-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => 'Есть новые ответы в Службе поддержки.', ':user_id' => $support['user_id'], ':time' => time()));
}else{
#-Добавление комментария-#
$ins_support = $pdo->prepare("INSERT INTO `support` SET `category` = :category, `msg` = :msg, `user_id` = :user_id, `support_id` = :support_id, `time` = :time");
$ins_support->execute(array(':category' => $support['category'], ':msg' => $msg, ':user_id' => $user['id'], ':support_id' => $support['support_id'],  ':time' => time())); 	
#-Пометка что прочитано-#
$upd_support = $pdo->prepare("UPDATE `support` SET `new` = 0 WHERE `user_id` = :user_id AND `support_id` = :support_id");	
$upd_support->execute(array(':user_id' => $user['id'], ':support_id' => $support['support_id']));
#-Выборка данных администрации-#
$sel_users_a = $pdo->query("SELECT `id`, `prava` FROM `users` WHERE `prava` = 1");
$users_a = $sel_users_a->fetch(PDO::FETCH_LAZY);
#-Запись лога админу-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => 'Есть новые вопросы в Службе поддержки.', ':user_id' => $users_a['id'], ':time' => time()));
#-Выборка данных поддержки-#
$sel_users_p = $pdo->query("SELECT `id`, `prava` FROM `users` WHERE `prava` = 3");
$users_p = $sel_users_p->fetch(PDO::FETCH_LAZY);
#-Запись лога поддержки-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => 'Есть новые вопросы в Службе поддержки.', ':user_id' => $users_p['id'], ':time' => time()));
}
header("Location: /support?support_id=$support[support_id]");
}else{
header('Location: /support');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /support');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>