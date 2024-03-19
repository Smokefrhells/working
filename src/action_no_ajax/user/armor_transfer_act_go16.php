<?php
require_once '../../system/system.php';
echo only_reg();
echo save();

#-Надеваем снаряжение-#
switch($act){
case 'trans':
if(isset($_GET['ank_id']) and isset($_POST['weapon_id'])){
$ank_id = check($_GET['ank_id']);
$weapon_id = $_POST['weapon_id'];

#-Проверка игрока которому передается снаряжение-#
$sel_users = $pdo->prepare("SELECT `id`, `level` FROM `users` WHERE `id` = :ank_id AND `level` >= 10");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() == 0) $error = 'Ошибка передачи!';
#-Уровень игрока-#
if($user['level'] < 60) $error = 'Недостаточный уровень!';

#-Нет ошибок-#
if(!isset($error)){
$N = count($weapon_id);
for($i=0; $i < $N; $i++)
{
$upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `user_id` = :ank_id WHERE `user_id` = :user_id AND `state` = 0 AND `runa` = 0 AND `auction` = 0 AND `id` = :weapon_id");	
$upd_weapon_me->execute(array(':ank_id' => $ank_id, ':user_id' => $user['id'], ':weapon_id' => $weapon_id[$i]));
}
header("Location: /armor_transfer?ank_id=$ank_id");
}else{
header("Location: /armor_transfer?ank_id=$ank_id");
$_SESSION['err'] = $error;
exit();	
}	
}else{
header('Location: /armor_transfer');
$_SESSION['err'] = 'Данные не переданы!';
exit();	
}
}
?>