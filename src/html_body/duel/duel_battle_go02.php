<?php
require_once '../../system/system.php';
$head = 'Бой';
echo only_reg();
echo duel_level();
require_once H.'system/head.php';
session_start();
echo'<div class="page">';
#-Есть ли у нас доступные бои-#
$user_duel = floor($user['level']/2);
if($user['duel_b'] < $user_duel){

if(isset($_SESSION['duel_id'])){
$ank_id = $_SESSION['duel_id'];

#-Выборка данных игрока-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
$all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Параметры игрока-#
$user_param = $user['sila']+$user['zashita']+$user['health']+$user['s_sila']+$user['s_zashita']+$user['s_health']+$user['sila_bonus']+$user['zashita_bonus']+$user['health_bonus'];
#-Параметры опонента-#
$ank_param = $all['sila']+$all['zashita']+$all['health']+$all['s_sila']+$all['s_zashita']+$all['s_health']+$all['sila_bonus']+$all['zashita_bonus']+$all['health_bonus'];

if($user_param > $ank_param){
$us_p = "<span class='green'>[+".($user_param-$ank_param)."]</span>";
$op_p = "<span class='red'>[-".($user_param-$ank_param)."]</span>";
}else{
$us_p = "<span class='red'>[-".($ank_param-$user_param)."]</span>";
$op_p = "<span class='green'>[+".($ank_param-$user_param)."]</span>";
}

#-Враг-#
echo'<div class="block_hunting">';
echo"<img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='img_h_battle' width='50' height='50'  alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><b>$all[nick]</b> <span style='font-size: 13px;'>[$all[level] ур.]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])." $op_p</div></div>";
#-Оружие-#
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/duel_attack?act=attc' class='button_red_a'>Атаковать</a>";
echo'<div style="padding-top: 3px;"></div>';
$silver = $user['level'] * 42;
echo"<a href='/duel_act?act=next' class='button_green_a'>Другой за <img src='/style/images/many/silver.png' alt=''/>$silver</a>";
echo'<div style="padding-top: 3px;"></div>';
#-Проведение боев за золото-#
$user_duel = floor($user['level']/2);
$user_atk = $user_duel-$user['duel_b'];
if($user['duel_b'] < $user_duel){
if($_GET['conf'] == 'auto'){
echo"<a href='/duel_attack_auto?act=attc' class='button_green_a'>Подтверждаю</a>";	
}else{
echo"<a href='/duel_battle?conf=auto' class='button_green_a'>$user_atk боев за <img src='/style/images/many/gold.png' alt=''/>$user_atk</a>";
}
}

echo'<div style="padding-top: 3px;"></div>';	
echo'</div>';
echo'<div class="line_1"></div>';
#-Герой-#
echo"<img src='".avatar_img_min($user['avatar'], $user['pol'])."' class='img_h_battle'  width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($user['sila']+$user['s_sila']+$user['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($user['zashita']+$user['s_zashita']+$user['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($user['health']+$user['s_health']+$user['health_bonus'])." $us_p</div></div>";
echo'</div>';
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Данные о бое не найдены!';
echo'</div>';
echo'</div>';
}
}
/*if(rand(0,1)==1){
unset($_SESSION['duel_id']);
}*/
echo'</div>';
require_once H.'system/footer.php';
?>