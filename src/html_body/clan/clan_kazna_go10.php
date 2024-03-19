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
$head = 'Казна клана';
require_once H.'system/head.php';
echo'<div class="page">';
#-Проверяем что мы сотоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Сколько можно пополнить золотом и серебром-#
$gold = (((($user['level'])*5)+25)+$clan['treasury_lvl']*200);
$gold_ost = $gold - $clan_u['gold_t'];
$silver = ((($user['level']*10000)+$user['level']*35)+$clan['treasury_lvl']*100000);
$silver_ost = $silver - $clan_u['silver_t'];
echo'<div class="body_list">';
echo'<div class="menulist">';
echo"<div class='svg_list'>";
echo"<img src='/style/images/clan/kazna.png' alt=''/> Казна: <span class='gray'><img src='/style/images/many/gold.png' alt=''/>".num_format($clan['gold'])." <img src='/style/images/many/silver.png' alt=''/>".num_format($clan['silver'])."</span><br/>";
echo"<img src='/style/images/many/gold.png' alt=''/><span class='yellow'>Золото:</span> <span class='gray'>".num_format($gold_ost)." из ".num_format($gold)."</span><br/>";
echo"<img src='/style/images/many/silver.png' alt=''/><span class='yellow'>Серебро:</span> <span class='gray'>".num_format($silver_ost)." из ".num_format($silver)."</span>";
echo'</div>';
echo'<div class="line_1"></div>';
echo'</div>';
echo'</div>';
echo'<center>';
#-Форма пополнения золота-#
if($clan_u['gold_t'] != $gold){
echo'<form method="post" action="/clan_kazna_act?act=repl_gold&clan_id='.$clan['id'].'">';
echo'<input class="input_form" type="number" name="gold" placeholder="Кол-во золота"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Пополнить"/>';
echo'</form>';
}else{
$gold_time = $clan_u['gold_time']-time();
if($clan_u['gold_time'] >= time()){
echo'<div style="padding-top: 5px;"></div>';
echo'<center><div class="button_green_a"><img src="/style/images/body/time.png" alt=""/>'.(int)($gold_time/3600).' час. '.(int)($gold_time/60%60).' мин.</div></center>';
}
}
#-Форма пополнения серебра-#
if($clan_u['silver_t'] != $silver){
echo'<div style="padding-top: 5px;"></div>';
echo'<form method="post" action="/clan_kazna_act?act=repl_silver&clan_id='.$clan['id'].'">';
echo'<input class="input_form" type="number" name="silver" placeholder="Кол-во серебра"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Пополнить"/>';
echo'</form>';
}else{
$silver_time = $clan_u['silver_time']-time();
if($clan_u['silver_time'] >= time()){
echo'<div style="padding-top: 5px;"></div>';
echo'<center><div class="button_green_a"><img src="/style/images/body/time.png" alt=""/>'.(int)($silver_time/3600).' час. '.(int)($silver_time/60%60).' мин.</div></center>';
}
}
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
#-5 последних пополнений казны-#
$sel_log = $pdo->prepare("SELECT * FROM `clan_log` WHERE `clan_id` = :clan_id AND `type` = 3 ORDER BY `time` DESC LIMIT 5");
$sel_log->execute(array(':clan_id' => $clan['id']));
#-Если есть записи-#
if($sel_log-> rowCount() != 0){
echo'<div class="body_list">';
while($log = $sel_log->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo"<img src='/style/images/clan/kazna.png' alt=''/>$log[log]";
echo'</div>';
}
echo'</div>';
}
}else{
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Вы не состоите в этом клане!';
echo'</div>';
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>