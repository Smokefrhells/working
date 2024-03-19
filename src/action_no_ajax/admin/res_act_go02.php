<?php
require_once '../../system/system.php';

#-Зачисление ресурсов-#
switch($act){
case 'res':
if(isset($_POST['ank_id']) and isset($_POST['quatity']) and isset($_POST['type']) and $user['prava'] == 1){
$ank_id = check($_POST['ank_id']);
$quatity = check($_POST['quatity']);
$type = check($_POST['type']);
if(!preg_match('/^[0-9]+$/u',$_POST['quatity'])) $error = 'Некорректная сума!';
#-Если нет ошибок-#
if(!isset($error)){
#-Проверяем есть ли игрок-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() != 0){
$ank = $sel_users->fetch(PDO::FETCH_LAZY);
#-Ключи-#
if($type == 1){
$upd_users = $pdo->prepare("UPDATE `users` SET `key` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['key']+$quatity, ':ank_id' => $ank['id']));
}
#-Серебро-#
if($type == 2){
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['silver']+$quatity, ':ank_id' => $ank['id']));
}
#-Золото-#
if($type == 3){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['gold']+$quatity, ':ank_id' => $ank['id']));
}
#-Кристаллы-#
if($type == 4){
$upd_users = $pdo->prepare("UPDATE `users` SET `crystal` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['crystal']+$quatity, ':ank_id' => $ank['id']));
}
#-Турнирные ключи-#
if($type == 5){
$upd_users = $pdo->prepare("UPDATE `users` SET `kluch` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['kluch']+$quatity, ':ank_id' => $ank['id']));
}
#-Тыква-#
if($type == 6){
$upd_users = $pdo->prepare("UPDATE `users` SET `eventItem` = :quatity WHERE `id` = :ank_id");
$upd_users->execute(array(':quatity' => $ank['eventItem']+$quatity, ':ank_id' => $ank['id']));
}
header('Location: /admin_res');
$_SESSION['ok'] = 'Ресурсы зачислены!';
exit();
}else{
header('Location: /admin_gold');
$_SESSION['ok'] = 'Игрок не найден!';
exit();	
}
}else{
header('Location: /admin_gold');
$_SESSION['ok'] = $error;
exit();	
}
}else{
header('Location: /admin');
$_SESSION['ok'] = 'Ошибка!';
exit();	
}
}
?>