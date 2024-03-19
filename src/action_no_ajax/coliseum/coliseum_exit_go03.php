<?php
require_once '../../system/system.php';
echo only_reg();
echo coliseum_level();

#-Выход из очереди колизея-#
switch($act){
case 'exit_osh':
#-Проверка что игрок стоит в очереди-#
$sel_coliseum = $pdo->prepare("SELECT `id`, `user_id`, `statys` FROM `coliseum` WHERE `user_id` = :user_id AND `statys` = 0");
$sel_coliseum->execute(array(':user_id' => $user['id']));
if($sel_coliseum->rowCount() == 0) $error = 'Выход невозможен!';

#-Нет ошибок-#
if(!isset($error)){
#-Удаление боя-#
$del_coliseum = $pdo->prepare("DELETE FROM `coliseum` WHERE `user_id` = :user_id");
$del_coliseum->execute(array(':user_id' => $user['id']));
header('Location: /coliseum');
}else{
header('Location: /coliseum');
$_SESSION['err'] = $error;
exit();
}
}

#-Выход из статистики колизея-#
switch($act){
case 'exit_stk':
#-Проверка что игрок участвовал в этом бою-#
$sel_coliseum = $pdo->prepare("SELECT `id`, `user_id`, `statys` FROM `coliseum` WHERE `user_id` = :user_id AND `statys` = 3");
$sel_coliseum->execute(array(':user_id' => $user['id']));
if($sel_coliseum->rowCount() == 0) $error = 'Данные не найдены!';

#-Нет ошибок-#
if(!isset($error)){
#-Удаление боя-#
$del_coliseum = $pdo->prepare("DELETE FROM `coliseum` WHERE `user_id` = :user_id");
$del_coliseum->execute(array(':user_id' => $user['id']));
header('Location: /coliseum');
}else{
header('Location: /coliseum');
$_SESSION['err'] = $error;
exit();
}
}
?>