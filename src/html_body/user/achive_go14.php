<?php
require_once '../../system/system.php';
$head = 'Достижения';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';

#-Навигация-#
$one = '<a href="/achive?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 3 or !isset($_GET['type']))  ? "<b>I ранг</b>" : "I ранг").'</span></a>';
$two = '<a href="/achive?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>II ранг</b>" : "II ранг").'</span></a>';
$three = '<a href="/achive?type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>III ранг</b>" : "III ранг").'</span></a>';

#-Вывод-#
echo'<div style="padding: 5px;">';
echo''.$one.' '.$two.' '.$three.'';
echo'</div>';
	

echo'<div class="menulist">';

                                                                     #-I РАНГ-#
if(!isset($_GET['type']) or $_GET['type'] == 1 or $_GET['type'] > 3){
                                                                 #-Сохранение героя-#
echo'<div class="line_1"></div>';	
if($user['ach_save'] == 0){
echo'<li><a href="/ach_save_act?act=get">';
echo'<img src="/style/images/body/ok.png" alt=""/><span style="font-size:15px;"><b>Незнакомец</b> '.($user['save'] == 1 ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Сохрани своего героя</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/silver.png" alt=""/>2\'000 и <img src="/style/images/body/traing.png" alt=""/>Доспех Рыцаря</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/body/ok.png" alt=""/><span style="font-size:15px;"><b>Незнакомец</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Сохрани своего героя</span><br/>';
echo'</div></a></li>';
}                         
                                                                       #-ОХОТА-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_ohota'] == 0){
$kolvo_o = 100;
$nagrada_o = '<img src="/style/images/many/silver.png" alt=""/>12\'000 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_ohota'] == 1){
$kolvo_o = 1500;
$nagrada_o = '<img src="/style/images/many/silver.png" alt=""/>50\'000 и <img src="/style/images/body/key.png" alt=""/>12';
}
#-Третье достижение-#
if($user['ach_ohota'] == 2){
$kolvo_o = 2000;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_ohota'] == 3){
$kolvo_o = 4500;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>200 и <img src="/style/images/body/key.png" alt=""/>50';
}
#-Пятое достижение-#
if($user['ach_ohota'] == 4){
$kolvo_o = 6000;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>350 и <img src="/style/images/many/silver.png" alt=""/>100\'000';
}
#-Шестое достижение-#
if($user['ach_ohota'] == 5){
$kolvo_o = 7500;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>450 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижение-#
if($user['ach_ohota'] == 6){
$kolvo_o = 9000;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>550 и <img src="/style/images/body/key.png" alt=""/>80';
}
#-Восьмое достижение-#
if($user['ach_ohota'] == 7){
$kolvo_o = 10500;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>750 и <img src="/style/images/many/silver.png" alt=""/>200\'000';
}
#-Девятое достижение-#
if($user['ach_ohota'] == 8){
$kolvo_o = 12000;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>900 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Десятое достижение-#
if($user['ach_ohota'] == 9){
$kolvo_o = 14000;
$nagrada_o = '<img src="/style/images/many/gold.png" alt=""/>1\'200 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}

if($user['ach_ohota'] != 10){
$battle_o = $user['hunting_b_1']+$user['hunting_b_2']+$user['hunting_b_3']+$user['hunting_b_4']+$user['hunting_b_5']+$user['hunting_b_6']+$user['hunting_b_7'];
echo'<li><a href="/ach_ohota_act?act=get">';
echo'<img src="/style/images/body/ohota.png" alt=""/><span style="font-size:15px;"><b>Охотник</b> '.($battle_o >= $kolvo_o ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Проведи необходимое кол-во боев</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Боев: <span class="gray">'.$battle_o.' из '.$kolvo_o.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_o.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/body/ohota.png" alt=""/><span style="font-size:15px;"><b>Охотник</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Проведи необходимое кол-во боев</span><br/>';
echo'</div></a></li>';
}      
                                                                       #-ДУЭЛИ-#
if($user['level'] >= 5){																	  
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_duel'] == 0){
$kolvo_d = 10;
$nagrada_d = '<img src="/style/images/many/silver.png" alt=""/>2500 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_duel'] == 1){
$kolvo_d = 50;
$nagrada_d = '<img src="/style/images/many/silver.png" alt=""/>5000 и <img src="/style/images/body/key.png" alt=""/>5';
}
#-Третье достижение-#
if($user['ach_duel'] == 2){
$kolvo_d = 100;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>30 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_duel'] == 3){
$kolvo_d = 300;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>80 и <img src="/style/images/body/key.png" alt=""/>30';
}
#-Пятое достижение-#
if($user['ach_duel'] == 4){
$kolvo_d = 500;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>120 и <img src="/style/images/many/silver.png" alt=""/>50000';
}
#-Шестое достижение-#
if($user['ach_duel'] == 5){
$kolvo_d = 1000;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>200 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижение-#
if($user['ach_duel'] == 6){
$kolvo_d = 1500;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>250 и <img src="/style/images/body/key.png" alt=""/>50';
}
#-Восмое достижение-#
if($user['ach_duel'] == 7){
$kolvo_d = 2000;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>350 и <img src="/style/images/many/silver.png" alt=""/>100000';
}
#-Восьмое достижение-#
if($user['ach_duel'] == 8){
$kolvo_d = 3000;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>400 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Девятое достижение-#
if($user['ach_duel'] == 9){
$kolvo_d = 5000;
$nagrada_d = '<img src="/style/images/many/gold.png" alt=""/>500 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}

if($user['ach_duel'] != 10){
echo'<li><a href="/ach_duel_act?act=get">';
echo'<img src="/style/images/body/league.png" alt=""/><span style="font-size:15px;"><b>Дуэлянт</b> '.($user['duel_pobeda'] >= $kolvo_d ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Побеждай в дуэлях</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Боев: <span class="gray">'.$user['duel_pobeda'].' из '.$kolvo_d.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_d.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/body/league.png" alt=""/><span style="font-size:15px;"><b>Дуэлянт</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Побеждай в дуэлях</span><br/>';
echo'</div></a></li>';
}
}
                                                                       #-БОССЫ-#
if($user['level'] >= 5){																	  
echo'<div class="line_1"></div>';	

#-Первое достижение-#
if($user['ach_boss'] == 0){
$kolvo_b = 3;
$nagrada_b = '<img src="/style/images/many/silver.png" alt=""/>10\'000 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_boss'] == 1){
$kolvo_b = 10;
$nagrada_b = '<img src="/style/images/many/silver.png" alt=""/>15\'000 и <img src="/style/images/body/key.png" alt=""/>10';
}
#-Третье достижение-#
if($user['ach_boss'] == 2){
$kolvo_b = 25;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>50 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_boss'] == 3){
$kolvo_b = 75;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/many/silver.png" alt=""/>35\'000';
}
#-Пятое достижение-#
if($user['ach_boss'] == 4){
$kolvo_b = 175;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>150 и <img src="/style/images/many/silver.png" alt=""/>50\'000';
}
#-Шестое достижение-#
if($user['ach_boss'] == 5){
$kolvo_b = 325;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>225 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_boss'] == 6){
$kolvo_b = 725;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>400 и <img src="/style/images/body/key.png" alt=""/>25';
}
#-Восьмое достижение-#
if($user['ach_boss'] == 7){
$kolvo_b = 1475;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>800 и <img src="/style/images/many/silver.png" alt=""/>100\'000';
}
#-Девятое достижение-#
if($user['ach_boss'] == 8){
$kolvo_b = 2975;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>1600 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Десятое достижение-#
if($user['ach_boss'] == 9){
$kolvo_b = 5975;
$nagrada_b = '<img src="/style/images/many/gold.png" alt=""/>2200 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}

if($user['ach_boss'] != 10){
echo'<li><a href="/ach_boss_act?act=get">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Уничтожитель</b> '.($user['boss_pobeda'] >= $kolvo_b ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Побеждай в сражениях с Боссами<br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Боев: <span class="gray">'.$user['boss_pobeda'].' из '.$kolvo_b.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_b.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Уничтожитель</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Побеждай в сражениях с Боссами<br/>';
echo'</div></a></li>';
}
}  
                                                                   #-ЗАДАНИЯ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_tasks'] == 0){
$kolvo_t = 3;
$nagrada_t = '<img src="/style/images/many/silver.png" alt=""/>10\'000 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_tasks'] == 1){
$kolvo_t = 5;
$nagrada_t = '<img src="/style/images/many/silver.png" alt=""/>15\'500 и <img src="/style/images/body/key.png" alt=""/>15';
}
#-Третье достижение-#
if($user['ach_tasks'] == 2){
$kolvo_t = 15;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>50 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_tasks'] == 3){
$kolvo_t = 25;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>75 и <img src="/style/images/many/silver.png" alt=""/>50\'000';
}
#-Пятое достижение-#
if($user['ach_tasks'] == 4){
$kolvo_t = 75;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>150 и <img src="/style/images/many/silver.png" alt=""/>75\'000';
}
#-Шестое достижение-#
if($user['ach_tasks'] == 5){
$kolvo_t = 150;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>200 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_tasks'] == 6){
$kolvo_t = 250;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>300 и <img src="/style/images/body/key.png" alt=""/>40';
}
#-Восьмое достижение-#
if($user['ach_tasks'] == 7){
$kolvo_t = 400;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>450 и <img src="/style/images/many/silver.png" alt=""/>100\'000';
}
#-Девятое достижение-#
if($user['ach_tasks'] == 8){
$kolvo_t = 600;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>600 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Десятое достижение-#
if($user['ach_tasks'] == 9){
$kolvo_t = 1000;
$nagrada_t = '<img src="/style/images/many/gold.png" alt=""/>800 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}

if($user['ach_tasks'] != 10){
echo'<li><a href="/ach_tasks_act?act=get">';
echo'<img src="/style/images/body/daily_tasks.png" alt=""/><span style="font-size:15px;"><b>Исполнитель</b> '.($user['tasks'] >= $kolvo_t ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Выполни необходимое количество ежедневных заданий<br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Заданий выполнено: <span class="gray">'.$user['tasks'].' из '.$kolvo_t.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_t.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/body/daily_tasks.png" alt=""/><span style="font-size:15px;"><b>Исполнитель</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Выполни необходимое количество ежедневных заданий<br/>';
echo'</div></a></li>';
}                                                                                                                                  
															       #-ГЕРОЙ I РАНГА-#
echo'<div class="line_1"></div>';	
if($user['ach_heros_1'] == 0){
echo'<li><a href="/ach_heros1_act?act=get">';
echo'<img src="/style/images/quality/3.png" alt=""/><span style="font-size:15px;"><b>Герой I Ранга</b> '.($user['ach_heros_1'] == 1 ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Выполни все достижения I ранга<br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>2\'000 и <img src="/style/images/body/traing.png" alt=""/>Комплект Паладина</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/quality/3.png" alt=""/><span style="font-size:15px;"><b>Герой I Ранга</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Выполни все достижения I ранга<br/>';
echo'</div></a></li>';
}			
}


                                                                   #-II РАНГ-#
if($_GET['type'] == 2){	
                                                                    #-ЗАМКИ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_zamki'] == 0){
$kolvo_z = 2;
$star_z = 'star0';
$nagrada_z = '<img src="/style/images/many/silver.png" alt=""/>13\'000 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_zamki'] == 1){
$kolvo_z = 5;
$star_z = 'star1';
$nagrada_z = '<img src="/style/images/many/silver.png" alt=""/>20\'000 и <img src="/style/images/body/key.png" alt=""/>10';
}
#-Третье достижение-#
if($user['ach_zamki'] == 2){
$kolvo_z = 15;
$star_z = 'star2';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_zamki'] == 3){
$kolvo_z = 30;
$star_z = 'star3';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>150 и <img src="/style/images/many/silver.png" alt=""/>100\'000';
}
#-Пятое достижение-#
if($user['ach_zamki'] == 4){
$kolvo_z = 60;
$star_z = 'star4';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>200 и <img src="/style/images/many/silver.png" alt=""/>150\'000';
}
#-Шестое достижение-#
if($user['ach_zamki'] == 5){
$kolvo_z = 100;
$star_z = 'star5';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>250 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_zamki'] == 6){
$kolvo_z = 150;
$star_z = 'star6';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>400 и <img src="/style/images/body/key.png" alt=""/>20';
}
#-Восьмое достижение-#
if($user['ach_zamki'] == 7){
$kolvo_z = 250;
$star_z = 'star7';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>600 и <img src="/style/images/many/silver.png" alt=""/>150\'000';
}
#-Девятое достижение-#
if($user['ach_zamki'] == 8){
$kolvo_z = 400;
$star_z = 'star8';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>800 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Десятое достижение-#
if($user['ach_zamki'] == 9){
$kolvo_z = 500;
$star_z = 'star9';
$nagrada_z = '<img src="/style/images/many/gold.png" alt=""/>1000 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_zamki'] == 10){
$star_z = 'star10';
}

if($user['ach_zamki'] != 10){
echo'<li><a href="/ach_zamki_act?act=get">';
echo'<img src="/style/images/body/zamki.png" alt=""/><span style="font-size:15px;"><b>Защитник</b> '.($user['zamki_pobeda'] >= $kolvo_z ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Защищай свою сторону в сражении Замки</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Побед: <span class="gray">'.$user['zamki_pobeda'].' из '.$kolvo_z.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_z.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=2"><div style="opacity: .5;">';
echo'<img src="/style/images/body/zamki.png" alt=""/><span style="font-size:15px;"><b>Защитник</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Защищай свою сторону в сражении Замки</span><br/>';
echo'</div></a></li>';
}	
                                                                    #-РЕЙД-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_reid'] == 0){
$kolvo_r = 1;
$star_r = 'star0';
$nagrada_r = '<img src="/style/images/many/silver.png" alt=""/>15\'000 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_reid'] == 1){
$kolvo_r = 3;
$star_r = 'star1';
$nagrada_r = '<img src="/style/images/many/silver.png" alt=""/>25\'000 и <img src="/style/images/body/key.png" alt=""/>5';
}
#-Третье достижение-#
if($user['ach_reid'] == 2){
$kolvo_r = 7;
$star_r = 'star2';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_reid'] == 3){
$kolvo_r = 15;
$star_r = 'star3';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>130 и <img src="/style/images/many/silver.png" alt=""/>120\'000';
}
#-Пятое достижение-#
if($user['ach_reid'] == 4){
$kolvo_r = 25;
$star_r = 'star4';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>240 и <img src="/style/images/many/silver.png" alt=""/>160\'000';
}
#-Шестое достижение-#
if($user['ach_reid'] == 5){
$kolvo_r = 50;
$star_r = 'star5';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>470 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_reid'] == 6){
$kolvo_r = 85;
$star_r = 'star6';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>650 и <img src="/style/images/body/key.png" alt=""/>15';
}
#-Восьмое достижение-#
if($user['ach_reid'] == 7){
$kolvo_r = 150;
$star_r = 'star7';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>850 и <img src="/style/images/many/silver.png" alt=""/>200\'000';
}
#-Девятое достижение-#
if($user['ach_reid'] == 8){
$kolvo_r = 250;
$star_r = 'star8';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>1100 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Десятое достижение-#
if($user['ach_reid'] == 9){
$kolvo_r = 400;
$star_r = 'star9';
$nagrada_r = '<img src="/style/images/many/gold.png" alt=""/>1500 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_reid'] == 10){
$star_r = 'star10';
}

if($user['ach_reid'] != 10){
echo'<li><a href="/ach_reid_act?act=get">';
echo'<img src="/style/images/body/reid.png" alt=""/><span style="font-size:15px;"><b>Рейдовик</b> '.($user['reid_pobeda'] >= $kolvo_r ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Побеждай боссов в сражении Рейд</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Побед: <span class="gray">'.$user['reid_pobeda'].' из '.$kolvo_r.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_r.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=2"><div style="opacity: .5;">';
echo'<img src="/style/images/body/reid.png" alt=""/><span style="font-size:15px;"><b>Рейдовик</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Побеждай боссов в сражении Рейд</span><br/>';
echo'</div></a></li>';
}	
                                                                    #-СУНДУКИ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_chest'] == 0){
$kolvo_c = 3;
$star_c = 'star0';
$nagrada_c = '<img src="/style/images/many/silver.png" alt=""/>15\'000 и <img src="/style/images/body/key.png" alt=""/>2';
}
#-Второе достижение-#
if($user['ach_chest'] == 1){
$kolvo_c = 7;
$star_c = 'star1';
$nagrada_c = '<img src="/style/images/many/silver.png" alt=""/>30\'000 и <img src="/style/images/body/key.png" alt=""/>5';
}
#-Третье достижение-#
if($user['ach_chest'] == 2){
$kolvo_c = 25;
$star_c = 'star2';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>50 и <img src="/style/images/many/silver.png" alt=""/>50\'000';
}
#-Четвертое достижение-#
if($user['ach_chest'] == 3){
$kolvo_c = 40;
$star_c = 'star3';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/many/silver.png" alt=""/>75\'000';
}
#-Пятое достижение-#
if($user['ach_chest'] == 4){
$kolvo_c = 100;
$star_c = 'star4';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>150 и <img src="/style/images/many/silver.png" alt=""/>100\'000';
}
#-Шестое достижение-#
if($user['ach_chest'] == 5){
$kolvo_c = 200;
$star_c = 'star5';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>250 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_chest'] == 6){
$kolvo_c = 400;
$star_c = 'star6';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>450 и <img src="/style/images/body/key.png" alt=""/>15';
}
#-Восьмое достижение-#
if($user['ach_chest'] == 7){
$kolvo_c = 600;
$star_c = 'star7';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>650 и <img src="/style/images/many/silver.png" alt=""/>150\'000';
}
#-Девятое достижение-#
if($user['ach_chest'] == 8){
$kolvo_c = 800;
$star_c = 'star8';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>850 и <img src="/style/images/many/silver.png" alt=""/>250\'000';
}
#-Десятое достижение-#
if($user['ach_chest'] == 9){
$kolvo_c = 1000;
$star_c = 'star9';
$nagrada_c = '<img src="/style/images/many/gold.png" alt=""/>1100 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_chest'] == 10){
$star_c = 'star10';
}

if($user['ach_chest'] != 10){
echo'<li><a href="/ach_chest_act?act=get">';
echo'<img src="/style/images/body/chest.png" alt=""/><span style="font-size:15px;"><b>Добытчик</b> '.($user['chest'] >= $kolvo_c ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Открой нужное кол-во сундуков</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Сундуков открыто: <span class="gray">'.$user['chest'].' из '.$kolvo_c.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_c.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=2"><div style="opacity: .5;">';
echo'<img src="/style/images/body/reid.png" alt=""/><span style="font-size:15px;"><b>Добытчик</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Открой нужное кол-во сундуков</span><br/>';
echo'</div></a></li>';
}		
                                                                        #-КЛАН-#
echo'<div class="line_1"></div>';	
if($user['ach_clan'] == 0){
echo'<li><a href="/ach_clan_act?act=get">';
echo'<img src="/style/images/body/clan.png" alt=""/><span style="font-size:15px;"><b>Укрепленный</b> '.($user['clan_id'] != 0 ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Вступи или создай свой клан</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>250 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=2"><div style="opacity: .5;">';
echo'<img src="/style/images/body/ok.png" alt=""/><span style="font-size:15px;"><b>Укрепленный</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Вступи или создай свой клан</span><br/>';
echo'</div></a></li>';
}
                                                                  #-ГЕРОЙ II РАНГА-#
echo'<div class="line_1"></div>';	
if($user['ach_heros_2'] == 0){
echo'<li><a href="/ach_heros2_act?act=get">';
echo'<img src="/style/images/quality/3.png" alt=""/><span style="font-size:15px;"><b>Герой II Ранга</b> '.($user['ach_heros_2'] == 1 ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Выполни все достижения I и II рангов<br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>3\'000 и <img src="/style/images/body/traing.png" alt=""/>Комплект Следопыта</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=2"><div style="opacity: .5;">';
echo'<img src="/style/images/quality/3.png" alt=""/><span style="font-size:15px;"><b>Герой II Ранга</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Выполни все достижения I и II рангов<br/>';
echo'</div></a></li>';
}
}


                                                                    #-III РАНГ-#
if($_GET['type'] == 3){
                                      					          #-НОВЫЙ УРОВЕНЬ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_level'] == 0){
$kolvo_l = 5;
$star_l = 'star0';
$nagrada_l = '<img src="/style/images/many/silver.png" alt=""/>25\'000 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_level'] == 1){
$kolvo_l = 15;
$star_l = 'star1';
$nagrada_l = '<img src="/style/images/many/silver.png" alt=""/>50\'000 и <img src="/style/images/body/key.png" alt=""/>10';
}
#-Третье достижение-#
if($user['ach_level'] == 2){
$kolvo_l = 25;
$star_l = 'star2';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Четвертое достижение-#
if($user['ach_level'] == 3){
$kolvo_l = 35;
$star_l = 'star3';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>125 и <img src="/style/images/many/silver.png" alt=""/>75\'000';
}
#-Пятое достижение-#
if($user['ach_level'] == 4){
$kolvo_l = 45;
$star_l = 'star4';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>175 и <img src="/style/images/many/silver.png" alt=""/>100\'000';
}
#-Шестое достижение-#
if($user['ach_level'] == 5){
$kolvo_l = 55;
$star_l = 'star5';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>250 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_level'] == 6){
$kolvo_l = 65;
$star_l = 'star6';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>325 и <img src="/style/images/body/key.png" alt=""/>25';
}
#-Восьмое достижение-#
if($user['ach_level'] == 7){
$kolvo_l = 75;
$star_l = 'star7';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>400 и <img src="/style/images/many/silver.png" alt=""/>200\'000';
}
#-Девятое достижение-#
if($user['ach_level'] == 8){
$kolvo_l = 95;
$star_l = 'star8';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>550 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Десятое достижение-#
if($user['ach_level'] == 9){
$kolvo_l = 100;
$star_l = 'star9';
$nagrada_l = '<img src="/style/images/many/gold.png" alt=""/>700 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_level'] == 10){
$star_l = 'star10';
}

if($user['ach_level'] != 10){
echo'<li><a href="/ach_level_act?act=get">';
echo'<img src="/style/images/user/level.png" alt=""/><span style="font-size:15px;"><b>Прокачанный</b> '.($user['level'] >= $kolvo_l ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Получи требуемый уровень Героя</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Требуемый уровень: <img src="/style/images/user/level.png" alt=""/><span class="gray">'.$kolvo_l.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_l.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive"><div style="opacity: .5;">';
echo'<img src="/style/images/user/level.png" alt=""/><span style="font-size:15px;"><b>Прокачанный</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Получи требуемый уровень Героя</span><br/>';
echo'</div></a></li>';
}	
                                                                    #-СИЛЬНЕЙШИЙ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_sila'] == 0){
$kolvo_s = 20;
$star_s = 'star0';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_sila'] == 1){
$kolvo_s = 40;
$star_s = 'star1';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>150';
}
#-Третье достижение-#
if($user['ach_sila'] == 2){
$kolvo_s = 60;
$star_s = 'star2';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>200';
}
#-Четвертое достижение-#
if($user['ach_sila'] == 3){
$kolvo_s = 80;
$star_s = 'star3';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>250';
}
#-Пятое достижение-#
if($user['ach_sila'] == 4){
$kolvo_s = 100;
$star_s = 'star4';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>300';
}
#-Шестое достижение-#
if($user['ach_sila'] == 5){
$kolvo_s = 120;
$star_s = 'star5';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>350 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_sila'] == 6){
$kolvo_s = 140;
$star_s = 'star6';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>400';
}
#-Восьмое достижение-#
if($user['ach_sila'] == 7){
$kolvo_s = 160;
$star_s = 'star7';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>450';
}
#-Девятое достижение-#
if($user['ach_sila'] == 8){
$kolvo_s = 180;
$star_s = 'star8';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>500';
}
#-Десятое достижение-#
if($user['ach_sila'] == 9){
$kolvo_s = 200;
$star_s = 'star9';
$nagrada_s = '<img src="/style/images/many/gold.png" alt=""/>550 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_sila'] == 10){
$star_s = 'star10';
}

if($user['ach_sila'] != 10){
echo'<li><a href="/ach_level_sila_act?act=get">';
echo'<img src="/style/images/user/sila.png" alt=""/><span style="font-size:15px;"><b>Сильнейший</b> '.($user['level_sila'] >= $kolvo_s ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Прокачай требуемый уровень Силы</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Уровень: <span class="gray"><img src="/style/images/user/level.png" alt=""/>'.$user['level_sila'].' из <img src="/style/images/user/level.png" alt=""/>'.$kolvo_s.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_s.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=3"><div style="opacity: .5;">';
echo'<img src="/style/images/user/sila.png" alt=""/><span style="font-size:15px;"><b>Сильнейший</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Прокачай требуемый уровень Силы</span><br/>';
echo'</div></a></li>';
}
                                                                    #-ЗАЩИЩЕННЫЙ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_zashita'] == 0){
$kolvo_za = 20;
$star_za = 'star0';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>100 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_zashita'] == 1){
$kolvo_za = 40;
$star_za = 'star1';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>150';
}
#-Третье достижение-#
if($user['ach_zashita'] == 2){
$kolvo_za = 60;
$star_za = 'star2';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>200';
}
#-Четвертое достижение-#
if($user['ach_zashita'] == 3){
$kolvo_za = 80;
$star_za = 'star3';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>250';
}
#-Пятое достижение-#
if($user['ach_zashita'] == 4){
$kolvo_za = 100;
$star_za = 'star4';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>300';
}
#-Шестое достижение-#
if($user['ach_zashita'] == 5){
$kolvo_za = 120;
$star_za = 'star5';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>350 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_zashita'] == 6){
$kolvo_za = 140;
$star_za = 'star6';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>400';
}
#-Восьмое достижение-#
if($user['ach_zashita'] == 7){
$kolvo_za = 160;
$star_za = 'star7';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>450';
}
#-Девятое достижение-#
if($user['ach_zashita'] == 8){
$kolvo_za = 180;
$star_za = 'star8';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>500';
}
#-Десятое достижение-#
if($user['ach_zashita'] == 9){
$kolvo_za = 200;
$star_za = 'star9';
$nagrada_za = '<img src="/style/images/many/gold.png" alt=""/>550 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_zashita'] == 10){
$star_za = 'star10';
}

if($user['ach_zashita'] != 10){
echo'<li><a href="/ach_level_zashita_act?act=get">';
echo'<img src="/style/images/user/zashita.png" alt=""/><span style="font-size:15px;"><b>Защищенный</b> '.($user['level_zashita'] >= $kolvo_za ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Прокачай требуемый уровень Защиты</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Уровень: <span class="gray"><img src="/style/images/user/level.png" alt=""/>'.$user['level_zashita'].' из <img src="/style/images/user/level.png" alt=""/>'.$kolvo_za.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_za.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=3"><div style="opacity: .5;">';
echo'<img src="/style/images/user/zashita.png" alt=""/><span style="font-size:15px;"><b>Защищенный</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Прокачай требуемый уровень Защиты</span><br/>';
echo'</div></a></li>';
}
                                                                    #-ЖИВУЧИЙ-#
echo'<div class="line_1"></div>';	
#-Первое достижение-#
if($user['ach_health'] == 0){
$kolvo_h = 20;
$star_h = 'star0';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>125 и <img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
}
#-Второе достижение-#
if($user['ach_health'] == 1){
$kolvo_h = 40;
$star_h = 'star1';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>200';
}
#-Третье достижение-#
if($user['ach_health'] == 2){
$kolvo_h = 60;
$star_h = 'star2';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>275';
}
#-Четвертое достижение-#
if($user['ach_health'] == 3){
$kolvo_h = 80;
$star_h = 'star3';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>350';
}
#-Пятое достижение-#
if($user['ach_health'] == 4){
$kolvo_h = 100;
$star_h = 'star4';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>425';
}
#-Шестое достижение-#
if($user['ach_health'] == 5){
$kolvo_h = 120;
$star_h = 'star5';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>500 и <img src="/style/images/body/chest.png" alt=""/>Древний сундук';
}
#-Седьмое достижениее-#
if($user['ach_health'] == 6){
$kolvo_h = 140;
$star_h = 'star6';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>575';
}
#-Восьмое достижение-#
if($user['ach_health'] == 7){
$kolvo_h = 160;
$star_h = 'star7';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>650';
}
#-Девятое достижение-#
if($user['ach_health'] == 8){
$kolvo_h = 180;
$star_h = 'star8';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>725';
}
#-Десятое достижение-#
if($user['ach_health'] == 9){
$kolvo_h = 200;
$star_h = 'star9';
$nagrada_h = '<img src="/style/images/many/gold.png" alt=""/>800 и <img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
}
if($user['ach_health'] == 10){
$star_h = 'star10';
}

if($user['ach_health'] != 10){
echo'<li><a href="/ach_level_health_act?act=get">';
echo'<img src="/style/images/user/health.png" alt=""/><span style="font-size:15px;"><b>Живучий</b> '.($user['level_health'] >= $kolvo_h ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Прокачай требуемый уровень Здоровья</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Уровень: <span class="gray"><img src="/style/images/user/level.png" alt=""/>'.$user['level_health'].' из <img src="/style/images/user/level.png" alt=""/>'.$kolvo_h.'</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray">'.$nagrada_h.'</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=3"><div style="opacity: .5;">';
echo'<img src="/style/images/user/health.png" alt=""/><span style="font-size:15px;"><b>Живучий</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Прокачай требуемый уровень Здоровья</span><br/>';
echo'</div></a></li>';
}
                                                                  #-ГЕРОЙ III РАНГА-#
echo'<div class="line_1"></div>';	
if($user['ach_heros_3'] == 0){
echo'<li><a href="/ach_heros3_act?act=get">';
echo'<img src="/style/images/quality/3.png" alt=""/><span style="font-size:15px;"><b>Герой III Ранга</b> '.($user['ach_heros_3'] == 1 ? "<span class='green'>(Выполнено)</span>" : "<span class='red'>(Не выполнено)</span>").'</span><br/>';
echo'<span class="gray">Выполни все достижения I, II и III рангов<br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>4\'000 и <img src="/style/images/body/traing.png" alt=""/>Комплект Охотника</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/achive?type=3"><div style="opacity: .5;">';
echo'<img src="/style/images/quality/3.png" alt=""/><span style="font-size:15px;"><b>Герой III Ранга</b> <span class="green">(Выполнено)</span></span><br/>';
echo'<span class="gray">Выполни все достижения I, II и III рангов<br/>';
echo'</div></a></li>';
}
}

echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>