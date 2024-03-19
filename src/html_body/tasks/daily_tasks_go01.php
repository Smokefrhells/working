<?php
require_once '../../system/system.php';
$head = 'Ежедневные задания';
echo only_reg();
require_once H.'system/head.php';
#-Ежедневные задания-#
//Тип 1 = Охота
//Тип 2 = Дуэли оффлайн
//Тип 3 = Дуэли онлайн
//Тип 4 = Боссы

echo'<img src="/style/images/location/tasks.jpg" class="img"/>';
echo'<div class="menulist">';
echo'<div class="body_list">';
#-Количество заданий-#
$sel_tasks_1 = $pdo->prepare("SELECT COUNT(*) FROM `daily_tasks` WHERE `user_id` = :user_id");
$sel_tasks_1->execute(array(':user_id' => $user['id']));
$tasks_1 = $sel_tasks_1->fetch(PDO::FETCH_LAZY);
#-Количество боссов которые доступны-#
$sel_boss = $pdo->prepare("SELECT COUNT(*) FROM `boss` WHERE `level` <= :user_level");
$sel_boss->execute(array(':user_level' => $user['level']));
$amount = $sel_boss->fetch(PDO::FETCH_LAZY);
$tasks_q = $amount[0]+3;
if($tasks_1[0] != $tasks_q){
echo'<li><a href="/tasks_all?act=take"><img src="/style/images/body/ok.png" alt=""/> Взять все задания</a></li>';
echo'<div class="line_1"></div>';
}


