<?php
require_once '../../system/system.php';
$head = 'Помощь';
echo only_reg();
echo boss_level();
echo boss_campaign();
require_once H.'system/head.php';
echo'<div class="body_list">';
#-Сортировка-#
$friends_f = '<a href="/boss_help?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 3 or !isset($_GET['type']))  ? "<b>Все</b>" : "Все").'</span></a>';
$clan_f = '<a href="/boss_help?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Из клана</b>" : "Из клана").'</span></a>';
$online_f = '<a href="/boss_help?type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>Онлайн</b>" : "Онлайн").'</span></a>';
echo'<div class="line_1_m"></div>';
echo'<div style="padding: 5px;">';
echo''.$friends_f.''.$clan_f.''.$online_f.'';
echo'</div>';

#-Выборка данных о бое-#
$sel_boss_users = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_users->execute(array(':user_id' => $user['id']));
if($sel_boss_users-> rowCount() != 0){
$boss_u = $sel_boss_users->fetch(PDO::FETCH_LAZY);
#-Выборка данных о Боссе-#
$sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `id` = :boss_id");
$sel_boss->execute(array(':boss_id' => $boss_u['boss_id']));
$boss = $sel_boss->fetch(PDO::FETCH_LAZY);

#-Количество друзей-#
$sel_friends_c = $pdo->prepare("SELECT COUNT(*) FROM `friends` WHERE `friend_1` = :user_id OR `friend_2` = :user_id");
$sel_friends_c->execute(array(':user_id' => $user['id']));
$amount = $sel_friends_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  

#-Вывод друзей-#
$sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE `friend_1` = :user_id OR `friend_2` = :user_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_friends->execute(array(':user_id' => $user['id']));
if($sel_friends->rowCount() != 0){
while($friends = $sel_friends->fetch(PDO::FETCH_LAZY)){
#-ID игрока-#
if($friends['friend_1'] == $user['id']){
$all_id = $friends['friend_2'];
}else{
$all_id = $friends['friend_1'];	
}
#-Выборка данных-#
if($_GET['type'] == 1 or $_GET['type'] > 3 or !isset($_GET['type'])){
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `ev_help`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $all_id));
}
if($_GET['type'] == 2){
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `ev_help`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online`, `clan_id` FROM `users` WHERE `id` = :all_id AND `clan_id` = :clan_id");
$sel_users->execute(array(':all_id' => $all_id, ':clan_id' => $user['clan_id']));
}
if($_GET['type'] == 3){
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `ev_help`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online` FROM `users` WHERE `id` = :all_id AND `time_online` > :time");
$sel_users->execute(array(':all_id' => $all_id, ':time' => time()-1200));
}
$all = $sel_users->fetch(PDO::FETCH_LAZY);

if(isset($all['nick'])){
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';
#-Состоим в бою или нет-#
$sel_boss_u = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :all_id");
$sel_boss_u->execute(array(':all_id' => $all['id']));
#-Стоит время у игрока или нет-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `boss_time` WHERE `user_id` = :all_id AND `boss_id` = :boss_id");
$sel_boss_t->execute(array(':all_id' => $all['id'], ':boss_id' => $boss_u['boss_id']));
#-Отправляли уведомление или нет-#
$sel_event = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :all_id AND `ev_id` = :battle_id");
$sel_event->execute(array(':all_id' => $all_id, ':battle_id' => $boss_u['battle_id']));
if($boss['level'] <= $all['level'] and $all['ev_help'] == 0 and $sel_boss_u-> rowCount() == 0 and $sel_boss_t-> rowCount() == 0 and $sel_event-> rowCount() == 0){
echo'<div class="menulitl">';
echo"<li><a href='/boss_invite?act=inv&all_id=$all[id]&battle_id=$boss_u[battle_id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span>".($user['clan_id'] == $all['clan_id'] ? '<div style="float: right; padding-right: 5px;"><img src="/style/images/body/clan.png" alt=""/></div>' : '')."<br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
echo'</div>';
}else{
#-Почему нельзя пригласить-#
if($boss['level'] > $all['level']) $er = '<span class="red">Низкий уровень</span>';
if($all['ev_help'] == 1) $er = '<span class="red">Запрет</span>';
if($sel_event-> rowCount() !=0 ) $er = '<span class="green">Отправлено</span>';
if($sel_boss_t-> rowCount() != 0) $er = '<span class="red">На отдыхе</span>';
if($sel_boss_u-> rowCount() != 0) $er = '<span class="red">В бою</span>';
echo"<div style='padding-left: 2px;'><div style='opacity: .5;'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span> $er ".($user['clan_id'] == $all['clan_id'] ? '<div style="float: right; padding-right: 5px;"><img src="/style/images/body/clan.png" alt=""/></div>' : '')."<br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></div></div>";
}
}
}

}else{
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Нет друзей!';
echo'</div>';
}
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
}
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/boss_battle'><img src='/style/images/body/back.png' alt=''/> Бой</a></li>";
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>