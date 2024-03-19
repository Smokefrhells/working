<?php
require_once '../../system/system.php';
$head = 'Питомцы';
echo only_reg();
echo pets_level();
require_once H.'system/head.php';
echo'<div class="page">';

#-Выборка данных питомцев-#
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id ORDER BY `active` DESC");
$sel_pets_me->execute(array(':user_id' => $user['id']));
if($sel_pets_me-> rowCount() != 0){
while($pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY))  
{
#-Способность питомца-#	
if($pets_me['pets_id'] == 1){$ability = "<img src='/style/images/pets/ability/thief.gif' alt=''/><span class='yellow'>Везение:</span> Шанс срабатывания $pets_me[abi_prosent]% [Max: 35%]"; $abi_prosent = 35;}
if($pets_me['pets_id'] == 2){$ability = "<img src='/style/images/pets/ability/absorb.gif' alt=''/><span class='yellow'>Поглощение:</span> Шанс срабатывания $pets_me[abi_prosent]% [Max: 30%]"; $abi_prosent = 30;}	
if($pets_me['pets_id'] == 3){$ability = "<img src='/style/images/pets/ability/crete.gif' alt=''/><span class='yellow'>Крит:</span> Шанс срабатывания $pets_me[abi_prosent]% [Max: 30%]"; $abi_prosent = 30;}	
if($pets_me['pets_id'] == 4){$ability = "<img src='/style/images/pets/ability/dodge.gif' alt=''/><span class='yellow'>Уворот:</span> Шанс срабатывания $pets_me[abi_prosent]% [Max: 25%]"; $abi_prosent = 25;}
if($pets_me['pets_id'] == 5){$ability = "<img src='/style/images/pets/ability/treatment.gif' alt=''/><span class='yellow'>Вампиризм:</span> Шанс срабатывания $pets_me[abi_prosent]% [Max: 20%]"; $abi_prosent = 20;}	

#-Выборка данных питомца-#
$sel_pets = $pdo->prepare("SELECT `id`, `name`, `sila`, `zashita`, `health`, `images` FROM `pets` WHERE `id` = :pets_id");
$sel_pets->execute(array(':pets_id' => $pets_me['pets_id']));
$pets = $sel_pets->fetch(PDO::FETCH_LAZY);	

#-Урон-#
$pets_uron_1 = round(((($pets_me['b_param']+$pets['sila']) * 0.2)), 0);
$pets_uron_2 = round(((($pets_me['b_param']+$pets['sila']) * 0.25)), 0);

#-Вывод питомца-#
echo'<div class="t_max">';
echo'<img src="'.$pets['images'].'" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>'.$pets['name'].'</b> '.($pets_me['active'] == 1 ? "<span class='green'>(Выбрано)</span>" : "").'<br/> <span class="t_param"><img src="/style/images/user/sila.png" alt=""/>'.($pets['sila']+$pets_me['b_param']).' <img src="/style/images/user/zashita.png" alt=""/>'.($pets['zashita']+$pets_me['b_param']).'  <img src="/style/images/user/health.png" alt=""/>'.($pets['health']+$pets_me['b_param']).'</span><br/><img src="/style/images/body/attack.png" alt=""/><span class="red">Урон: '.$pets_uron_1.'-'.$pets_uron_2.'</span><br/><img src="/style/images/user/level.png" alt=""/>Уровень тренировки: '.$pets_me['max_level'].' из 100<br/>'.$ability.'</div>';
echo'</div>';

#-Если не активирован-#
if($pets_me['active'] == 0){
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/pets_act?act=active&pets_id=$pets_me[id]' class='button_green_a'>Выбрать</a>";
}
echo'<div style="padding-top: 3px;"></div>';
	
#-Тренировка питомца-#
if($pets_me['max_level'] < 100){
#-Прокачка за золото-#
if($pets_me['b_level'] >= 3){
$gold = (($pets_me['max_level'] * 2)*$pets_me['pets_id']);
if($user['gold'] >= $gold){
echo"<a href='/pets_act?act=traing&pets_id=$pets_me[id]' class='button_green_a'>+".($pets_me['max_level']*$pets_me['pets_id'])." к параметрам за <img src='/style/images/many/gold.png' alt=''/>$gold</a>";
}else{
echo"<div class='button_red_a'>+".($pets_me['max_level']*$pets_me['pets_id'])." к параметрам за <img src='/style/images/many/gold.png' alt=''/>$gold</div>";
}
}else{
$silver = round(((627 * $pets_me['max_level'])) * $pets_me['pets_id'], 0);
if($user['silver'] >= $silver){
echo"<a href='/pets_act?act=traing&pets_id=$pets_me[id]' class='button_green_a'>+".($pets_me['max_level']*$pets_me['pets_id'])." к параметрам за <img src='/style/images/many/silver.png' alt=''/>$silver</a>";	
}else{
echo"<div class='button_red_a'>+".($pets_me['max_level']*$pets_me['pets_id'])." к параметрам за <img src='/style/images/many/silver.png' alt=''/>$silver</div>";	
}
}
echo'<div style="padding-top: 3px;"></div>';	
}

#-Тренировка способности-#
if($pets_me['abi_prosent'] < $abi_prosent){
$abi_gold = ($pets_me['pets_id']*$pets_me['abi_prosent'])*5;
#-Достаточно золота-#
if($user['gold'] >= $abi_gold){
echo"<a href='/pets_act?act=abi_traing&pets_id=$pets_me[id]' class='button_green_a'>+1% к способности <img src='/style/images/many/gold.png' alt=''/>$abi_gold</a>";		
}else{
echo"<div class='button_red_a'>+1% к способности <img src='/style/images/many/gold.png' alt=''/>$abi_gold</div>";		
}
echo'<div style="padding-top: 3px;"></div>';	
}
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Нет питомцев!</div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<li><a href="/magazin_pets"><img src="/style/images/many/gold.png" alt=""/> Купить питомца</a></li>';
echo'</div>';
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>