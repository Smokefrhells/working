<?php
echo'<div class="body_list">';
echo'<div class="menulist">';
                                                                     /*ИГРОВЫЕ ЛОКАЦИИ*/

#-Подарок-#
echo'<li><a href="/knowledge_basa?sort=location&spr=gift"><img src="/style/images/body/gift.png" alt=""/> Подарок</a></li>';
if($_GET['spr'] == 'gift'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/gift.png" alt=""/><span class="yellow">Подарок</span> - в честь праздников или других событий Администрация игры оставляет подарки игрокам в виде <img src="/style/images/many/gold.png" alt=""/><span class="gray">Золота</span>, <img src="/style/images/many/silver.png" alt=""/><span class="gray">Серебра</span>, <img src="/style/images/body/key.png" alt=""/><span class="gray">Ключей</span> и <img src="/style/images/many/crystal.png" alt=""/><span class="gray">Кристаллов</span>.<br/>';
echo'Не стоит путать <span class="gray">Подарки</span> с <span class="gray">Ежедневным бонусом</span>.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/><span style="font-size:14px; color:#dc50ff;"> Не появляються автоматически</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Охота и захват-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=hunting"><img src="/style/images/body/ohota.png" alt=""/> Охота и Захват</a></li>';	
if($_GET['spr'] == 'hunting'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
#-Охота-#
echo'<img src="/style/images/body/ohota.png" alt=""/><span class="yellow">Охота</span> - локация с различными монстрами за победу над которыми можно получить <img src="/style/images/user/exp.png" alt=""/><span class="gray">Опыт</span> и <img src="/style/images/many/silver.png"/><span class="gray">Серебро</span>. Всего насчитывается <span class="gray">56 монстров</span> во всех локациях.<br/>'; 
echo'Монстры бывают трех видов:<br/>';
echo'<img src="/style/images/body/ok.png" alt=""/><span class="green">Обычные (75%)</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/><span class="green">Редкие (15%)</span><br/>';
echo'<img src="/style/images/body/ok.png" alt=""/><span class="green">Легендарные (10%)</span><br/>';
echo'Вам доступно 7 локаций, которые зависят от вашего <img src="/style/images/user/level.png" alt=""/>Уровня.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Тёмный лес - <img src="/style/images/user/level.png" alt=""/>1 ур.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Заброшенный замок - <img src="/style/images/user/level.png" alt=""/>20 ур.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Пещера - <img src="/style/images/user/level.png" alt=""/>35 ур.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Пустыня - <img src="/style/images/user/level.png" alt=""/>50 ур.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Катакомбы - <img src="/style/images/user/level.png" alt=""/>65 ур.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Руины - <img src="/style/images/user/level.png" alt=""/>80 ур.<br/>';
echo'<img src="/style/images/body/ohota.png" alt=""/>Преисподняя - <img src="/style/images/user/level.png" alt=""/>95 ур.<br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Кол-во боёв одинаковое на всех <img src="/style/images/user/level.png" alt=""/>Уровнях</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Награда не зависит от <img src="/style/images/user/level.png" alt=""/>Уровня</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">При покидании боя вам засчитываеться поражение</span><br/>';
#-Захват локаций-#
echo'<div style="padding-top: 10px;"></div>';
echo'<img src="/style/images/body/ohota.png" alt=""/><span class="yellow">Захват локаций</span> - в охоте вы можете захватить одну из 7 локаций которая подходит по вашему <img src="/style/images/user/level.png" alt=""/>Уровню.<br/>';
echo'Эта локация будет давать бонус к вашим <img src="/style/images/body/traing.png" alt=""/><span class="gray">Параметрам</span>, а также при охоте в этой локации вы будете получать в 2 раза больше <img src="/style/images/many/crystal.png" alt=""/><span class="gray">Кристаллов</span>.<br/>';

echo'<div style="padding-top: 5px;"></div>';
echo'Если локация не захвачена вы можете выкупить её за <img src="/style/images/many/gold.png" alt=""/><span class="gray">Золото</span>.<br/>';
echo'<span class="gray">';
echo'<img src="/style/images/body/ohota.png"> Тёмный лес - <img src="/style/images/many/gold.png"/>40<br/>';
echo'<img src="/style/images/body/ohota.png"> Заброшенный замок - <img src="/style/images/many/gold.png"/>80<br/>';
echo'<img src="/style/images/body/ohota.png"> Пещера - <img src="/style/images/many/gold.png"/>120<br/>';
echo'<img src="/style/images/body/ohota.png"> Пустыня - <img src="/style/images/many/gold.png"/>160<br/>';
echo'<img src="/style/images/body/ohota.png"> Катакомбы - <img src="/style/images/many/gold.png"/>200<br/>';
echo'<img src="/style/images/body/ohota.png"> Руины - <img src="/style/images/many/gold.png"/>240<br/>';
echo'<img src="/style/images/body/ohota.png"> Преисподняя - <img src="/style/images/many/gold.png"/>280<br/>';
echo'</span>';

echo'<div style="padding-top: 5px;"></div>';
echo'Уровни захвата и бонусы к параметрам:<br/>';
echo'<span class="gray">';
echo'<img src="/style/images/body/ohota.png"> Тёмный лес - <img src="/style/images/user/level.png"/>1-15 ур. [<img src="/style/images/body/traing.png">2\'000]<br/>';
echo'<img src="/style/images/body/ohota.png"> Заброшенный замок - <img src="/style/images/user/level.png"/>20-34 ур. [<img src="/style/images/body/traing.png">4\'000]<br/>';
echo'<img src="/style/images/body/ohota.png"> Пещера - <img src="/style/images/user/level.png"/>35-49 ур. [<img src="/style/images/body/traing.png">6\'000]<br/>';
echo'<img src="/style/images/body/ohota.png"> Пустыня - <img src="/style/images/user/level.png"/>50-64 ур. [<img src="/style/images/body/traing.png">8\'000]<br/>';
echo'<img src="/style/images/body/ohota.png"> Катакомбы - <img src="/style/images/user/level.png"/>65-79 ур. [<img src="/style/images/body/traing.png">10\'000] <br/>';
echo'<img src="/style/images/body/ohota.png"> Руины - <img src="/style/images/user/level.png"/>80-94 ур. [<img src="/style/images/body/traing.png">12\'000]<br/>';
echo'<img src="/style/images/body/ohota.png"> Преисподняя - <img src="/style/images/user/level.png"/>95-100 ур. [<img src="/style/images/body/traing.png">14\'000]<br/>';
echo'</span>';
echo'Если вы владеете локацией и в сражении заново её захватили, то получите <span class="gray">один из трех сундуков</span>.<br/>';
echo'Если вы владеете локацией и время вышло но участников нет, то вы автоматически заново захвачиваете её.</br>';

echo'<div style="padding-top: 10px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Количество участников в сражении не ограничено</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Локация автоматически освобождаеться если вы получили <img src="/style/images/user/level.png"/>Уровень больше чем предельно допустимый для этой локации</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Владеть вы можете только одной локацией</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Дуэли-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=duel"><img src="/style/images/body/league.png" alt=""/> Дуэли</a></li>';	
if($_GET['spr'] == 'duel'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/league.png" alt=""/><span class="yellow">Дуэли</span> - локация в которой вы сможете сразиться с другими игроками как в оффлайн режиме так и онлайн.<br/>';
echo'В <span class="gray">онлайн режиме</span> награда за победу будет больше чем в <span class="gray">оффлайн</span>.<br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Кол-во боёв зависит от вашего <img src="/style/images/user/level.png" alt=""/>Уровня, через каждые 2 уровня +1 бой</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Система подбирает игроков с разницей в уровнях +-5, нет ограничений если начать бой из страницы игрока</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Цена ускорения времени зависит также от уровня героя</span><br/>';
echo'</span>';
echo'</span>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Боссы-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=boss"><img src="/style/images/body/bos.png" alt=""> Боссы</a></li>';	
if($_GET['spr'] == 'boss'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/bos.png" alt=""><span class="yellow">Боссы</span> - локация где вы сможете сразиться с Боссами.<br/>'; 
echo'Если вы не справляетесь с боссом, то сможете позвать на помощь своих друзей.<br/>';
echo'В этой локации у вас есть хорошая возможность заработать <img src="/style/images/many/gold.png" alt=""><span class="gray">Золото</span> и <img src="/style/images/body/chest.png" alt=""><span class="gray">Сундуки</span>.<br/>';

echo'<img src="/style/images/body/chest.png" alt=""/>Шансы выпадения сундуков:<br/>';
echo'<span class="green">';
echo'<img src="/style/images/body/ok.png" alt=""/>Обычный сундук - 40%<br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Древний сундук - 10%<br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Золотой сундук - 5%<br/>';
echo'</span>';

echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальное кол-во участников: 5</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Цена ускорения времени зависит от Босса</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Воскрешение доступно только при 2 и больше участников</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Рейд-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=reid"><img src="/style/images/body/reid.png" alt=""/> Рейд</a></li>';	
if($_GET['spr'] == 'reid'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/reid.png" alt=""/><span class="yellow">Рейд</span> - сражение в котором вы можете сражаться с мировыми боссами.<br/>';
echo'Всего доступно 5 боссов:<br/>';
echo'<img src="/style/images/monstru/reid/1.jpg" alt=""/>';
echo'<img src="/style/images/monstru/reid/2.jpg" alt=""/>';
echo'<img src="/style/images/monstru/reid/3.jpg" alt=""/>';
echo'<img src="/style/images/monstru/reid/4.jpg" alt=""/>';
echo'<img src="/style/images/monstru/reid/5.jpg" alt=""/><br/>';
echo'Боссы выбираються рандомно с шансом: 50%, 20%, 15%, 10%, 5%.<br/>';
echo'Сражение начинается через <img src="/style/images/body/time.png" alt=""/>6 часов (Если участников нет, то время начинается заново). На сражение выделено <img src="/style/images/body/time.png" alt=""/>2 часа.';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Доступно с <img src="/style/images/user/level.png" alt=""/>20 ур.</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимально кол-во воскрешений: 3</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Можно выбить Амулет либо Кольцо</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Если босс не был убит, то все заработаное серебро аннулируется</span><br/>';
echo'</span>';
echo'</span>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Замки-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=zamki"><img src="/style/images/body/zamki.png" alt=""/> Замки</a></li>';	
if($_GET['spr'] == 'zamki'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/zamki.png" alt=""/><span class="yellow">Замки</span> - сражение в котором сражаються две стороны Левые и Правые.<br/>';
echo'У каждой стороны есть свой <img src="/style/images/body/zamki.png" alt=""/><span class="gray">Замок</span>. <img src="/style/images/user/health.png" alt=""/><span class="gray">Здоровье замка</span> это общее здоровье всех игроков одной и той же стороны которые участвуют в сражении. Сражение проводится каждые 10 мин. и на сражение дается 30 мин.<br/>';
echo'<img src="/style/images/user/health.png" alt=""/><span class="yellow">Лечить</span> - можно восстановить свое кол-во здоровья замку.<br/>';
echo'<img src="/style/images/body/freezing.png" alt=""/><span class="yellow">Заморозка</span> - замораживает игрока противоположной стороны на 30 сек. На протяжении 30 сек. он не сможет атаковать замок.<br/>';
echo'<img src="/style/images/body/stena.png" alt=""/><span class="yellow">Стена</span> - дает дополнительно 25% здоровья от общего кол-ва здоровья замка.';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Доступно с <img src="/style/images/user/level.png" alt=""/>10 ур.</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Всё <img src="/style/images/many/silver.png" alt=""/>Серебро проигравшей стороны распределяеться между игроками которые победили</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Награда зависит от количества урона нанесенного замку врага</span><br/>';
echo'</span>';
echo'</span>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Колизей-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=coliseum"><img src="/style/images/body/coliseum.png" alt=""/> Колизей</a></li>';	
if($_GET['spr'] == 'coliseum'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/coliseum.png" alt=""/><span class="yellow">Колизей</span> - локация в которой вы сможете сразиться с другими игроками.<br/>';
echo'Подбор <img src="/style/images/user/user.png" alt=""/>Игроков идет по уровням:<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>13-20<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>20-40<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>40-60<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>60-80<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>80-100<br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальное кол-во игроков которые могут участвувать в сражении <img src="/style/images/user/user.png" alt=""/>5</span><br/>';
echo'</span>';
echo'</span>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Башни-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=towers"><img src="/style/images/body/towers.png" alt=""/> Башни</a></li>';	
if($_GET['spr'] == 'towers'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/towers.png" alt=""/><span class="yellow">Башни</span> - локация в которой вы сможете сражаться с другими игроками за игровую валюту. Доступна локация с <img src="/style/images/user/level.png" alt=""/>25 ур.<br/>';
echo'Сражение проходит в формате 2x2 и группа которая победит получит награду в два раза больше чем вносили.<br/>';
echo'Подбор врагов идет по уровню:<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>25-40<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>40-60<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>60-80<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>80-100<br/>';
echo'Подбор взносов идет по уровню:<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>1-25 - <img src="/style/images/many/gold.png" alt=""/>25 <img src="/style/images/many/silver.png" alt=""/>5\'000<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>25-50 - <img src="/style/images/many/gold.png" alt=""/>45 <img src="/style/images/many/silver.png" alt=""/>10\'000<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>50-75 - <img src="/style/images/many/gold.png" alt=""/>65 <img src="/style/images/many/silver.png" alt=""/>15\'000<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>75-100 - <img src="/style/images/many/gold.png" alt=""/>85 <img src="/style/images/many/silver.png" alt=""/>25\'000<br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">На бой дается <img src="/style/images/body/time.png" alt=""/>10 минут</span><br/>';
echo'</span>';
echo'</span>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Дуэльный поединок-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=pets_duel"><img src="/style/images/body/pets_duel.png" alt=""/> Дуэльный поединок</a></li>';	
if($_GET['spr'] == 'pets_duel'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/pets_duel.png" alt=""/><span class="yellow">Дуэльный поединок</span> - локация где можно проходит сражение питомцев  в формате 1x1. За <img src="/style/images/body/ok.png" alt=""/><span class="green">Победу</span> вы получаете <img src="/style/images/many/gold.png" alt=""/>Золото.<br/>';
echo'Подбор оппонента зависит от параметров питомца и если не будет найден питомец с похожими параметрами, то выбран будет любой оппонент.<br/>';
echo'Кол-во золота за победу зависит от ранга:<br/>';
echo'<img src="/style/images/body/pets_rang.png" alt=""/>1 ранг - <img src="/style/images/many/gold.png" alt=""/>15<br/>';
echo'<img src="/style/images/body/pets_rang.png" alt=""/>2 ранг - <img src="/style/images/many/gold.png" alt=""/>25<br/>';
echo'<img src="/style/images/body/pets_rang.png" alt=""/>3 ранг - <img src="/style/images/many/gold.png" alt=""/>35<br/>';
echo'<img src="/style/images/body/pets_rang.png" alt=""/>4 ранг - <img src="/style/images/many/gold.png" alt=""/>45<br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Доступен только при наличии питомца</span><br/>';
echo'</span>';
echo'</span>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Торговая лавка-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=torg_lavka"><img src="/style/images/body/torg.png" alt=""/> Торговая лавка</a></li>';
if($_GET['spr'] == 'torg_lavka'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/torg.png" alt=""/><span class="yellow">Торговая лавка</span> - локация где можно купить снаряжение, зелье и другие улучшения.<br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/traing.png" alt=""/><span class="yellow">Магазин снаряжения</span> - в магазине вы сможете купить различное снаряжение которое дает бонус к вашим параметрам.<br/>';

echo'<div style="padding-top: 5px;"></div>';
echo'Существует 15 комплектов которые можно купить в торговой лавке:<br/>';
echo'<span class="green">';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Рыцаря - <img src="/style/images/user/level.png" alt=""/>3 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Защитника - <img src="/style/images/user/level.png" alt=""/>5 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Ассасина - <img src="/style/images/user/level.png" alt=""/>10 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Убийцы - <img src="/style/images/user/level.png" alt=""/>20 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/><span class="blue">Комплект Холода - <img src="/style/images/user/level.png" alt=""/>25 ур.[Праздничный]</span><br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Разрушителя - <img src="/style/images/user/level.png" alt=""/>30 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Варвара - <img src="/style/images/user/level.png" alt=""/>40 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Ополченца - <img src="/style/images/user/level.png" alt=""/>50 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Тьмы - <img src="/style/images/user/level.png" alt=""/>60 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Короля - <img src="/style/images/user/level.png" alt=""/>70 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/><span class="blue">Комплект Стражника - <img src="/style/images/user/level.png" alt=""/>70 ур.[Праздничный]</span><br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Хранителя - <img src="/style/images/user/level.png" alt=""/>80 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Правителя - <img src="/style/images/user/level.png" alt=""/>90 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/>Комплект Тёмного рыцаря - <img src="/style/images/user/level.png" alt=""/>100 ур.<br/>';
echo'<img src="/style/images/body/traing.png" alt=""/><span class="blue">Комплект Карателя - <img src="/style/images/user/level.png" alt=""/>100 ур.[Праздничный]</span><br/>';
echo'</span>';

echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/auction.png" alt=""/><span class="yellow">Аукцион</span> - место где вы можете купить снаряжение или продать своё. Аукцион доступен с <img src="/style/images/user/level.png" alt=""/>10 уровня. Продажа своего снаряжения доступна с <img src="/style/images/user/level.png" alt=""/>60 уровня. Максимальное кол-во лотов: 5.<br/>';
echo'<img src="/style/images/many/gold.png" alt=""/>Стоимость лотов:<br/>';
echo'1 лот - <img src="/style/images/many/gold.png" alt=""/>300<br/>';
echo'2 лот - <img src="/style/images/many/gold.png" alt=""/>700<br/>';
echo'3 лот - <img src="/style/images/many/gold.png" alt=""/>1400<br/>';
echo'4 лот - <img src="/style/images/many/gold.png" alt=""/>2100<br/>';
echo'5 лот - <img src="/style/images/many/gold.png" alt=""/>2800<br/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Недоступна продажа снаряжения с рунами</span><br/>';

echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/potions.png" alt=""/><span class="yellow">Зелья</span> - покупая зелья вы получаете некоторые преимущества в бою. Покупка зелья недоступна во время любого боя.<br/>';

echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/premium.png" alt=""/><span class="yellow">Премиум</span> - с премиум аккаунтом ускориться прокачка вашего героя.<br/>';
echo'Существует 2 вида премиума:<br/>';
echo'<span class="yellow">';
echo'<img src="/style/images/body/ok.png" alt=""/>Серебряный премиум<br/>';
echo'<img src="/style/images/body/ok.png" alt=""/>Золотой премиум<br/>';
echo'</span>';

echo'<div style="padding-top: 3px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">После покупки можно продливать премиум на 24 часа</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Ежедневные задания-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=daily_tasks"><img src="/style/images/body/daily_tasks.png" alt=""/> Ежедневные задания</a></li>';
if($_GET['spr'] == 'daily_tasks'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/daily_tasks.png" alt=""/><span class="yellow">Ежедневные задания</span> - выполняйте несложные задания каждый день. Для того чтобы приступить к выполнению задания нужно взять его и после этого выполнить действие которое нужно.<br/>';

echo'<div style="padding-top: 3px;"></div>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Обнуление заданий происходит в 00:00 по игровому времени</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Кузнец-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=blacksmith"><img src="/style/images/body/blacksmith.png" alt=""/> Кузнец</a></li>';
if($_GET['spr'] == 'blacksmith'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/blacksmith.png" alt=""/><span class="yellow">Кузнец</span> - локация где вы сможете улучшить своё снаряжение.<br/>';

//Заточка
echo'<div style="padding-top: 5px;"></div>';
echo'<span class="yellow">Заточка</span> - способ улучшения вещи, который дает бонус к параметрам.<br/>';
echo'Количество бонуса к параметрам зависит от <img src="/style/images/user/level.png" alt=""/><span class="gray">Уровня заточки</span> и <img src="/style/images/quality/1.png" alt=""/><span class="gray">Качества вещи</span>.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальный уровень заточки <img src="/style/images/user/level.png" alt=""/>100</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Каждый <img src="/style/images/user/level.png" alt=""/>5 ур. заточка осуществляеться за <img src="/style/images/many/gold.png" alt=""/>Золото</span><br/>';

//Руны
echo'<div style="padding-top: 5px;"></div>';
echo'<img src="/style/images/body/runa.png" alt=""/><span class="yellow">Руны</span> - значительно увеличивает параметры вещи.<br/>';
echo'Купить руну можно за <img src="/style/images/many/gold.png" alt=""/><span class="gray">Золото</span>.<br/>';
echo'Количество <img src="/style/images/many/gold.png" alt=""/><span class="gray">Золота</span> на покупку руны напрямую зависит от кол-ва бонуса к каждому параметру.<br/>';

echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Вы можете продать руну, для того что бы заново купить её на другое сгаряжение</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Турнир игроков-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=tournament_users"><img src="/style/images/body/tournament_users.png" alt=""/> Турнир игроков</a></li>';
if($_GET['spr'] == 'tournament_users'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/tournament_users.png" alt=""/><span class="yellow">Турнир игроков</span> - локация в которой можно посмотреть информацию о вашем месте в еженедельных турнирах.<br/>';
#-Статуэтки-#
echo'<img src="/style/images/user/figur.png" alt=""/><span class="yellow">Статуэтки</span> - собирайте статуэтки путем обмена <img src="/style/images/user/exp.png" alt=""/>500\'000 => <img src="/style/images/user/figur.png" alt=""/>1.<br/>';
echo'Доступен только игрокам <img src="/style/images/user/level.png" alt=""/>100 ур.<br/>';
echo'<img src="/style/images/body/rating.png" alt=""/>1 место: <img src="/style/images/many/gold.png" alt=""/>800 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>2 место: <img src="/style/images/many/gold.png" alt=""/>700 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>3 место: <img src="/style/images/many/gold.png" alt=""/>600 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>4 место: <img src="/style/images/many/gold.png" alt=""/>500 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>5 место: <img src="/style/images/many/gold.png" alt=""/>400 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>6 место: <img src="/style/images/many/gold.png" alt=""/>350 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>7 место: <img src="/style/images/many/gold.png" alt=""/>300 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>8 место: <img src="/style/images/many/gold.png" alt=""/>250 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>9 место: <img src="/style/images/many/gold.png" alt=""/>200 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>10 место: <img src="/style/images/many/gold.png" alt=""/>100 золота</br>';
#-Дуэли-#
echo'<img src="/style/images/body/league.png" alt=""/><span class="yellow">Дуэли</span> - сражайтесь с другими игроками и побеждайте.<br/>';
echo'Этот турнир доступен всем игрокам у которых открыты дуэли.<br/>';
echo'<img src="/style/images/body/rating.png" alt=""/>1 место: <img src="/style/images/many/gold.png" alt=""/>800 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>2 место: <img src="/style/images/many/gold.png" alt=""/>700 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>3 место: <img src="/style/images/many/gold.png" alt=""/>600 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>4 место: <img src="/style/images/many/gold.png" alt=""/>500 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>5 место: <img src="/style/images/many/gold.png" alt=""/>400 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>6 место: <img src="/style/images/many/gold.png" alt=""/>350 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>7 место: <img src="/style/images/many/gold.png" alt=""/>300 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>8 место: <img src="/style/images/many/gold.png" alt=""/>250 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>9 место: <img src="/style/images/many/gold.png" alt=""/>200 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>10 место: <img src="/style/images/many/gold.png" alt=""/>100 золота</br>';
#-Колизей-#
echo'<img src="/style/images/body/coliseum.png" alt=""/><span class="yellow">Колизей</span> - убей как можно больше оппонентов.<br/>';
echo'Этот турнир доступен всем игрокам у которых открыт колизей.<br/>';
echo'<img src="/style/images/body/rating.png" alt=""/>1 место: <img src="/style/images/many/gold.png" alt=""/>800 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>2 место: <img src="/style/images/many/gold.png" alt=""/>700 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>3 место: <img src="/style/images/many/gold.png" alt=""/>600 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>4 место: <img src="/style/images/many/gold.png" alt=""/>500 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>5 место: <img src="/style/images/many/gold.png" alt=""/>400 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>6 место: <img src="/style/images/many/gold.png" alt=""/>350 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>7 место: <img src="/style/images/many/gold.png" alt=""/>300 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>8 место: <img src="/style/images/many/gold.png" alt=""/>250 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>9 место: <img src="/style/images/many/gold.png" alt=""/>200 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>10 место: <img src="/style/images/many/gold.png" alt=""/>100 золота</br>';
#-Башни-#
echo'<img src="/style/images/body/towers.png" alt=""/><span class="yellow">Башни</span> - убей как можно больше оппонентов.<br/>';
echo'Этот турнир доступен всем игрокам у которых открыты башни.<br/>';
echo'<img src="/style/images/body/rating.png" alt=""/>1 место: <img src="/style/images/many/gold.png" alt=""/>800 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>2 место: <img src="/style/images/many/gold.png" alt=""/>700 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>3 место: <img src="/style/images/many/gold.png" alt=""/>600 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>4 место: <img src="/style/images/many/gold.png" alt=""/>500 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>5 место: <img src="/style/images/many/gold.png" alt=""/>400 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>6 место: <img src="/style/images/many/gold.png" alt=""/>350 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>7 место: <img src="/style/images/many/gold.png" alt=""/>300 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>8 место: <img src="/style/images/many/gold.png" alt=""/>250 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>9 место: <img src="/style/images/many/gold.png" alt=""/>200 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>10 место: <img src="/style/images/many/gold.png" alt=""/>100 золота</br>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Зачисление наград происходит каждое воскресенье в <img src="/style/images/body/time.png" alt=""/>12:00 по игровому времени</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Турнир кланов-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=tournament_clan"><img src="/style/images/body/tournament_clan.png" alt=""/> Турнир кланов</a></li>';
if($_GET['spr'] == 'tournament_clan'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/tournament_clan.png" alt=""/><span class="yellow">Турнир кланов</span> - локация в которой можно посмотреть информацию о месте вашегоклана в еженедельных турнирах.<br/>';
#-Статуэтки-#
echo'<img src="/style/images/user/figur.png" alt=""/><span class="yellow">Статуэтки</span> - клан собирает статуэтки путем обмена <img src="/style/images/user/exp.png" alt=""/>1\'000\'000 => <img src="/style/images/user/figur.png" alt=""/>1.<br/>';
echo'Доступен только c <img src="/style/images/user/level.png" alt=""/>100 уровня клана.<br/>';
echo'<img src="/style/images/body/rating.png" alt=""/>1 место: <img src="/style/images/many/gold.png" alt=""/>3000 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>2 место: <img src="/style/images/many/gold.png" alt=""/>2500 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>3 место: <img src="/style/images/many/gold.png" alt=""/>2250 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>4 место: <img src="/style/images/many/gold.png" alt=""/>2000 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>5 место: <img src="/style/images/many/gold.png" alt=""/>1750 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>6 место: <img src="/style/images/many/gold.png" alt=""/>1500 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>7 место: <img src="/style/images/many/gold.png" alt=""/>1250 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>8 место: <img src="/style/images/many/gold.png" alt=""/>1000 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>9 место: <img src="/style/images/many/gold.png" alt=""/>750 золота</br>';
echo'<img src="/style/images/body/rating.png" alt=""/>10 место: <img src="/style/images/many/gold.png" alt=""/>500 золота</br>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Зачисление наград происходит каждое воскресенье в <img src="/style/images/body/time.png" alt=""/>12:00 по игровому времени</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Все <img src="/style/images/many/gold.png" alt=""/>Золото зачисляется в казну клана</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}


#-Зал славы-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=rating"><img src="/style/images/body/rating.png" alt=""/> Зал славы</a></li>';
if($_GET['spr'] == 'rating'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/rating.png" alt=""/><span class="yellow">Зал славы</span> - локация где можно увидеть самых сильных игроков в игре в определённой категории.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Обновление позиции происходит сразу после изменения параметров игрока</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Обменник-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=exchange"><img src="/style/images/body/obmenik.png" alt=""/> Обменник</a></li>';
if($_GET['spr'] == 'exchange'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/obmenik.png" alt=""/><span class="yellow">Обменник</span> - в этой локации можно обменивать <img src="/style/images/many/gold.png"><span class="gray">Золото</span> на <img src="/style/images/many/silver.png"><span class="gray">Серебро</span> или наоборот.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Обмен серебра на золото доступен 1 раз в 24 часа</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Количество золота (Серебро=>Золото) зависит от вашего <img src="/style/images/user/level.png" alt=""/>Уровня</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Получить золото-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=location&spr=buy_gold"><img src="/style/images/many/gold.png" alt=""/> Получить золото</a></li>';
if($_GET['spr'] == 'buy_gold'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/many/gold.png" alt=""/><span class="yellow">Получить золото</span> - здесь вы можете купить дополнительное золото за реальные средства. Вам доступно несколько способов оплаты с различными бонусами.<br/>';
echo'Также в игре переодически проводяться выгодные акции на покупку золота.';
echo'</span>';
echo'</div>';
echo'</div>';
}

echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'</div>';
?>