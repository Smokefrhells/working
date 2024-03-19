<?php
require_once '../../system/system.php';
echo only_reg();
echo exchanger_level();

#-Обмен золота на серебро-#
switch($act){
case 'silver':
if(isset($_GET['obm'])){
$obm = check($_GET['obm']);
#-Выгодное предложение обмена-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 7");
if($sel_stock-> rowCount() != 0){
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);
if($obm == 1){
$silver_obm_1 = round((10000 + ((10000 * $stock['prosent'])/100)), 0);
}
if($obm == 2){
$silver_obm_2 = round((50000 + ((50000 * $stock['prosent'])/100)), 0);
}
if($obm == 3){
$silver_obm_3 = round((100000 + ((100000 * $stock['prosent'])/100)), 0);
}
}else{
if($obm == 1){$silver_obm_1 = 10000;}
if($obm == 2){$silver_obm_2 = 50000;}
if($obm == 3){$silver_obm_3 = 100000;}
}
#-Проверяем тип обмена-#
if($obm == 1){
#-Достаточно ли золота-#
if($user['gold'] >= 10){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-10, ':silver' => $user['silver']+$silver_obm_1, ':id' => $user['id']));	
header('Location: /exchanger');
exit();
}else{
header('Location: /exchanger');
exit();
}
}
#-Проверяем тип обмена-#
if($obm == 2){
#-Достаточно ли золота-#
if($user['gold'] >= 50){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-50, ':silver' => $user['silver']+$silver_obm_2, ':id' => $user['id']));
header('Location: /exchanger');
exit();	
}else{
header('Location: /exchanger');
exit();
}
}
#-Проверяем тип обмена-#
if($obm == 3){
#-Достаточно ли золота-#
if($user['gold'] >= 100){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']-100, ':silver' => $user['silver']+$silver_obm_3, ':id' => $user['id']));
header('Location: /exchanger');
exit();
}else{
header('Location: /exchanger');
exit();
}
}
}else{
header('Location: /exchanger');
$_SESSION['err'] = 'Данные не получены!';
exit();
}
}

#-Обмен серебра на золото-#
switch($act){
case 'gold':
#-Проверяем что не стоит время-#
if($user['obmenik_time'] == 0){
$silver = $user['level'] *1000;
$gold = $user['level'];
#-Достаточно ли серебра-#
if($user['silver'] >= $silver){
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `gold` = :gold, `obmenik_time` = :time WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']-$silver, ':gold' => $user['gold']+$gold, ':time' => time()+86400, ':id' => $user['id']));
header('Location: /exchanger');
exit();
}else{
header('Location: /exchanger');
exit();
}
}else{
header('Location: /exchanger');
$_SESSION['err'] = 'Приходите позже!';
exit();	
}
}
?>