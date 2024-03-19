<?php
require_once '../../system/system.php';
echo only_reg();

#-Создание тикета в службе поддержки-#
switch($act){
case 'add':
if(isset($_POST['msg']) and isset($_POST['category'])){
$msg = check($_POST['msg']);
$category_i = check($_POST['category']);
$support_id = $user['id']+(rand(1, 999));

#-Сообщение-#
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 5000) $error = 'Слишком длинное сообщение!';
#-Категория-#
if(!in_array($category_i, array('1', '2', '3', '4', '5'))) $error = 'Такой категории не существует!';

#-Если нет ошибок-#
if(!isset($error)){
#-Категория-#
if($category_i == 1) $category = 'Ошибки и баги';	
if($category_i == 2) $category = 'Вопросы по оплате';
if($category_i == 3) $category = 'Помощь по игре';	
if($category_i == 4) $category = 'Обжалование Молчанки/Блока';	
if($category_i == 5) $category = 'Другое';		
#-Запись тикета-#
$ins_support = $pdo->prepare("INSERT INTO `support` SET `category` = :category, `msg` = :msg, `user_id` = :user_id, `support_id` = :support_id, `time` = :time");
$ins_support->execute(array(':category' => $category, ':msg' => $msg, ':user_id' => $user['id'], ':support_id' => $support_id,  ':time' => time())); 
#-Выборка данных администрации-#
$sel_users_a = $pdo->query("SELECT `id`, `prava` FROM `users` WHERE `prava` = 1");
$users_a = $sel_users_a->fetch(PDO::FETCH_LAZY);
#-Запись лога админу-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => 'Есть новые тикеты в Службе поддержки.', ':user_id' => $users_a['id'], ':time' => time()));

#-Выборка данных поддержки-#
$sel_users_p = $pdo->query("SELECT `id`, `prava` FROM `users` WHERE `prava` = 3");
$users_p = $sel_users_p->fetch(PDO::FETCH_LAZY);
#-Запись лога поддержки-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => 'Есть новые тикеты в Службе поддержки.', ':user_id' => $users_p['id'], ':time' => time()));
header('Location: /support');
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