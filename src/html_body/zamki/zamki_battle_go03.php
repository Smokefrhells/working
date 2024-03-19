<?php
require_once '../../system/system.php';
$head = 'Бой';
echo only_reg();
echo zamki_level();
require_once H.'system/head.php';
echo'<div class="page">';

#-Выборка данных замка-#
$sel_zamki = $pdo->query("SELECT * FROM `zamki`");
if($sel_zamki-> rowCount() != 0){
$zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
#-Выборка игрока в сражении-#
$sel_zamki_u = $pdo->prepare("SELECT * FROM `zamki_users` WHERE `user_id` = :user_id");
$sel_zamki_u->execute(array(':user_id' => $user['id']));
if($sel_zamki_u-> rowCount() != 0){
$zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY);
echo'<div class="block_hunting">';

#-Замок Левых-#
if($zamki['health_max_left'] != 0){
if($zamki_u['storona'] == 'right'){
echo"<img src='".img_zamki($zamki['health_max_left'],$zamki['health_t_left'])."' class='img_m_battle' alt=''/><div class='block_monsters'><img src='/style/images/body/zamki.png' alt=''/><span class='red'><b>Замок Левых</b> [".(round(100/($zamki['health_max_left']/$zamki['health_t_left']), 2))."%]</span><br/><div class='param_monsters'><img src='/style/images/user/health.png' alt=''/>$zamki[health_t_left] ".($zamki['stena_left'] == 0 ? "" : "<img src='/style/images/body/stena.png' alt=''/>Установлена")."</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/($zamki['health_max_left']/$zamki['health_left']))."%'><div class='health' style='width:".round(100/($zamki['health_left']/$zamki['health_t_left']))."%'></div></div></div>";
}
}
#-Замок Правых-#
if($zamki['health_max_right'] != 0){
if($zamki_u['storona'] == 'left'){
echo"<img src='".img_zamki($zamki['health_max_right'],$zamki['health_t_right'])."' class='img_m_battle' alt=''/><div class='block_monsters'><img src='/style/images/body/zamki.png' alt=''/><span class='red'><b>Замок Правых</b> [".(round(100/($zamki['health_max_right']/$zamki['health_t_right']), 2))."%]</span><br/><div class='param_monsters'><img src='/style/images/user/health.png' alt=''/>$zamki[health_t_right] ".($zamki['stena_right'] == 0 ? "" : "<img src='/style/images/body/stena.png' alt=''/>Установлена")."</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/($zamki['health_max_right']/$zamki['health_right']))."%'><div class='health' style='width:".round(100/($zamki['health_right']/$zamki['health_t_right']))."%'></div></div></div>";
}
}

#-Оружие или время-#
if($sel_zamki_u-> rowCount() != 0){
echo'<div style="padding-top: 10px;"></div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
#-Оружие-#
if($zamki['statys'] == 1 and $zamki_u['time_freezing'] == 0){
#-Выборка оружия для боя которое сейчас надето-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
$sel_weapon_me->execute(array(':user_id' => $user['id'],':type' => 5, ':state' => 1)); 
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Если есть оружие-#
if($sel_weapon_me-> rowCount() != 0){
#-Выборка данных о оружие-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
$sel_weapon->execute(array(':id' => $weapon_me['weapon_id'])); 
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
echo"<center><a href='/zamki_attack?act=attc'><img src='$weapon[images]' class='".ramka($weapon_me['max_level'])."' alt=''/></a></center>";
}else{
echo"<center><a href='/zamki_attack?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
}
}else{
#-Время-#
if($zamki_u['time_freezing'] == 0){$zamki_ostatok = $zamki['time'] - time();}else{$zamki_ostatok = $zamki_u['time_freezing'] - time();}
echo"<center><a href='/zamki_battle' class='button_red_a'><img src='/style/images/body/time.png' alt=''/>".timers($zamki_ostatok)."</a></center>";
echo'<div style="padding-top: 3px;"></div>';
}
echo'</div>';
echo'<div class="line_1"></div>';
}

#-Замок Правых-#
if($zamki['health_max_right'] != 0){
if($zamki_u['storona'] == 'right'){
echo"<img src='".img_zamki($zamki['health_max_right'],$zamki['health_t_right'])."' class='img_m_battle' alt=''/><div class='block_monsters'><img src='/style/images/body/zamki.png' alt=''/><span class='green'><b>Замок Правых</b> [".(round(100/($zamki['health_max_right']/$zamki['health_t_right']), 2))."%]</span><br/><div class='param_monsters'><img src='/style/images/user/health.png' alt=''/>$zamki[health_t_right] ".($zamki['stena_right'] == 0 ? "" : "<img src='/style/images/body/stena.png' alt=''/>Установлена")."</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/($zamki['health_max_right']/$zamki['health_right']))."%'><div class='health' style='width:".round(100/($zamki['health_right']/$zamki['health_t_right']))."%'></div></div></div>";
}
}
#-Замок Левых-#
if($zamki['health_max_left'] != 0){
if($zamki_u['storona'] == 'left'){
echo"<img src='".img_zamki($zamki['health_max_left'],$zamki['health_t_left'])."' class='img_m_battle' alt=''/><div class='block_monsters'><img src='/style/images/body/zamki.png' alt=''/><span class='green'><b>Замок Левых</b> [".(round(100/($zamki['health_max_left']/$zamki['health_t_left']), 2))."%]</span><br/><div class='param_monsters'><img src='/style/images/user/health.png' alt=''/>$zamki[health_t_left] ".($zamki['stena_left'] == 0 ? "" : "<img src='/style/images/body/stena.png' alt=''/>Установлена")."</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/($zamki['health_max_left']/$zamki['health_left']))."%'><div class='health' style='width:".round(100/($zamki['health_left']/$zamki['health_t_left']))."%'></div></div></div>";
}
}
echo'<div style="padding-top: 10px;"></div>';

#-Лог событий-#
$sel_zamki_log = $pdo->query("SELECT * FROM `zamki_log` ORDER BY `time` DESC LIMIT 5");
if($sel_zamki_log -> rowCount() != 0){
echo'<div class="line_1"></div>';
while($zamki_log = $sel_zamki_log->fetch(PDO::FETCH_LAZY)){
echo'<div class="body_list"><div style="padding: 2px; padding-left: 5px;">';
if($zamki_log['storona'] == $zamki_u['storona']){
echo"<span class='green'>$zamki_log[log]</span>";
}else{
if($zamki_log['storona'] == ''){
echo"<span class='yellow'>$zamki_log[log]</span>";
}else{
echo"<span class='red'>$zamki_log[log]</span>";	
}
}
echo'</div>';
echo'</div>';
}
}

#-Урон который нанес игрок-#
if($sel_zamki_u-> rowCount() != 0 and $zamki['statys'] == 1){
$sel_silver_sum = $pdo->prepare("SELECT SUM(uron) FROM `zamki_users` WHERE `storona` = :storona");
$sel_silver_sum->execute(array(':storona' => $zamki_u['storona']));
$silver_sum = $sel_silver_sum->fetch(PDO::FETCH_LAZY);

$user_health = $user['health']+$user['s_health']+$user['health_bonus'];
if($zamki_u['storona'] == 'right'){
$vostanov_health = $zamki['health_t_right']+$user_health;
$zamki_max_health = $zamki['health_max_right'];
}else{
$vostanov_health = $zamki['health_t_left']+$user_health;
$zamki_max_health = $zamki['health_max_left'];	
}

echo'<div class="body_list">';
echo'<div class="menulist">';
#-Восстановление здоровья-#
echo'<div class="line_1"></div>';
if($zamki_u['quatity_health'] == 0 and $vostanov_health <= $zamki_max_health){
echo'<li><a href="/zamki_health?act=health">Лечить <img src="/style/images/user/health.png" alt=""/>'.num_format($user_health).' за <img src="/style/images/many/gold.png" alt=""/>85</a></li>';
}else{
echo'<div class="svg_list"><span class="white">Лечить <img src="/style/images/user/health.png" alt=""/>'.num_format($user_health).' за <img src="/style/images/many/gold.png" alt=""/>85</span></div>';
}
#-Заморозка-#
echo'<div class="line_1"></div>';
if($zamki_u['quatity_freezing'] == 0){
echo'<li><a href="/zamki_freezing?act=freezing"><img src="/style/images/body/freezing.png" alt=""/> Заморозка за <img src="/style/images/many/gold.png" alt=""/>'.($zamki['max_uron_id'] == $user['id'] ? '0' : '50').'</a></li>';
}else{
echo'<div class="svg_list"><span class="white"><img src="/style/images/body/freezing.png" alt=""/> Заморозка за <img src="/style/images/many/gold.png" alt=""/>50</span></div>';
}
#-Урон и серебро-#
echo'<div class="line_1"></div>';
echo"<div class='svg_list'><img src='/style/images/body/attack.png' alt=''/> <span class='yellow'>Нанесено урона:</span> <span class='gray'>".num_format($silver_sum[0])."</span></div>";
echo'<div class="line_1"></div>';
echo"<div class='svg_list'><img src='/style/images/many/silver.png' alt=''/><span class='yellow'>Заработано серебра:</span> <span class='gray'>".num_format(round(($silver_sum[0]/65), 0))."</div>";
#-В бою правых-#
$sel_battle_r = $pdo->query("SELECT COUNT(*) FROM `zamki_users` WHERE `uron` > 0 AND `storona` = 'right'");
$battle_r = $sel_battle_r->fetch(PDO::FETCH_LAZY);
#-В бою левых-#
$sel_battle_l = $pdo->query("SELECT COUNT(*) FROM `zamki_users` WHERE `uron` > 0 AND `storona` = 'left'");
$battle_l = $sel_battle_l->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo"<div class='svg_list'><img src='/style/images/user/user.png' alt=''/> <span class='yellow'>В бою:</span> <span class='gray'>П - $battle_r[0] Л - $battle_l[0]</span></div>";
echo'</div>';
echo'</div>';
}

#-Установка стены-#
if($zamki_u['storona'] == 'right'){$stena = $zamki['stena_right'];$health_max_s = $zamki['health_max_right'];}else{$stena = $zamki['stena_left'];$health_max_s = $zamki['health_max_left'];}
if($stena == 0 and $zamki['statys'] == 0 and $sel_zamki_u-> rowCount() != 0){
$health_stena = round(($health_max_s * 0.25), 0);
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/zamki_stena?act=stena"><img src="/style/images/body/stena.png" alt=""/> Стена +<img src="/style/images/user/health.png" alt=""/>'.$health_stena.' за <img src="/style/images/many/gold.png" alt=""/>100</a></li>';
echo'</div>';
echo'</div>';
}

}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Вы не участвуете в этом сражении!</div>';
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Бой окончен!</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>