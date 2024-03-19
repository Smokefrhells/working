<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$head = 'Постройки клана';
require_once H.'system/head.php';
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
#-Проверяем что мы состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
} 
echo'<div class="page">';

#-Казармы-#
if($clan['quatity_user'] < 40){
$gold_kazarma = (($clan['quatity_user'] *25)+200);
}else{
$gold_kazarma = (($clan['quatity_user'] *45)+500);	
}
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/clan/building/barracks.jpg" alt=""/></div>';
echo'<div class="t_name"><b>Казармы</b> <span class="white">('.$clan['quatity_user'].'/100)</span><br/>';
echo'<div class="t_param"><span style="font-size:13px;color: #bfbfbf;">Увеличивают количество мест в клане</span></div>';
echo'</div>';
echo'</div>';
#-Если уровень не максимальный и есть права-#
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
if($clan['quatity_user'] < 100){
echo'<a href="/kazarma?act=kash&clan_id='.$clan['id'].'" class="button_green_a">+5 за <img src="/style/images/many/gold.png" alt="">'.$gold_kazarma.'</a>';
}else{
echo'<div class="button_red_a">Максимальный уровень</div>';	
}
echo'<div style="padding-top:5px;"></div>';
}

#-Замки-#
$zashita = 20000*$clan['zashita_lvl'];
if($clan['zashita_lvl'] == 10 or $clan['zashita_lvl'] == 20 or $clan['zashita_lvl'] == 30 or $clan['zashita_lvl'] == 40 or $clan['zashita_lvl'] == 50 or $clan['zashita_lvl'] == 60 or $clan['zashita_lvl'] == 70 or $clan['zashita_lvl'] == 80 or $clan['zashita_lvl'] == 90 or $clan['zashita_lvl'] == 100){
$many_zamki = (($clan['zashita_lvl'] * 320)+800);
$img_zamki = '<img src="/style/images/many/gold.png" alt=""/>';
}else{
$many_zamki = (($clan['zashita_lvl'] *30000)+2000);	
$img_zamki = '<img src="/style/images/many/silver.png" alt=""/>';
}
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/clan/building/zamok.jpg" alt=""/></div>';
echo'<div class="t_name"><b>Замки</b> <span class="white">('.$clan['zashita_lvl'].'/100)</span><br/>';
echo'<div class="t_param"><span style="font-size:13px;color: #bfbfbf;">Увеличивают защиту клана<br/><span class="green">Текущая защита: '.num_format($clan['zashita']).'</span></span></div>';
echo'</div>';
echo'</div>';
#-Если уровень не максимальный-#
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
if($clan['zashita_lvl'] < 100){
echo'<a href="/zamki_b?act=kash&clan_id='.$clan['id'].'" class="button_green_a">+'.num_format($zashita).' за '.$img_zamki.''.num_format($many_zamki).'</a>';
}else{
echo'<div class="button_red_a">Максимальный уровень</div>';	
}
echo'<div style="padding-top:5px;"></div>';
}

#-Амулет-#
$gold_amulet = (($clan['amulet_lvl'] *1000)+1000);
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/clan/building/amulet.jpg" alt=""/></div>';
echo'<div class="t_name"><b>Амулет</b> <span class="white">('.$clan['amulet_lvl'].'/10)</span><br/>';
echo'<div class="t_param"><span style="font-size:13px;color: #bfbfbf;">Увеличивает количество призовых мест в рейтинге опыта</span></div>';
echo'</div>';
echo'</div>';
#-Если уровень не максимальный и есть права-#
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
if($clan['amulet_lvl'] < 10){
echo'<a href="/amulet?act=kash&clan_id='.$clan['id'].'" class="button_green_a">+1 за <img src="/style/images/many/gold.png" alt=""/>'.$gold_amulet.'</a>';
}else{
echo'<div class="button_red_a">Максимальный уровень</div>';	
}
}
echo'<div style="padding-top:5px;"></div>';


#-Сокровищница-#
$gold_treasury = (($clan['treasury_lvl'] *800)+1000);
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/clan/building/treasury.jpg" alt=""/></div>';
echo'<div class="t_name"><b>Сокровищница</b> <span class="white">('.$clan['treasury_lvl'].'/10)</span><br/>';
echo'<div class="t_param"><span style="font-size:13px;color: #bfbfbf;">Увеличивает лимит взносов в казну клана</span></div>';
echo'</div>';
echo'</div>';
if($clan['level'] >= 10){
#-Если уровень не максимальный и есть права-#
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
if($clan['treasury_lvl'] < 10){
echo'<a href="/treasury?act=kash&clan_id='.$clan['id'].'" class="button_green_a">+1 за <img src="/style/images/many/gold.png" alt=""/>'.$gold_treasury.'</a>';
}else{
echo'<div class="button_red_a">Максимальный уровень</div>';	
}
}
}else{
echo'<div class="button_red_a">Доступно с <img src="/style/images/user/level.png" alt=""/>10 ур.</div>';	
}
echo'<div style="padding-top:5px;"></div>';

echo'</div>';
require_once H.'system/footer.php';
?>