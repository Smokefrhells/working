<?php
require_once '../../system/system.php';
#-Скрытие игрока-#
switch($act){
case 'hidden':
if(isset($_GET['ank_id']) and ($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3)){
$ank_id = check($_GET['ank_id']);
#-Проверяем есть ли игрок-#
$sel_users = $pdo->prepare("SELECT `id`, `prava`, `time_online` FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() == 0) $error = 'Игрок не найден!';
	
#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$upd_users = $pdo->prepare("UPDATE `users` SET `time_online` = :time_online WHERE `id` = :all_id");
$upd_users->execute(array(':time_online' => $all['time_online']-3600, ':all_id' => $all['id']));
header("Location: /hero/$all[id]");
$_SESSION['ok'] = 'Игрок скрыт!';
exit();

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