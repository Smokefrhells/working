<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
$head = 'Ошибка платежа';
require_once H.'system/head.php';
$sel_donate = $pdo->prepare("SELECT * FROM `donate` WHERE `user_id` = :user_id AND `statys` = 0");
$sel_donate->execute(array(':user_id' => $user['id']));
#-Если есть запись то перезаписываем-#
if($sel_donate-> rowCount() != 0){
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo"<img src='/style/images/body/error.png' alt='Ошибка'/>Возникла ошибка платежа!";
echo'</div>';
echo'</div>';
}
require_once H.'system/footer.php';
?>