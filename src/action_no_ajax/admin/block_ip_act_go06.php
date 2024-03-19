<?php
require_once '../../system/system.php';
echo admod();
#-Блокировка ip пользователя-#
switch($act){
case 'ip':
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['ank_id']);
#-Проверяем есть ли игрок и права равны 0-#
$sel_users = $pdo->prepare("SELECT `id`, `prava`, `ip` FROM `users` WHERE `id` = :ank_id AND `prava` = 0");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден или нет прав!';

#-Нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Есть такой ip в блоке или нет-#
$sel_ip_block = $pdo->prepare("SELECT * FROM `ip_block` WHERE `ip` = :ip");
$sel_ip_block->execute(array(':ip' => $all['ip']));
if($sel_ip_block -> rowCount() == 0){
#-Добавляем ip-#
$ins_ip_block = $pdo->prepare("INSERT INTO `ip_block` SET `ip` = :ip");
$ins_ip_block->execute(array(':ip' => $all['ip']));
header("Location: /hero/$all[id]");
$_SESSION['ok'] = 'IP заблокирован!';
exit();
}else{
#-Удаляем ip-#
$del_ip_block = $pdo->prepare("DELETE FROM `ip_block` WHERE `ip` = :ip");
$del_ip_block->execute(array(':ip' => $all['ip']));
header("Location: /hero/$all[id]");
$_SESSION['ok'] = 'IP разблокирован!';
exit();
}

}else{
header('Location: /');
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