for($i=0; $i < 22;){
$i = $i+1;
#-Охота-#
if($i == 1){
$sel_tasks_h = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 1");
$sel_tasks_h->execute(array(':user_id' => $user['id']));
if($sel_tasks_h -> rowCount() != 0){
$tasks_h = $sel_tasks_h->fetch(PDO::FETCH_LAZY);
$quatity_hunting = $tasks_h['quatity'];
}else{
$quatity_hunting = 0;
}
}

#-Оффлайн Дуэли-#
if($i == 2 and $user['level'] >= 5){
$sel_duel_offline = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 2");
$sel_duel_offline->execute(array(':user_id' => $user['id']));
if($sel_duel_offline -> rowCount() != 0){
$duel_offline = $sel_duel_offline->fetch(PDO::FETCH_LAZY);
$quatity_duel_offline = $duel_offline['quatity'];
}else{
$quatity_duel_offline = 0;
}
}

#-Онлайн Дуэли-#
if($i == 3 and $user['level'] >= 5){
$sel_duel_online = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 3");
$sel_duel_online->execute(array(':user_id' => $user['id']));
if($sel_duel_online -> rowCount() != 0){
$duel_online = $sel_duel_online->fetch(PDO::FETCH_LAZY);
$quatity_duel_online = $duel_online['quatity'];
}else{
$quatity_duel_online = 0;
}
}

#-Боссы-#
#-Огненная змея-#
if($i == 4 and $user['level'] >= 5){
$sel_zmeya = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 1");
$sel_zmeya->execute(array(':user_id' => $user['id']));
if($sel_zmeya -> rowCount() != 0){
$zmeya = $sel_zmeya->fetch(PDO::FETCH_LAZY);
$quatity_zmeya = $zmeya['quatity'];
}else{
$quatity_zmeya = 0;
}
}
#-Великан-#
if($i == 5 and $user['level'] >= 8){
$sel_velekan = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 2");
$sel_velekan->execute(array(':user_id' => $user['id']));
if($sel_velekan -> rowCount() != 0){
$velekan = $sel_velekan->fetch(PDO::FETCH_LAZY);
$quatity_velekan = $velekan['quatity'];
}else{
$quatity_velekan = 0;
}
}
#-Знахарь-#
if($i == 6 and $user['level'] >= 15){
$sel_znahar = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 3");
$sel_znahar->execute(array(':user_id' => $user['id']));
if($sel_znahar -> rowCount() != 0){
$znahar = $sel_znahar->fetch(PDO::FETCH_LAZY);
$quatity_znahar = $znahar['quatity'];
}else{
$quatity_znahar = 0;
}
}
#-Индеец-#
if($i == 7 and $user['level'] >= 20){
$sel_indees = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 4");
$sel_indees->execute(array(':user_id' => $user['id']));
if($sel_indees -> rowCount() != 0){
$indees = $sel_indees->fetch(PDO::FETCH_LAZY);
$quatity_indees = $indees['quatity'];
}else{
$quatity_indees = 0;
}
}
#-Маг-#
if($i == 8 and $user['level'] >= 25){
$sel_mag = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 5");
$sel_mag->execute(array(':user_id' => $user['id']));
if($sel_mag -> rowCount() != 0){
$mag = $sel_mag->fetch(PDO::FETCH_LAZY);
$quatity_mag = $mag['quatity'];
}else{
$quatity_mag = 0;
}
}
#-Гоблин-#
if($i == 9 and $user['level'] >= 30){
$sel_goblin = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 6");
$sel_goblin->execute(array(':user_id' => $user['id']));
if($sel_goblin -> rowCount() != 0){
$goblin = $sel_goblin->fetch(PDO::FETCH_LAZY);
$quatity_goblin = $goblin['quatity'];
}else{
$quatity_goblin = 0;
}
}
#-Бык Убийца-#
if($i == 10 and $user['level'] >= 35){
$sel_buk = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 7");
$sel_buk->execute(array(':user_id' => $user['id']));
if($sel_buk -> rowCount() != 0){
$buk = $sel_buk->fetch(PDO::FETCH_LAZY);
$quatity_buk = $buk['quatity'];
}else{
$quatity_buk = 0;
}
}
#-Асторем-#
if($i == 11 and $user['level'] >= 40){
$sel_astorem = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 8");
$sel_astorem->execute(array(':user_id' => $user['id']));
if($sel_astorem -> rowCount() != 0){
$astorem = $sel_astorem->fetch(PDO::FETCH_LAZY);
$quatity_astorem = $astorem['quatity'];
}else{
$quatity_astorem = 0;
}
}
#-Гренлейд-#
if($i == 12 and $user['level'] >= 45){
$sel_grenleid = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 9");
$sel_grenleid->execute(array(':user_id' => $user['id']));
if($sel_grenleid -> rowCount() != 0){
$grenleid = $sel_grenleid->fetch(PDO::FETCH_LAZY);
$quatity_grenleid = $grenleid['quatity'];
}else{
$quatity_grenleid = 0;
}
}
#-Нифлена-#
if($i == 13 and $user['level'] >= 50){
$sel_niflena = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 10");
$sel_niflena->execute(array(':user_id' => $user['id']));
if($sel_niflena -> rowCount() != 0){
$niflena = $sel_niflena->fetch(PDO::FETCH_LAZY);
$quatity_niflena = $niflena['quatity'];
}else{
$quatity_niflena = 0;
}
}
#-Разрушитель-#
if($i == 14 and $user['level'] >= 60){
$sel_razruh = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 11");
$sel_razruh->execute(array(':user_id' => $user['id']));
if($sel_razruh -> rowCount() != 0){
$razruh = $sel_razruh->fetch(PDO::FETCH_LAZY);
$quatity_razruh = $razruh['quatity'];
}else{
$quatity_razruh = 0;
}
}
#-Приспешник-#
if($i == 15 and $user['level'] >= 70){
$sel_prispeh = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 12");
$sel_prispeh->execute(array(':user_id' => $user['id']));
if($sel_prispeh -> rowCount() != 0){
$prispeh = $sel_prispeh->fetch(PDO::FETCH_LAZY);
$quatity_prispeh = $prispeh['quatity'];
}else{
$quatity_prispeh = 0;
}
}
#-Панда-#
if($i == 16 and $user['level'] >= 75){
$sel_panda = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 13");
$sel_panda->execute(array(':user_id' => $user['id']));
if($sel_panda -> rowCount() != 0){
$panda = $sel_panda->fetch(PDO::FETCH_LAZY);
$quatity_panda = $panda['quatity'];
}else{
$quatity_panda = 0;
}
}
#-Варнур-#
if($i == 17 and $user['level'] >= 80){
$sel_varnur = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 14");
$sel_varnur->execute(array(':user_id' => $user['id']));
if($sel_varnur -> rowCount() != 0){
$varnur = $sel_varnur->fetch(PDO::FETCH_LAZY);
$quatity_varnur = $varnur['quatity'];
}else{
$quatity_varnur = 0;
}
}
#-Блуждающий-#
if($i == 18 and $user['level'] >= 85){
$sel_blusda = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 15");
$sel_blusda->execute(array(':user_id' => $user['id']));
if($sel_blusda -> rowCount() != 0){
$blusda = $sel_blusda->fetch(PDO::FETCH_LAZY);
$quatity_blusda = $blusda['quatity'];
}else{
$quatity_blusda = 0;
}
}
#-Гнол-#
if($i == 19 and $user['level'] >= 90){
$sel_gnol = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 16");
$sel_gnol->execute(array(':user_id' => $user['id']));
if($sel_gnol -> rowCount() != 0){
$gnol = $sel_gnol->fetch(PDO::FETCH_LAZY);
$quatity_gnol = $gnol['quatity'];
}else{
$quatity_gnol = 0;
}
}
#-Жаба-#
if($i == 20 and $user['level'] >= 95){
$sel_shaba = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 17");
$sel_shaba->execute(array(':user_id' => $user['id']));
if($sel_shaba -> rowCount() != 0){
$shaba = $sel_shaba->fetch(PDO::FETCH_LAZY);
$quatity_shaba = $shaba['quatity'];
}else{
$quatity_shaba = 0;
}
}
#-Орвун-#
if($i == 21 and $user['level'] >= 100){
$sel_orvun = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = 18");
$sel_orvun->execute(array(':user_id' => $user['id']));
if($sel_orvun -> rowCount() != 0){
$orvun = $sel_orvun->fetch(PDO::FETCH_LAZY);
$quatity_orvun = $orvun['quatity'];
}else{
$quatity_orvun = 0;
}
}
}

                                                                  #-ОХОТА-#
