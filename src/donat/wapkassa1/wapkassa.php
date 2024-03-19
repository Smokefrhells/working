<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
$head = 'Покупка золота';
require_once H.'system/head.php';
#-Покупка золота через WapKassa-#
echo'<div class="page">';
#-Форма выбора количества золота-#
if(!isset($_POST['gold'])){
echo'<center>';
echo'<form method="post" action="/wapkassa">';
echo"<input class='input_form' type='number' name='gold' min='100' value='100'/><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Продолжить"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
}else{
$gold = check($_POST['gold']); //Количество золота
$bonus_gold = round(($gold*0.25), 0); //Бонус золота
$summa = $gold/10; //Сумма платежа
$id = 1006; //ID площадки
$secret = 'G6H7J7KDJHk.;jesrt7l345tkljvhgt744'; //Секретное слово
$hash = strtoupper(hash("sha256",$id.$summa.$secret)); //Hash кода
#-Проверка на ошибки-#
if($gold < 100) $error = 'Минимальное кол-во золота 100!';
if($gold > 10000) $error = 'Максимальное кол-во золота за один раз 10000!';
#-Проверяем есть ли запись с такими данными-#
$ins_donate = $pdo->prepare("INSERT INTO `donate` SET `user_id` = :user_id, `summa` = :summa, `gold` = :gold, `time` = :time");
$ins_donate->execute(array(':user_id' => $user['id'], ':summa' => $summa, ':gold' => $gold, ':time' => time()));
$donate_id = $pdo->lastInsertId();
if(!isset($donate_id)) $error = 'Повторите попытку!';
#-Если нет ошибок-#
if(!isset($error)){
echo'<center>';
echo'<span class="gray">';
echo"Вы хотите купить: <span class='yellow'><img src='/style/images/many/gold.png' alt=''/>$gold</span> <span class='green'>(+$bonus_gold)</span><br/>";
echo"Стоимость: <span class='yellow'>$summa руб.</span>";
echo'</span>';
echo'</center>';
echo'<div style="padding-top: 3px;"></div>';
echo'<form method="POST" action="https://wapkassa.ru/merchant/payment">';
echo'<input type="hidden" name="WK_PAYMENT_SITE" value="'.$id.'">';
echo'<input type="hidden" name="WK_PAYMENT_AMOUNT" value="'.$summa.'">';
echo'<input type="hidden" name="WK_PAYMENT_COMM" value="Покупка '.$gold.' золота ID '.$user['id'].'">';
echo'<input type="hidden" name="WK_PAYMENT_HASH" value="'.$hash.'">';
echo'<input type="hidden" name="WK_PAYMENT_USER" value="'.$user['id'].'">';
echo'<input type="hidden" name="WK_PAYMENT_TOVAR" value="gold">';
echo'<input type="hidden" name="WK_PAYMENT_VALUE" value="'.$donate_id.'">';
echo'<input type="hidden" name="WK_PAYMENT_COUNT" value="'.$gold.'">';
echo'<input class="button_green_i" name="submit" type="submit"  value="Перейти к оплате"/>';
echo'</form>';
echo'<div style="padding-top: 3px;"></div>';
}else{
#-Вывод ошибки-#
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo"<img src='/style/images/body/error.png' alt=''/>$error";
echo'</div>';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/wapkassa"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'</div>';
}
}
echo'</div>';
require_once H.'system/footer.php';
?>