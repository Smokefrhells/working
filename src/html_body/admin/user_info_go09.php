<?php
require_once '../../system/system.php';
$head = 'Информация';
echo only_reg();
require_once H.'system/head.php';
#-Права-#
if(($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3) and $user['id'] != $all['id']){
#-Данные игрока-#
if(isset($_GET['user_id'])){
$user_id = check($_GET['user_id']);

#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `gold`, `silver`, `crystal`, `key`, `snow`, `email`, `ip`, `lot` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $user_id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);

echo'<div class="line_1_v"></div>';
echo'<div class="user_info">';
#-Только для админа-#
if($user['prava'] == 1){
echo'<img src="/style/images/many/gold.png" alt=""/> Золото: <span class="yellow">'.num_format($all['gold']).'</span><br/>';	
echo'<img src="/style/images/many/silver.png" alt=""/> Серебро: <span class="yellow">'.num_format($all['silver']).'</span><br/>';	
echo'<img src="/style/images/many/crystal.png" alt=""/> Кристаллы: <span class="yellow">'.num_format($all['crystal']).'</span><br/>';	
echo'<img src="/style/images/body/key.png" alt=""/> Ключи: <span class="yellow">'.num_format($all['key']).'</span><br/>';
echo'<img src="/style/images/many/snow.png" alt=""/> Снежки: <span class="yellow">'.num_format($all['snow']).'</span><br/>';
echo'<img src="/style/images/body/lot.png" alt=""/> Кол-во лотов в аукционе: <span class="yellow">'.num_format($all['lot']).'</span><br/>';
#-Кол-во рефералов-#
$sel_users_ref = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `ref_id` = :all_id");
$sel_users_ref->execute(array(':all_id' => $all['id']));
$users_ref = $sel_users_ref->fetch(PDO::FETCH_LAZY);
echo'<img src="/style/images/body/ref.png" alt=""/> Кол-во рефералов: <span class="yellow">'.$users_ref[0].'</span><br/>';
}

echo'<img src="/style/images/user/event.png" alt=""/> Email: <span class="yellow">'.$all['email'].'</span><br/>';	
echo'<img src="/style/images/body/ip.png" alt=""/> IP адрес: <span class="yellow">'.$all['ip'].'</span><br/>';
$sel_ip_users = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `ip` = :user_ip AND `id` != :all_id");
$sel_ip_users->execute(array(':user_ip' => $all['ip'], ':all_id' => $all['id']));
$ip_users = $sel_ip_users->fetch(PDO::FETCH_LAZY);
echo'<img src="/style/images/user/user.png" alt=""/> Возможные мульты: <span class="yellow">'.$ip_users[0].'</span><br/>';
echo'</div>';

#-Выборка и вывод мультов-#
$sel_users_myl = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol`, `level`, `ip`, `block` FROM `users` WHERE `ip` = :all_ip AND `id` != :all_id");
$sel_users_myl->execute(array(':all_ip' => $all['ip'], ':all_id' => $all['id']));
if($ip_users[0] > 0){
echo'<div class="line_1"></div>';
echo'<div class="mini-line"></div>';
echo'<div class="line_1_v"></div>';
echo'<div class="body_list">';	
echo'<div class="svg_list"><img src="/style/images/user/user.png" alt=""/> Список мультов: <span class="yellow">'.$ip_users[0].'</span></div>';
echo'<div class="menulitl">';
while($users_myl = $sel_users_myl->fetch(PDO::FETCH_LAZY)){
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$users_myl[id]'> <img src='".avatar_img_min($users_myl['avatar'], $users_myl['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='/style/images/user/user.png'> <span class='menulitl_name'>$users_myl[nick]</span> ".($users_myl['block'] == 0 ? '<span class="green">Не заблокирован</span>' : '<span class="red">Заблокирован</span>')."<br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$users_myl[level] <img src='/style/images/body/ip.png' alt=''/>$users_myl[ip]</div></div></a></li>";	
}


echo'</div>';
echo'</div>';
}

echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='/style/images/body/back.png' atl=''/> Назад</a></li>";
echo'</div>';
echo'</div>';
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Игрок не найден!</div>';
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Данные не переданы!</div>';
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Нет прав!</div>';
}
require_once H.'system/footer.php';
?>