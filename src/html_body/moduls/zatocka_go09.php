<?php
require_once '../../system/system.php';
$head = 'Перенос заточки';
echo only_reg();
echo blacksmith_level();
require_once H.'system/head.php';
echo'<img src="/style/images/location/blacksmith.jpg" class="img"/>';
echo'<div class="page">';


$weapon_id = check($_GET['weapon']);
#-Выборка данных снаряжения-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id");
$sel_weapon_me->execute(array(':id' => $weapon_id, ':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() != 0){
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);

$weapon_id2 = check($_GET['weapon2']);

$sel_weapon_me2 = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id");
$sel_weapon_me2->execute(array(':id' => $weapon_id2, ':user_id' => $user['id']));
if($sel_weapon_me2-> rowCount() != 0){
$weapon_me2 = $sel_weapon_me2->fetch(PDO::FETCH_LAZY);



$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));	
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);

$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me2['weapon_id']));	
$weapon2 = $sel_weapon->fetch(PDO::FETCH_LAZY);
echo'<div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me['max_level']).'"  alt=""/></div><div class="weapon_setting"><span style="color: '.color_w($weapon_me['max_level']).';"><img src="'.star($weapon_me['max_level']).'" alt=""/><b>'.$weapon['name'].'</b> ['.$weapon_me['max_level'].' ур.]</span><br/><div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon['sila']+$weapon_me['b_sila']+$weapon_me['runa']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon['zashita']+$weapon_me['b_zashita']+$weapon_me['runa']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon['health']+$weapon_me['b_health']+$weapon_me['runa']).'<br/>Руна: '.($weapon_me['runa'] == 0 ? "Не установлена" : "Установлена <span class='green'>[+$weapon_me[runa]]</span>").'</div></div>';
echo'<div style="padding-top: 10px;"></div>';

echo'<div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me2['max_level']).'"  alt=""/></div><div class="weapon_setting"><span style="color: '.color_w($weapon_me2['max_level']).';"><img src="'.star($weapon_me2['max_level']).'" alt=""/><b>'.$weapon2['name'].'</b> ['.$weapon_me2['max_level'].' ур.]</span><br/><div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon2['sila']+$weapon_me2['b_sila']+$weapon_me2['runa']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon2['zashita']+$weapon_me2['b_zashita']+$weapon_me2['runa']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon2['health']+$weapon_me2['b_health']+$weapon_me2['runa']).'<br/>Руна: '.($weapon_me2['runa'] == 0 ? "Не установлена" : "Установлена <span class='green'>[+$weapon_me2[runa]]</span>").'</div></div>';
echo'<div style="padding-top: 10px;"></div>';

echo('<center>точно?</center>');
}else{
echo('<center>выберите вещь куда перенести заточку</center>');

#-Выборка снаряжения-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = 0 AND `id` != :id ORDER BY `weapon_id` ASC, `type` ASC LIMIT $start, $num");
$sel_weapon_me->execute(array(':user_id' => $user['id'],':id' => $weapon_id));
#-Если есть такое оружие-#
if($sel_weapon_me-> rowCount() != 0){
while($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY))  
{
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));	
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
#-Вывод снаряжения-#weapon2
echo'<a href="/zatocka?weapon='.$weapon_id.'&weapon2='.$weapon_me['id'].'"><div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me['max_level']).'"  alt=""/></div><div class="weapon_setting"><span style="color: '.color_w($weapon_me['max_level']).';"><img src="'.star($weapon_me['max_level']).'" alt=""/><b>'.$weapon['name'].'</b> ['.$weapon_me['max_level'].' ур.]</span><br/><div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon['sila']+$weapon_me['b_sila']+$weapon_me['runa']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon['zashita']+$weapon_me['b_zashita']+$weapon_me['runa']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon['health']+$weapon_me['b_health']+$weapon_me['runa']).'<br/>Руна: '.($weapon_me['runa'] == 0 ? "Не установлена" : "Установлена <span class='green'>[+$weapon_me[runa]]</span>").'</div></div>';
echo'<div style="padding-top: 10px;"></div></a>';




}
    
}
}
}else{
//err

}
require_once H.'system/footer.php';
?>