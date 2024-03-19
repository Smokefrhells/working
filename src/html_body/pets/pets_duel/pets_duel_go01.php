<?php
require_once '../../../system/system.php';
$head = 'Дуэльный поединок';
echo only_reg();
echo pets_level();
session_start();
require_once H.'system/head.php';
echo'<div class="page">';

#-Выборка активного питомца игрока-#
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id AND `active` = 1");
$sel_pets_me->execute(array(':user_id' => $user['id']));
if($sel_pets_me-> rowCount() != 0){
$pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);
#-Выборка данных текущего питомца-#
$sel_pets = $pdo->prepare("SELECT `id`, `name`, `sila`, `zashita`, `health`, `images` FROM `pets` WHERE `id` = :pets_id");
$sel_pets->execute(array(':pets_id' => $pets_me['pets_id']));
$pets = $sel_pets->fetch(PDO::FETCH_LAZY);
}

#-Проверка что игрок не участвует в бою-#
$sel_pets_duel_me = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `user_id` = :user_id");
$sel_pets_duel_me->execute(array(':user_id' => $user['id']));
if($sel_pets_duel_me->rowCount() == 0){
echo'<img src="/style/images/location/pets_duel.jpg" class="img" alt=""/>';
echo'<div style="padding-top: 3px;"></div>';
if($user['pets_boi']> 0){
echo'<a href="/pets_duel_start?act=start" class="button_green_a">Сразиться</a>';
echo'<div style="padding-top: 3px;"></div>';
}else{
echo'<div class="button_green_a">Кончились бои!</div>';
echo'<div style="padding-top: 3px;"></div>';
}
#-Должен быть активный питомец-#
if($sel_pets_me-> rowCount() != 0){
echo'<div class="line_1"></div>';
echo'<div class="mini-line"></div>';
echo'<div class="line_1_v"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<img src="'.$pets['images'].'" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>'.$pets['name'].'</b><br/><span class="t_param"><img src="/style/images/user/sila.png" alt=""/>'.($pets['sila']+$pets_me['b_param']).' <img src="/style/images/user/zashita.png" alt=""/>'.($pets['zashita']+$pets_me['b_param']).'  <img src="/style/images/user/health.png" alt=""/>'.($pets['health']+$pets_me['b_param']).'</span><br/>'.pets_ability($pets_me['pets_id'], $pets_me['abi_prosent']).'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Нет выбраного питомца!</div>';	
}

#-Ранги-#
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
if($user['pets_rang'] == 15) $rang = 1;
if($user['pets_rang'] == 25) $rang = 2;
if($user['pets_rang'] == 35) $rang = 3;
if($user['pets_rang'] == 45) $rang = 4;
echo'<div class="info_list"><img src="/style/images/body/pets_rang.png" alt=""/>'.$rang.' ранг: <img src="/style/images/many/gold.png" alt=""/>'.$user['pets_rang'];
echo'<small><center>вам доступно 15 боев в день, они обновляются в 12:00 по серверу</center></small></div>';
echo'<center>боев '.$user['pets_boi'].' из 15</center>';
echo'</div>';

}else{
$pets_duel_me = $sel_pets_duel_me->fetch(PDO::FETCH_LAZY);	

#-ОЧЕРЕДЬ-#
if($pets_duel_me['statys'] == 0){	
#-Выборка сколько питомцев участвует в сражении-#
$sel_pets_a = $pdo->prepare("SELECT COUNT(*) FROM `pets_duel` WHERE `battle_id` = :battle_id");
$sel_pets_a->execute(array(':battle_id' => $pets_duel_me['battle_id']));
$pets_a = $sel_pets_a->fetch(PDO::FETCH_LAZY);	
?>
<script type="text/javascript">
function timer(){
 var obj=document.getElementById('timer_inp');
 obj.innerHTML--;
 if(obj.innerHTML==0){location.reload();setTimeout(function(){},1000);}
 else{setTimeout(timer, 1000);}
}
setTimeout(timer,1000);
</script>
<?
echo'<img src="/style/images/location/pets_duel.jpg" class="img" alt=""/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<center>';
echo"<span class='gray'><img src='/style/images/body/loader.gif' alt=''/>Ожидание других игроков [<span id='timer_inp'>5</span>]</span><br/>";	
echo'</center>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/pets_duel" class="button_green_a">Обновить</a>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/pets_duel_exit?act=exit_osh" class="button_red_a">Выйти</a>';
echo'<div style="padding-top: 3px;"></div>';		
}

#-БОЙ И ПОДГОТОВКА К БОЮ-#
if($pets_duel_me['statys'] >= 1){

#-Выборка данных боя питомца врага-#
$sel_duel_ank = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `battle_id` = :battle_id AND `user_id` != :user_id");
$sel_duel_ank->execute(array(':battle_id' => $pets_duel_me['battle_id'], ':user_id' => $user['id']));
$duel_ank = $sel_duel_ank->fetch(PDO::FETCH_LAZY);
#-Выборка активного питомца врага-#
$sel_pets_m_ank = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :ank_id AND `active` = 1");
$sel_pets_m_ank->execute(array(':ank_id' => $duel_ank['user_id']));
$pets_m_ank = $sel_pets_m_ank->fetch(PDO::FETCH_LAZY);
#-Выборка данных питомца врага-#
$sel_pets_ank = $pdo->prepare("SELECT `id`, `name`, `sila`, `zashita`, `images` FROM `pets` WHERE `id` = :id");
$sel_pets_ank->execute(array(':id' => $pets_m_ank['pets_id']));
$pets_ank = $sel_pets_ank->fetch(PDO::FETCH_LAZY);

#-Вывод питомца врага-#
echo'<div class="line_1_v"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<img src="'.$pets_ank['images'].'" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>'.$pets_ank['name'].'</b><br/><span class="t_param"><img src="/style/images/user/sila.png" alt=""/>'.($pets_ank['sila']+$pets_m_ank['b_param']).' <img src="/style/images/user/zashita.png" alt=""/>'.($pets_ank['zashita']+$pets_m_ank['b_param']).'  <img src="/style/images/pets/ability/treatment.gif" alt=""/>'.$duel_ank['pets_t_health'].' '.($duel_ank['pets_uron'] > 0 ? "<span style='font-size:12px; color:#ff0000;'>(-$duel_ank[pets_uron])</span>" : "").'</span><br/>'.pets_ability($pets_m_ank['pets_id'], $pets_m_ank['abi_prosent']).'</div>';
echo'</div>';
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="mini-line"></div>';
echo'<div class="line_1_v"></div>';

#-Атаковать или время-#
echo'<div style="padding-top: 5px;"></div>';
if($pets_duel_me['statys'] == 1){
#-Время подготовки-#
$sel_pets_duel_t = $pdo->prepare("SELECT `id`, `battle_id`, `time` FROM `pets_duel` WHERE `battle_id` = :battle_id ORDER BY `id`");
$sel_pets_duel_t->execute(array(':battle_id' => $pets_duel_me['battle_id']));
$pets_duel_t = $sel_pets_duel_t->fetch(PDO::FETCH_LAZY);
$ostatok = timers(($pets_duel_t['time']-time()));
echo'<a href="/pets_duel" class="button_green_a">'.($ostatok == '' ? "Обновить" : "$ostatok").'</a>';
}else{
echo'<a href="/pets_duel_attack?act=attc" class="button_red_a">Атаковать</a>';		
}
echo'<div style="padding-top: 5px;"></div>';

#-Выборка данных боя питомца игрока-#
$sel_pets_u = $pdo->prepare("SELECT * FROM `pets_duel` WHERE `battle_id` = :battle_id AND `user_id` = :user_id");
$sel_pets_u->execute(array(':battle_id' => $pets_duel_me['battle_id'], ':user_id' => $user['id']));
$pets_u = $sel_pets_u->fetch(PDO::FETCH_LAZY);

echo'<div class="line_1"></div>';
echo'<div class="mini-line"></div>';
echo'<div class="line_1_v"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<img src="'.$pets['images'].'" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>'.$pets['name'].'</b><br/><span class="t_param"><img src="/style/images/user/sila.png" alt=""/>'.($pets['sila']+$pets_me['b_param']).' <img src="/style/images/user/zashita.png" alt=""/>'.($pets['zashita']+$pets_me['b_param']).'  <img src="/style/images/pets/ability/treatment.gif" alt=""/>'.$pets_duel_me['pets_t_health'].' '.($pets_duel_me['pets_uron'] > 0 ? "<span style='font-size:12px; color:#ff0000;'>(-$pets_duel_me[pets_uron])</span>" : "").'</span><br/>'.pets_ability($pets_me['pets_id'], $pets_me['abi_prosent']).'</div>';
echo'</div>';
echo'</div>';

#-ЛОГ БОЯ-#
$sel_pets_l = $pdo->prepare("SELECT * FROM `pets_duel_log` WHERE `battle_id` = :battle_id ORDER BY `time` DESC LIMIT 5");
$sel_pets_l->execute(array(':battle_id' => $pets_duel_me['battle_id']));
if($sel_pets_l-> rowCount() != 0){
echo'<div class="line_1"></div>';
echo'<div class="mini-line"></div>';
echo'<div class="line_1_v"></div>';
while($pets_l = $sel_pets_l->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="body_list"><div style="padding: 2px;padding-left: 5px;">';
if($pets_l['user_id'] == $user['id']){
echo"<span class='green'> $pets_l[log]</span>";
}else{
echo"<span class='red'> $pets_l[log]</span>";
}
echo'</div></div>';	
}
}
}
}
echo'</div>';
require_once H.'system/footer.php';
?>