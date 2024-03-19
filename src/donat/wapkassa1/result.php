<?php
require_once '../../system/system.php';
#-СТРАНИЦА ЗАЧИСЛЕНИЯ ЗОЛОТА-#
#-Получаем данные-#
if(isset($_POST['WK_PAY_AMOUNT']) and isset($_POST['WK_PAY_TIME']) and isset($_POST['WK_PAY_HASH'])){
#-Данные платежа-#
$id = 4026; //ID площадки
$secret = 'cGoXRuwmwXJY4aGC'; //Секретное слово
$hash = strtoupper(hash("sha256",$id.$_POST['WK_PAY_AMOUNT'].$_POST['WK_PAY_TIME'].$secret)); //Hash код
if($hash!=$_POST['WK_PAY_HASH']) exit('NO HACK!');

$user_id = abs(intval($_POST['WK_PAY_USER'])); //ID игрока
$gold = abs(intval($_POST['WK_PAY_COUNT'])); //Количество золота
$summa = $gold/10; //Сумма платежа
$type = $_POST['WK_PAY_TOVAR']; //Тип товара

#-Проверяем что есть данные платежа в базе-#
$sel_donate = $pdo->prepare("SELECT * FROM `donate` WHERE `user_id` = :user_id AND `gold` = :gold AND `statys` = 0");
$sel_donate->execute(array(':user_id' => $user_id, ':gold' => $gold));
if($sel_donate-> rowCount() == 0) exit('NO DONATE TABLE!');
#-Проверяем что существует такой игрок-#
$sel_users = $pdo->prepare("SELECT `id`, `gold`, `clan_id`, `pol`, `nick`, `ref_id`, `level` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $user_id));
if($sel_donate-> rowCount() == 0) exit('NO USER ID!');

#-Если нет ошибок-#
if(!isset($error)){
$all = $sel_users->fetch(PDO::FETCH_LAZY); //Игрок
$donate = $sel_donate->fetch(PDO::FETCH_LAZY); //Платеж
$bonus_gold = round(($donate['gold']*0.25), 0); //Бонусное золото
$all_gold = $donate['gold']+$bonus_gold; //Итоговое золото

#-Акция на x2 золото-#
$sel_stock_2 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 2");
if($sel_stock_2-> rowCount() != 0){
$all_gold = $donate['gold']+$bonus_gold+$donate['gold']; //Итоговое золото + такое самое количество
}
#-Акция на x3 золото-#
$sel_stock_3 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 3");
if($sel_stock_3-> rowCount() != 0){
$all_gold = ($donate['gold']*3)+$bonus_gold; //Итоговое золото * 3
}

#-Зачисляем золото-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id");
$upd_users->execute(array(':gold' => $all['gold']+$all_gold, ':user_id' => $all['id']));
#-Есть ли реферер у игрока-#	
if($all['ref_id'] != 0){
#-Есть ли такой реферер-#	
$sel_users_r = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :ref_id");
$sel_users_r->execute(array(':ref_id' => $all['ref_id']));
if($sel_users_r-> rowCount() != 0){
$ref_gold = round(($donate['gold']*0.15), 0); //Золото которое начисляем рефереру
#-Зачисляем золото-#
$upd_users_r = $pdo->prepare("UPDATE `users` SET `gold` = `gold` + :gold, `referer_gold` = `referer_gold` + :gold WHERE `id` = :id");
$upd_users_r->execute(array(':gold' => $ref_gold, ':id' => $all['ref_id']));
}
}
#-Ставим статус зачислено-#
$upd_donate = $pdo->prepare("UPDATE `donate` SET `statys` = 1, `time` = :time WHERE `id` = :donate_id");
$upd_donate->execute(array(':time' => time(), ':donate_id' => $donate['id']));

#-Питомец в подарок-#
$sel_stock_8 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 8");
if($sel_stock_8-> rowCount() != 0 and ($donate['gold'] >= 750 or $donate['gold'] >= 1500)){
if($donate['gold'] >= 1500){
$pets_id = 4;
$pets_name = 'Змееглав';	
}
if($donate['gold'] >= 3000){	
$pets_id = 5;
$pets_name = 'Гелаус';
}
#-Запись питомца-#
$ins_pets_me = $pdo->prepare("INSERT INTO `pets_me` SET `pets_id` = :pets_id, `user_id` = :user_id");
$ins_pets_me->execute(array(':pets_id' => $pets_id, ':user_id' => $all['id']));
#-Запись лога-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы успешно получили: <img src='/style/images/many/gold.png' alt=''/>$all_gold золота и <img src='/style/images/body/pets.png' alt=''/>Питомца $pets_name", ':user_id' => $all['id'], ':time' => time()));

}else{
#-Записываем лог-#
$ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
$ins_event->execute(array(':type' => 8, ':log' => "Вы успешно получили: <img src='/style/images/many/gold.png' alt=''/>$all_gold золота", ':user_id' => $all['id'], ':time' => time()));
}

#-Клановая акция-#
$sel_stock_1 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 1");
if($sel_stock_1-> rowCount() != 0){
if($all['clan_id'] != 0){
#-Выборка клана-#
$sel_clan = $pdo->prepare("SELECT `id`, `gold` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $all['clan_id']));
if($sel_clan-> rowCount() != 0){
#-Зачисляем золото в казну клана-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = `gold` + :gold WHERE `id` = :clan_id");
$upd_clan->execute(array(':gold' => $donate['gold'], ':clan_id' => $all['clan_id']));
#-Лог клана-#
if($all['pol'] == 1){$b = 'пополнил';}else{$b = 'пополнила';}
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 3, ':log' => "<a href='/hero/$all[id]' style='display:inline;text-decoration:underline;padding:0px;'>$all[nick]</a> $b казну: <img src='/style/images/many/gold.png' alt=''/>$donate[gold]", ':clan_id' => $all['clan_id'], ':time' => time())); 
}
}
}
}
}
?>