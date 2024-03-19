<?php
require_once '../../system/system.php';
$head = 'Вызов на дуэль';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
#-Уровень-#
if($user['level'] >= 5){
#-Проверяем получены ли данные-#
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['ank_id']);
#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `time_online` FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Проверяем сейчас в дуэли или нет-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_battle` WHERE `user_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
$sel_duel_o = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel_o->execute(array(':user_id' => $user['id']));
if($sel_duel_o-> rowCount() == 0){
#-Проверяем есть ли бои-#
$battle = floor($user['level']/2);
if($user['duel_b'] < $battle){
echo'<div style="padding-top: 5px;"></div>';
echo"<a href='/duel_act?act=atk_hero&ank_id=$ank_id' class='button_red_a'>Оффлайн режим</a>";	
#-Онлайн режим только если игрок не в бою-#
$sel_duel_ank = $pdo->prepare("SELECT * FROM `duel_battle` WHERE `user_id` = :ank_id");
$sel_duel_ank->execute(array(':ank_id' => $all['id']));
if($sel_duel_ank-> rowCount() == 0){
$sel_duel_o_ank = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :ank_id OR `ank_id` = :ank_id");
$sel_duel_o_ank->execute(array(':ank_id' => $all['id']));
if($sel_duel_o_ank-> rowCount() == 0){
#-Проверяем онлайн или нет-#
$time = time() - 600;
if($all['time_online'] > $time){
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/duel_act?act=atk_online_hero&ank_id=$ank_id' class='button_green_a'>Онлайн режим</a>";	
echo'<div style="padding-top: 5px;"></div>';
}else{
echo'<div style="padding-top: 3px;"></div>';
echo"<div style='opacity: 0.8;'><a href='/call_duel?ank_id=$ank_id' class='button_green_a'>Сейчас оффлайн</a></div>";	
echo'<div style="padding-top: 5px;"></div>';		
}
}else{
echo'<div style="padding-top: 3px;"></div>';
echo"<div style='opacity: 0.8;'><a href='/call_duel?ank_id=$ank_id' class='button_green_a'>Сейчас в бою</a></div>";	
echo'<div style="padding-top: 5px;"></div>';	
}
}else{
echo'<div style="padding-top: 3px;"></div>';
echo"<div style='opacity: 0.8;'><a href='/call_duel?ank_id=$ank_id' class='button_green_a'>Сейчас в бою</a></div>";	
echo'<div style="padding-top: 5px;"></div>';		
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> У вас закончились бои!';
echo'</div>';
echo'</div>';			
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Вы сейчас в бою!';
echo'</div>';
echo'</div>';	
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Вы сейчас в бою!';
echo'</div>';
echo'</div>';		
}
}
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Доступно с 5 ур.';
echo'</div>';
echo'</div>';		
}
echo'</div>';	
require_once H.'system/footer.php';
?>