if($sel_tasks_h-> rowCount() == 0){
echo'<li><a href="/tasks_hunting?act=take">';
echo'<img src="/style/images/body/ohota.png" alt=""/><span style="font-size:15px;"><b>Охота</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать 10 побед: <span class="gray">'.$quatity_hunting.' из 10</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>3 <img src="/style/images/many/silver.png" alt=""/>5\'000 <img src="/style/images/user/exp.png" alt=""/>3\'500</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/ohota.png" alt=""/><span style="font-size:15px;"><b>Охота</b> '.($tasks_h['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать 10 побед: <span class="gray">'.$quatity_hunting.' из 10</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>3 <img src="/style/images/many/silver.png" alt=""/>5\'000 <img src="/style/images/user/exp.png" alt=""/>3\'500</span><br/>';
echo'</div></a></li>';
}

                                                               #-ОФФЛАЙН ДУЭЛИ-#
if($user['level'] >= 5){
echo'<div class="line_1"></div>';	
if($sel_duel_offline-> rowCount() == 0){
echo'<li><a href="/tasks_offline_duel?act=take">';
echo'<img src="/style/images/body/league.png" alt=""/><span style="font-size:15px;"><b>Оффлайн дуэли</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать 10 побед: <span class="gray">'.$quatity_duel_offline.' из 10</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>5 <img src="/style/images/many/silver.png" alt=""/>8\'000 <img src="/style/images/user/exp.png" alt=""/>4\'500</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/league.png" alt=""/><span style="font-size:15px;"><b>Оффлайн дуэли</b> '.($duel_offline['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать 10 побед: <span class="gray">'.$quatity_duel_offline.' из 10</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>5 <img src="/style/images/many/silver.png" alt=""/>8\'000 <img src="/style/images/user/exp.png" alt=""/>4\'500</span><br/>';
echo'</div></a></li>';
}
}

                                                                #-ОНЛАЙН ДУЭЛИ-#
if($user['level'] >= 5){
echo'<div class="line_1"></div>';	
if($sel_duel_online-> rowCount() == 0){
echo'<li><a href="/tasks_online_duel?act=take">';
echo'<img src="/style/images/body/league.png" alt=""/><span style="font-size:15px;"><b>Онлайн дуэли</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать 10 побед: <span class="gray">'.$quatity_duel_online.' из 10</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>10 <img src="/style/images/many/silver.png" alt=""/>12\'000 <img src="/style/images/user/exp.png" alt=""/>6\'500</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/league.png" alt=""/><span style="font-size:15px;"><b>Онлайн дуэли</b> '.($duel_online['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать 10 побед: <span class="gray">'.$quatity_duel_online.' из 10</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>10 <img src="/style/images/many/silver.png" alt=""/>12\'000 <img src="/style/images/user/exp.png" alt=""/>6\'500</span><br/>';
echo'</div></a></li>';
}
}

                                                                #-ОГНЕННАЯ ЗМЕЯ-#
if($user['level'] >= 5){	
echo'<div class="line_1"></div>';										  
if($sel_zmeya-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=1">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Огненная змея</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_zmeya.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>2 <img src="/style/images/many/silver.png" alt=""/>2\'800 <img src="/style/images/user/exp.png" alt=""/>3\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Огненная змея</b> '.($zmeya['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_zmeya.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>2 <img src="/style/images/many/silver.png" alt=""/>2\'800 <img src="/style/images/user/exp.png" alt=""/>3\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                #-Великан-#
if($user['level'] >= 8){	
echo'<div class="line_1"></div>';										  
if($sel_velekan-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=2">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Великан</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_zmeya.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>3 <img src="/style/images/many/silver.png" alt=""/>5\'600 <img src="/style/images/user/exp.png" alt=""/>6\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Великан</b> '.($velekan['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_velekan.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>3 <img src="/style/images/many/silver.png" alt=""/>5\'600 <img src="/style/images/user/exp.png" alt=""/>6\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                 #-ЗНАХАРЬ-#
if($user['level'] >= 15){
echo'<div class="line_1"></div>';	
if($sel_znahar-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=3">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Знахарь</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_znahar.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>4 <img src="/style/images/many/silver.png" alt=""/>8\'400 <img src="/style/images/user/exp.png" alt=""/>9\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Знахарь</b> '.($znahar['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_znahar.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>4 <img src="/style/images/many/silver.png" alt=""/>8\'400 <img src="/style/images/user/exp.png" alt=""/>9\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                 #-ИНДЕЕЦ-#
if($user['level'] >= 20){
echo'<div class="line_1"></div>';	
if($sel_indees-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=4">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Индеец</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_indees.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>5 <img src="/style/images/many/silver.png" alt=""/>11\'200 <img src="/style/images/user/exp.png" alt=""/>12\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Индеец</b> '.($indees['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_indees.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>5 <img src="/style/images/many/silver.png" alt=""/>11\'200 <img src="/style/images/user/exp.png" alt=""/>12\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                  #-МАГ-#
if($user['level'] >= 25){
echo'<div class="line_1"></div>';	
if($sel_mag-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=5">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Маг</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_mag.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>6 <img src="/style/images/many/silver.png" alt=""/>14\'000 <img src="/style/images/user/exp.png" alt=""/>15\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Маг</b> '.($mag['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_mag.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>6 <img src="/style/images/many/silver.png" alt=""/>14\'000 <img src="/style/images/user/exp.png" alt=""/>15\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                  #-ГОБЛИН-#
if($user['level'] >= 30){
echo'<div class="line_1"></div>';	
if($sel_goblin-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=6">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Гоблин</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_goblin.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>7 <img src="/style/images/many/silver.png" alt=""/>16\'800 <img src="/style/images/user/exp.png" alt=""/>18\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Гоблин</b> '.($goblin['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_goblin.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>7 <img src="/style/images/many/silver.png" alt=""/>16\'800 <img src="/style/images/user/exp.png" alt=""/>18\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                #-БЫК УБИЙЦА-#
if($user['level'] >= 35){
echo'<div class="line_1"></div>';	
if($sel_buk-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=7">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Бык Убийца</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_buk.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>8 <img src="/style/images/many/silver.png" alt=""/>19\'600 <img src="/style/images/user/exp.png" alt=""/>21\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Бык Убийца</b> '.($buk['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_buk.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>8 <img src="/style/images/many/silver.png" alt=""/>19\'600 <img src="/style/images/user/exp.png" alt=""/>21\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                  #-АСТОРЕМ-#
if($user['level'] >= 40){
echo'<div class="line_1"></div>';	
if($sel_astorem-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=8">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Асторем</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_astorem.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>9 <img src="/style/images/many/silver.png" alt=""/>22\'400 <img src="/style/images/user/exp.png" alt=""/>24\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Асторем</b> '.($astorem['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_astorem.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>9 <img src="/style/images/many/silver.png" alt=""/>22\'400 <img src="/style/images/user/exp.png" alt=""/>24\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                  #-ГРЕНЛЕЙД-#
if($user['level'] >= 45){
echo'<div class="line_1"></div>';	
if($sel_grenleid-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=9">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Гренлейд</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_grenleid.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>10 <img src="/style/images/many/silver.png" alt=""/>25\'200 <img src="/style/images/user/exp.png" alt=""/>27\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Гренлейд</b> '.($grenleid['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_grenleid.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>10 <img src="/style/images/many/silver.png" alt=""/>25\'200 <img src="/style/images/user/exp.png" alt=""/>27\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                    #-НИФЛЕНА-#
if($user['level'] >= 50){
echo'<div class="line_1"></div>';	
if($sel_niflena-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=10">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Нифлена</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_niflena.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>11 <img src="/style/images/many/silver.png" alt=""/>28\'000 <img src="/style/images/user/exp.png" alt=""/>30\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Нифлена</b> '.($niflena['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_niflena.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>11 <img src="/style/images/many/silver.png" alt=""/>28\'000 <img src="/style/images/user/exp.png" alt=""/>30\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                   #-РАЗРУШИТЕЛЬ-#
if($user['level'] >= 60){
echo'<div class="line_1"></div>';	
if($sel_razruh-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=11">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Разрушитель</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_razruh.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>12 <img src="/style/images/many/silver.png" alt=""/>30\'800 <img src="/style/images/user/exp.png" alt=""/>33\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Разрушитель</b> '.($razruh['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_razruh.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>12 <img src="/style/images/many/silver.png" alt=""/>30\'800 <img src="/style/images/user/exp.png" alt=""/>33\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                    #-ПРИСПЕШНИК-#
if($user['level'] >= 70){
echo'<div class="line_1"></div>';	
if($sel_prispeh-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=12">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Приспешник</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_prispeh.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>13 <img src="/style/images/many/silver.png" alt=""/>33\'000 <img src="/style/images/user/exp.png" alt=""/>36\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Приспешник</b> '.($prispeh['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_prispeh.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>13 <img src="/style/images/many/silver.png" alt=""/>33\'000 <img src="/style/images/user/exp.png" alt=""/>36\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                  #-ОГНЕННАЯ ПАНДА-#
if($user['level'] >= 75){
echo'<div class="line_1"></div>';	
if($sel_panda-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=13">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Огненная панда</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_panda.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>14 <img src="/style/images/many/silver.png" alt=""/>36\'400 <img src="/style/images/user/exp.png" alt=""/>39\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Огненная панда</b> '.($panda['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_panda.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>14 <img src="/style/images/many/silver.png" alt=""/>36\'400 <img src="/style/images/user/exp.png" alt=""/>39\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                       #-ВАРНУР-#
if($user['level'] >= 80){
echo'<div class="line_1"></div>';	
if($sel_varnur-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=14">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Варнур</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_varnur.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>15 <img src="/style/images/many/silver.png" alt=""/>39\'200 <img src="/style/images/user/exp.png" alt=""/>42\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Варнур</b> '.($varnur['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_varnur.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>15 <img src="/style/images/many/silver.png" alt=""/>39\'200 <img src="/style/images/user/exp.png" alt=""/>42\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                      #-БЛУЖДАЮЩИЙ-#
if($user['level'] >= 85){	
echo'<div class="line_1"></div>';										  
if($sel_blusda-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=15">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Блуждающий</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_blusda.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>16 <img src="/style/images/many/silver.png" alt=""/>42\'000 <img src="/style/images/user/exp.png" alt=""/>45\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Блуждающий</b> '.($blusda['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_blusda.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>16 <img src="/style/images/many/silver.png" alt=""/>42\'000 <img src="/style/images/user/exp.png" alt=""/>45\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                        #-ГНОЛ-#
if($user['level'] >= 90){	
echo'<div class="line_1"></div>';										  
if($sel_gnol-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=16">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Гнол</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_gnol.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>17 <img src="/style/images/many/silver.png" alt=""/>44\'800 <img src="/style/images/user/exp.png" alt=""/>48\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Гнол</b> '.($gnol['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_gnol.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>17 <img src="/style/images/many/silver.png" alt=""/>44\'800 <img src="/style/images/user/exp.png" alt=""/>48\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                        #-ЖАБА-#
if($user['level'] >= 95){	
echo'<div class="line_1"></div>';										  
if($sel_shaba-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=17">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Жаба</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_shaba.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>18 <img src="/style/images/many/silver.png" alt=""/>47\'600 <img src="/style/images/user/exp.png" alt=""/>51\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Жаба</b> '.($shaba['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_shaba.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>18 <img src="/style/images/many/silver.png" alt=""/>47\'600 <img src="/style/images/user/exp.png" alt=""/>51\'000</span><br/>';
echo'</div></a></li>';
}
}
                                                                        #-ОРВУН-#
if($user['level'] >= 100){
echo'<div class="line_1"></div>';	
if($sel_orvun-> rowCount() == 0){
echo'<li><a href="/tasks_boss?act=take&type_id=18">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Орвун</b></span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_orvun.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>19 <img src="/style/images/many/silver.png" alt=""/>50\'400 <img src="/style/images/user/exp.png" alt=""/>54\'000</span><br/>';
echo'</a></li>';
}else{
echo'<li><a href="/daily_tasks"><div style="opacity: .5;">';
echo'<img src="/style/images/body/bos.png" alt=""/><span style="font-size:15px;"><b>Орвун</b> '.($orvun['statys'] == 0 ? "<span class='red'>(Не выполнено)</span>" : "<span class='green'>(Выполнено)</span>").'</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Одержать победу: <span class="gray">'.$quatity_orvun.' из 1</span><br/>';
echo'<img src="/style/images/body/gift.png" alt=""/>Награда: <span class="gray"><img src="/style/images/many/gold.png" alt=""/>19 <img src="/style/images/many/silver.png" alt=""/>50\'400 <img src="/style/images/user/exp.png" alt=""/>54\'000</span><br/>';
echo'</div></a></li>';
}
}
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>