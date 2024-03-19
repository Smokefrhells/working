<?php
require_once '../../system/system.php';
echo admod();
#-Блокировка игрока-#
switch($act){
case 'go':
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['ank_id']);

#-Проверка-#
$sel_users = $pdo->prepare("SELECT `id`, `prava`, `block`, `violation`, `cause` FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден!';
$ank = $sel_users->fetch(PDO::FETCH_LAZY);

if($ank['block'] == 0){
if(!preg_match('/[0-9]/',$_POST['day'])) $error = 'Только цифры!';
if(mb_strlen($_POST['cause']) < 1)  $error = 'Укажите причину блока!';
if(mb_strlen($_POST['cause']) > 255)  $error = 'Слишком длинное сообщение!';
}

#-Нет ошибок-#
if(!isset($error)){
if($ank['prava'] != 1 and $ank['prava'] != 2 and $ank['prava'] != 3){
#-Блок или разблок-#
if($ank['block'] == 0){
$day = check($_POST['day']);	
$day_ban = $day * 86400;
$cause = check($_POST['cause']); //Причина блока
$upd_users = $pdo->prepare("UPDATE `users` SET `block` = :block, `violation` = :violation, `cause` = :cause WHERE `id` = :ank_id");
$upd_users->execute(array(':block' => time()+$day_ban, ':violation' => $ank['violation']+1, ':cause' => $cause, ':ank_id' => $ank['id']));
header("Location: /hero/$ank[id]");
$_SESSION['ok'] = "Заблокирован на $day дн.!";
exit();
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `block` = :block, `violation` = :violation WHERE `id` = :ank_id");
$upd_users->execute(array(':block' => 0, ':violation' => $ank['violation']-1, ':ank_id' => $ank['id']));
header("Location: /hero/$ank[id]");
$_SESSION['ok'] = 'Разблокирован!';
exit();
}
}else{
header('Location: /');
$_SESSION['err'] = 'Ошибка!';
exit();	
}
}else{
header("Location: /admin_block?ank_id=$ank_id");
$_SESSION['err'] = $error;
exit();	
}
}else{
header('Location: /');
$_SESSION['err'] = 'Ошибка!';
exit();	
}
}
?>