<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
echo armor_transfer_level();
$head = 'Передача снаряжения';
require_once H.'system/head.php';
echo'<div class="page">';

#-Проверка данных-#
if(isset($_GET['ank_id']) and $_GET['ank_id'] != $user['id']){

#-Снаряжение-#
if($_GET['type'] == 1 or !isset($_GET['type'])){

#-Навигация-#
$all = '<a href="/armor_transfer?type_s=1&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type_s'] == 7 or $_GET['type_s'] > 8 or !isset($_GET['type_s']))  ? "<b>Все</b>" : "Все").'</span></a>';
//$head = '<a href="/armor_transfer?type_s=2&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.($_GET['type_s'] == 2 ? "<b>Голова</b>" : "Голова").'</span></a>';
//$body = '<a href="/armor_transfer?type_s=3&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.($_GET['type_s'] == 3 ? "<b>Торс</b>" : "Торс").'</span></a>';
//$gloves = '<a href="/armor_transfer?type_s=4&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.($_GET['type_s'] == 4 ? "<b>Руки</b>" : "Руки").'</span></a>';
//$shield = '<a href="/armor_transfer?type_s=5&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.($_GET['type_s'] == 5 ? "<b>Защита</b>" : "Защита").'</span></a>';
//$arm = '<a href="/armor_transfer?type_s=6&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.($_GET['type_s'] == 6 ? "<b>Оружие</b>" : "Оружие").'</span></a>';
$bijouterie = '<a href="/armor_transfer?type_s=8&ank_id='.$_GET['ank_id'].'" style="text-decoration:none;"><span class="body_sel">'.($_GET['type_s'] == 8 ? "<b>Бижутерия</b>" : "Бижутерия").'</span></a>';

#-Вывод-#
echo'<div class="body_list">';	
echo'<div style="padding: 5px;">';
echo''.$all.' '.$head.' '.$body.' '.$gloves.' '.$shield.' '.$arm.' '.$legs.' '.$bijouterie.'';
echo'</div>';
echo'<div class="line_1"></div>';
echo'</div>';	
	
#-Все без сортировки-#
if($_GET['type_s'] == 1 or !isset($_GET['type_s']) or $_GET['type_s'] != 2){
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = 0 AND `auction` = 0 AND `runa` = 0 ORDER BY `weapon_id` ASC, `type` ASC");
$sel_weapon_me->execute(array(':user_id' => $user['id']));
}
#-По сортировке-#
if($_GET['type_s'] == 8){
if($_GET['type_s'] == 8){
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = 0 AND `runa` = 0 AND (`type` = 7 OR `type` = 8) AND `auction` = 0 ORDER BY `weapon_id` ASC, `type` ASC");
$sel_weapon_me->execute(array(':user_id' => $user['id']));
}else{
$type_s = $_GET['type_s'] - 1;
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = 0 AND `runa` = 0 AND `type` = :type AND `auction` = 0 ORDER BY `weapon_id` ASC, `type` ASC");
$sel_weapon_me->execute(array(':type' => $type_s, ':user_id' => $user['id']));
}
}

#-Если есть оружие-#
if($sel_weapon_me-> rowCount() != 0){
echo'<form method="post" action="/armor_transfer_act?act=trans&ank_id='.$_GET['ank_id'].'">';	
while($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY))  
{
#-Выборка параметров-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));	
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
echo'<div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me['max_level']).'"  alt=""></div><div class="weapon_setting"><span style="color: '.color_w($weapon_me['max_level']).';"><img src="'.star($weapon_me['max_level']).'" alt=""/><b>'.$weapon['name'].'</b> ['.$weapon['level'].' ур.]</span><br/><div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon['sila']+$weapon_me['b_sila']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon['zashita']+$weapon_me['b_zashita']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon['health']+$weapon_me['b_health']).'<br/><input type="checkbox" name="weapon_id[]" value="'.$weapon_me['id'].'"/></div></div>';
echo'<div style="padding-top: 3px;"></div>';
}
echo'<div style="padding-top: 3px;"></div>';
echo"<input class='button_green_i' name='submit' type='submit'  value='Передать'/>";
echo'</form>';
echo'<div style="padding-top: 3px;"></div>';
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Сумка пуста!</div>';
}
}
}
echo'</div>';
require_once H.'system/footer.php';
?>