<?php
require_once '../../../system/system.php';
echo only_reg();
echo save();

#-Колесо фортуны-#
switch($act){
case 'go':
if($user['fortuna'] == 0){
$gold = $user['gold']-0;		
}else{
$gold = $user['gold']-($user['fortuna']*25);	
}	

#-Достаточно ли золота-#	
if($user['gold'] >= ($user['fortuna']*25)){
	
#-Минус золота и запись фортуны-#
$upd_users_g = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `fortuna` = :fortuna WHERE `id` = :user_id");
$upd_users_g->execute(array(':gold' => $gold, ':fortuna' => $user['fortuna']+1, ':user_id' => $user['id']));
		
$rand_f = rand(0, 100);

#-Награда (50 золота, 25К серебра, 10К кристаллов, Обычный сундук)-#
if($rand_f <= 60){
$rand_1 = rand(1, 4);

#-50 золота-#
if($rand_1 == 1){
$nagrada = '<img src="/style/images/many/gold.png" alt=""/>50';
$koleso = 'koleso_1';
$upd_users_1 = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users_1->execute(array(':gold' => $gold+50, ':user_id' => $user['id']));
}
#-25К серебра-#
if($rand_1 == 2){
$nagrada = '<img src="/style/images/many/silver.png" alt=""/>25К';
$koleso = 'koleso_6';
$upd_users_2 = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");
$upd_users_2->execute(array(':silver' => $user['silver']+25000, ':user_id' => $user['id']));
}
#-10К кристаллов-#
if($rand_1 == 3){
$nagrada = '<img src="/style/images/many/crystal.png" alt=""/>10К';
$koleso = 'koleso_3';
$upd_users_3 = $pdo->prepare("UPDATE `users` SET `crystal` = :crystal WHERE `id` = :user_id LIMIT 1");
$upd_users_3->execute(array(':crystal' => $user['crystal']+10000, ':user_id' => $user['id']));	
}
#-Обычный сундук-#
if($rand_1 == 4){
$nagrada = '<img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
$koleso = 'koleso_12';
$ins_chest_1 = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest_1->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
}	
}

#-Награда (75 золота, 50К серебра, Редкий сундук)-#
if($rand_f >= 60){
$rand_2 = rand(5, 7);

#-75 золота-#
if($rand_2 == 5){
$nagrada = '<img src="/style/images/many/gold.png" alt=""/>75';
$koleso = 'koleso_9';
$upd_users_4 = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users_4->execute(array(':gold' => $gold+75, ':user_id' => $user['id']));		
}
#-50К серебра-#
if($rand_2 == 6){
$nagrada = '<img src="/style/images/many/silver.png" alt=""/>50К';
$koleso = 'koleso_4';
$upd_users_5 = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");
$upd_users_5->execute(array(':silver' => $user['silver']+50000, ':user_id' => $user['id']));
}
#-Редкий сундук-#	
if($rand_2 == 7){
$nagrada = '<img src="/style/images/body/chest.png" alt=""/>Редкий сундук';
$koleso = 'koleso_8';
$ins_chest_2 = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest_2->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 		
}		
}

#-Награда (150 золота, 100К серебра, Золотой сундук)-#
if($rand_f >= 86){
$rand_3 = rand(8, 10);

#-150 золота-#
if($rand_3 == 8){
$nagrada = '<img src="/style/images/many/gold.png" alt=""/>150';
$koleso = 'koleso_7';
$upd_users_6 = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
$upd_users_6->execute(array(':gold' => $gold+150, ':user_id' => $user['id']));		
}
#-100К серебра-#
if($rand_3 == 9){
$nagrada = '<img src="/style/images/many/silver.png" alt=""/>100К';
$koleso = 'koleso_10';
$upd_users_7 = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");
$upd_users_7->execute(array(':silver' => $user['silver']+100000, ':user_id' => $user['id']));
}
#-Золотой сундук-#	
if($rand_3 == 10){
$nagrada = '<img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
$koleso = 'koleso_11';
$ins_chest_3 = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest_3->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 		
}
}

#-Награда (Змееглав, Гелаус)-#
if($rand_f >= 99){
$rand_4 = rand(11, 12);	
	
#-Змееглав-#	
/*if($rand_4 == 11){
$nagrada = '<img src="/style/images/body/pets.png" alt=""/>Змееглав';
$koleso = 'koleso_5';
$ins_pets_me_1 = $pdo->prepare("INSERT INTO `pets_me` SET `pets_id` = :pets_id, `user_id` = :user_id");
$ins_pets_me_1->execute(array(':pets_id' => 4, ':user_id' => $user['id']));		
}
#-Гелаус-#	
if($rand_4 == 12){
$nagrada = '<img src="/style/images/body/pets.png" alt=""/>Гелаус';
$koleso = 'koleso_2';
$ins_pets_me_2 = $pdo->prepare("INSERT INTO `pets_me` SET `pets_id` = :pets_id, `user_id` = :user_id");
$ins_pets_me_2->execute(array(':pets_id' => 5, ':user_id' => $user['id']));		
}
*/
}

#-Запись лога-#
$ins_log = $pdo->prepare("INSERT INTO `fortuna_log` SET `log` = :log, `time` = :time");
$ins_log->execute(array(':log' => "<a href='/hero/$user[id]'>$user[nick]</a> получил(а) $nagrada", ':time' => time()));	
header("Location: /fortuna?koleso=$koleso");
$_SESSION['ok'] = "Вам повезло: $nagrada";
exit();	
}else{
header('Location: /fortuna');
$_SESSION['err'] = 'Недостаточно золота!';
exit();		
}
}
?>