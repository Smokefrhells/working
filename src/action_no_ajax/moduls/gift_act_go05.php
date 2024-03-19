<?php
require_once '../../system/system.php';
echo only_reg();
echo gift_level();
#-Дарим подарок-#
switch($act){
case 'give':
if(isset($_GET['u_id']) and isset($_GET['g_id'])){
$u_id = check($_GET['u_id']);
$g_id = check($_GET['g_id']);
$description = check($_POST['description']);

#-Проверка что подарок существует-#
$sel_gift = $pdo->prepare("SELECT * FROM `gift` WHERE `id` = :g_id");
$sel_gift->execute(array(':g_id' => $g_id));
if($sel_gift -> rowCount() == 0) $error = 'Подарок не существует!';
#-Проверка что получатель существует-#
$sel_users = $pdo->prepare("SELECT `id`, `level`, `ev_gift` FROM `users` WHERE `id` = :u_id AND `level` >= 10 AND `ev_gift` = 0");
$sel_users->execute(array(':u_id' => $u_id));
if($sel_users -> rowCount() == 0) $error = 'Получатель не найден или другая ошибка!';
#-Описание если есть-#
if(!empty($description)){
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['description'])) $error = 'Некорректное описание!';
if(mb_strlen($description) < 1) $error = 'Пустое описание!';
if(mb_strlen($description) > 300) $error = 'Слишком длинное описание!';
}else{
$description = 'Описание отсутствует';
}

#-Если нет ошибок-#
if(!isset($error)){
$gift = $sel_gift->fetch(PDO::FETCH_LAZY);
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Достаточно ли золота-#
if($user['gold'] >= $gift['gold']){
#-Добавляем подарок в базу-#
$ins_gift = $pdo->prepare("INSERT INTO `gift_users` SET `gift_id` = :gift_id, `description` = :description, `send_id` = :send_id, `recip_id` = :recip_id, `time` = :time");
$ins_gift->execute(array(':gift_id' => $gift['id'], ':description' => $description, ':send_id' => $user['id'], ':recip_id' => $all['id'], ':time' => time()));
#-Минусуем золото-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id");
$upd_users->execute(array(':gold' => $user['gold']-$gift['gold'], ':user_id' => $user['id']));
#-Записываем лог-#
$ins_log = $pdo->prepare("INSERT INTO `event_log` SET `type` = 10, `log` = :log, `user_id` = :all_id, `time` = :time");
$ins_log->execute(array(':log' => "Вы получили подарок от <a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a>", ':all_id' => $all['id'], ':time' => time()));
header("Location: /gift_give?id=$all[id]");
$_SESSION['ok'] = 'Подарок отправлен!';
exit();
}else{
header("Location: /gift_give?id=$all[id]");
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header("Location: /gift_give?id=$all[id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /gift_give?id=$all[id]");
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Удаление подарка-#
switch($act){
case 'del':
if(isset($_GET['g_id']) and isset($_GET['u_id'])){
$g_id = check($_GET['g_id']);
$u_id = check($_GET['u_id']);
#-Проверка что подарок существует-#
$sel_gift_u = $pdo->prepare("SELECT * FROM `gift_users` WHERE `id` = :g_id AND `recip_id` = :recip_id");
$sel_gift_u->execute(array(':g_id' => $g_id, ':recip_id' => $user['id']));
if($sel_gift_u -> rowCount() == 0) $error = 'Подарок не существует!';
#-Если нет ошибок-#
if(!isset($error)){
#-Удаляем подарок-#
$del_gift_u = $pdo->prepare("DELETE FROM `gift_users` WHERE `id` = :g_id");
$del_gift_u->execute(array(':g_id' => $g_id));
header("Location: /gift/$u_id");
exit();
}else{
header("Location: /gift/$u_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /gift/$u_id");
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>