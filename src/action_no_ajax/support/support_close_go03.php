<?php
require_once '../../system/system.php';
echo only_reg();

#-Закрытие тикета-#
switch($act){
case 'close':
if(isset($_GET['support_id']) and ($user['prava'] == 1 or $user['prava'] == 3)){
$support_id = check($_GET['support_id']);

#-Проверка существует ли тикет-#
$sel_support = $pdo->prepare("SELECT * FROM `support` WHERE `support_id` = :support_id");
$sel_support->execute(array(':support_id' => $support_id));	
if($sel_support-> rowCount() == 0) $error = 'Тикет не найден!';		

#-Если нет ошибок-#
if(!isset($error)){
$support = $sel_support->fetch(PDO::FETCH_LAZY);

if($support['close'] == 0){
#-Закрытие тикета-#
$upd_support = $pdo->prepare("UPDATE `support` SET `close` = 1 WHERE `support_id` = :support_id");	
$upd_support->execute(array(':support_id' => $support['support_id']));
}else{
#-Открытие тикета-#
$upd_support = $pdo->prepare("UPDATE `support` SET `close` = 0 WHERE `support_id` = :support_id");	
$upd_support->execute(array(':support_id' => $support['support_id']));
}
header("Location: /support?support_id=$support[support_id]");
}else{
header('Location: /support');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /support');
$_SESSION['err'] = 'Данные не переданы или нет прав!';
exit();
}
}
?>