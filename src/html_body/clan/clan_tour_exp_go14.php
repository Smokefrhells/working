<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $id));
if($sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /clan');
$_SESSION['err'] = 'Клан не найден!';
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = $error;
exit();
}
$head = 'Рейтинг опыта';
require_once H.'system/head.php';

if($_GET['tour_exp_setting'] == 'on'){
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/clan_tour_setting?act=tour_exp&clan_id='.$clan['id'].'">';
echo'<input class="input_form" type="number" name="mesto_1" placeholder="1 место: 0-2000 золота" value="'.$clan['tour_exp_1'].'"/><br/>';
echo'<input class="input_form" type="number" name="mesto_2" placeholder="2 место: 0-1500 золота" value="'.$clan['tour_exp_2'].'"/><br/>';
echo'<input class="input_form" type="number" name="mesto_3" placeholder="3 место: 0-1000 золота" value="'.$clan['tour_exp_3'].'"/>';
echo'<br/><input class="input_form" type="number" name="mesto_all" placeholder="Остальные места: 0-500 золота" value="'.$clan['tour_exp_all'].'"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Сохранить"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/clan/tour_exp/'.$clan['id'].'"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Призовых мест: '.$clan['amulet_lvl'].'';
echo'</div>';
echo'</div>';

}else{
#-Количество участников клана-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id AND clan_users.exp > 0 ORDER BY clan_users.exp DESC, clan_users.id DESC LIMIT $clan[amulet_lvl]");
$sel_clan_u->execute(array(':clan_id' => $clan['id']));
echo'<div class="body_list">';
echo'<div class="menulitl">';
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY))  
{
$i = $i+1;
if($i == 1){$gold_p = $clan['tour_exp_1'];}
if($i == 2){$gold_p = $clan['tour_exp_2'];}
if($i == 3){$gold_p = $clan['tour_exp_3'];}
if($i > 3){$gold_p = $clan['tour_exp_all'];}
#-Выборка данных игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `level`, `avatar`, `pol`,`time_online` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $clan_u['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Если клан наш-#
$sel_clan_me = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_me->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
$clan_me = $sel_clan_me->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>".online($all['time_online'])." <span class='menulitl_name'>".$all['nick']."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место (+<img src='/style/images/many/gold.png' alt=''/>$gold_p) <img src='/style/images/user/exp.png' alt=''/>".num_format($clan_u['exp'])."</div></div></a></li>";
}
}
echo'</div>';

$sel_clan_me = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `prava` = 4");
$sel_clan_me->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_me->rowCount() != 0){
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/clan/tour_exp/'.$clan['id'].'?tour_exp_setting=on"><img src="/style/images/body/set.png" alt=""/> Настройки турнира</a></li>';
echo'</div>';
}
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Призовых мест: '.$clan['amulet_lvl'].'';
echo'</div>';
echo'</div>';
}

require_once H.'system/footer.php';
?>