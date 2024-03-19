<?php
echo'<div class="body_list">';
echo'<div class="menulist">';
                                                                     #-ДРУГОЕ-#                                                                
#-Новости-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=other&spr=news"><img src="/style/images/news/news.png" alt=""/> Новости</a></li>';
if($_GET['spr'] == 'news'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/news/news.png" alt=""/><span class="yellow">Новости</span> - в этом разделе публикуются все нововведения и изменения в игре.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Комментирование доступно с <img src="/style/images/user/level.png" alt=""/>15 ур.</span><br/>';
echo'</div>';
echo'</div>';
}

#-Чат-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=other&spr=chat"><img src="/style/images/body/clan_chat.png" alt=""/> Чат</a></li>';
if($_GET['spr'] == 'chat'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/clan_chat.png" alt=""/><span class="yellow">Чат</span> - в глобальном чате вы можете общаться с другими игроками.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Доступен с <img src="/style/images/user/level.png" alt=""/>15 ур.</span><br/>';
echo'</div>';
echo'</div>';
}

#-Форум-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=other&spr=forum"><img src="/style/images/forum/forum.png" alt=""/> Форум</a></li>';
if($_GET['spr'] == 'forum'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/forum/forum.png" alt=""/><span class="yellow">Форум</span> - в форуме можно общаться, делиться своими идеями по поводу игры, брать активное участие в развитии игры и многое другое.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Комментирование и создание топиков с <img src="/style/images/user/level.png" alt=""/>30 ур.</span><br/>';
echo'</div>';
echo'</div>';
}

#-Цвет акций-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=other&spr=stock"><img src="/style/images/body/ok.png" alt=""/> Цвет акций</a></li>';
if($_GET['spr'] == 'stock'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/body/ok.png" alt=""/>По цвету акции можно сразу определить что это акция на покупку золота, скидка или выгодное предложение.<br/>';
echo'<span class="orange">Акция на покупку золота</span><br/>';
echo'<span class="green">Игровая скидка</span><br/>';
echo'<span class="purple">Выгодное предложение</span><br/>';
echo'</div>';
echo'</div>';
}

#-Комплекты-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=other&spr=snarag"><img src="/style/images/body/snarag.png" alt=""/> Комплекты</a></li>';
if($_GET['spr'] == 'snarag'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';

echo'<img src="/style/images/body/torg.png" alt=""/><span class="yellow">Обычные из торговой лавки:</span><br/>';
#-Комплект Рыцаря-#
echo'<img src="/style/images/quality/1.png" alt=""/><span class="whit">Комплект Рыцаря[3 ур.] <img src="/style/images/body/all.png" alt=""/>4500</span><br/>';
echo'<img src="/style/images/weapon/head/1.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/body/1.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/gloves/1.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/shield/1.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/arm/1.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/legs/1.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<br/>';

#-Комплект Защитника-#
echo'<img src="/style/images/quality/2.png" alt=""/><span class="whit">Комплект Защитника[5 ур.] <img src="/style/images/body/all.png" alt=""/>9000</span><br/>';
echo'<img src="/style/images/weapon/head/2.png" width="48px" height="48px" class="weapon_2" alt=""/>';
echo'<img src="/style/images/weapon/body/2.png" width="48px" height="48px" class="weapon_2" alt=""/>';
echo'<img src="/style/images/weapon/gloves/2.png" width="48px" height="48px" class="weapon_2" alt=""/>';
echo'<img src="/style/images/weapon/shield/2.png" width="48px" height="48px" class="weapon_2" alt=""/>';
echo'<img src="/style/images/weapon/arm/2.png" width="48px" height="48px" class="weapon_2" alt=""/>';
echo'<img src="/style/images/weapon/legs/2.png" width="48px" height="48px" class="weapon_2" alt=""/>';
echo'<br/>';

#-Комплект Ассасина-#
echo'<img src="/style/images/quality/3.png" alt=""/><span class="whit">Комплект Ассасина[10 ур.] <img src="/style/images/body/all.png" alt=""/>13500</span><br/>';
echo'<img src="/style/images/weapon/head/3.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/body/3.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/gloves/3.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/shield/3.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/arm/3.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/legs/3.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<br/>';

#-Комплект Убийцы-#
echo'<img src="/style/images/quality/4.png" alt=""/><span class="whit">Комплект Убийцы[20 ур.] <img src="/style/images/body/all.png" alt=""/>18000</span><br/>';
echo'<img src="/style/images/weapon/head/4.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/body/4.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/gloves/4.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/shield/4.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/arm/4.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/legs/4.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<br/>';

#-Комплект Разрушителя-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Разрушителя[30 ур.] <img src="/style/images/body/all.png" alt=""/>22500</span><br/>';
echo'<img src="/style/images/weapon/head/6.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/6.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/6.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/6.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/6.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/6.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Варвара-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Варвара[40 ур.] <img src="/style/images/body/all.png" alt=""/>27000</span><br/>';
echo'<img src="/style/images/weapon/head/7.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/7.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/7.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/7.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/7.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/7.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Ополченца-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Ополченца[50 ур.] <img src="/style/images/body/all.png" alt=""/>31500</span><br/>';
echo'<img src="/style/images/weapon/head/8.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/8.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/8.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/8.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/8.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/8.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Тьмы-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Тьмы[60 ур.] <img src="/style/images/body/all.png" alt=""/>36000</span><br/>';
echo'<img src="/style/images/weapon/head/9.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/9.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/9.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/9.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/9.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/9.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Короля-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Короля[70 ур.] <img src="/style/images/body/all.png" alt=""/>40500</span><br/>';
echo'<img src="/style/images/weapon/head/10.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/10.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/10.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/10.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/10.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/10.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Хранителя-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Хранителя[80 ур.] <img src="/style/images/body/all.png" alt=""/>45000</span><br/>';
echo'<img src="/style/images/weapon/head/11.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/11.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/11.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/11.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/11.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/11.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Правителя-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Правителя[90 ур.] <img src="/style/images/body/all.png" alt=""/>49500</span><br/>';
echo'<img src="/style/images/weapon/head/12.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/12.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/12.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/12.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/12.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/12.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/>';

#-Комплект Темного рыцаря-#
echo'<img src="/style/images/quality/5.png" alt=""/><span class="whit">Комплект Темного рыцаря[100 ур.] <img src="/style/images/body/all.png" alt=""/>54000</span><br/>';
echo'<img src="/style/images/weapon/head/13.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/body/13.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/gloves/13.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/shield/13.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/arm/13.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<img src="/style/images/weapon/legs/13.png" width="48px" height="48px" class="weapon_5" alt=""/>';
echo'<br/><br/>';

echo'<img src="/style/images/body/torg.png" alt=""/><span class="yellow">Праздничные из торговой лавки:</span><br/>';
#-Комплект Холода-#
echo'<img src="/style/images/quality/4.png" alt=""/><span class="whit">Комплект Холода[25 ур.] <img src="/style/images/body/all.png" alt=""/>36000</span><br/>';
echo'<img src="/style/images/weapon/head/19.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/body/19.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/gloves/19.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/shield/19.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/arm/19.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<img src="/style/images/weapon/legs/19.png" width="48px" height="48px" class="weapon_4" alt=""/>';
echo'<br/>';

#-Комплект Стражника-#
echo'<img src="/style/images/quality/1.png" alt=""/><span class="whit">Комплект Стражника[70 ур.] <img src="/style/images/body/all.png" alt=""/>54000</span><br/>';
echo'<img src="/style/images/weapon/head/20.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/body/20.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/gloves/20.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/shield/20.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/arm/20.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<img src="/style/images/weapon/legs/20.png" width="48px" height="48px" class="weapon_1" alt=""/>';
echo'<br/>';

#-Комплект Карателя-#
echo'<img src="/style/images/quality/3.png" alt=""/><span class="whit">Комплект Карателя[100 ур.] <img src="/style/images/body/all.png" alt=""/>144000</span><br/>';
echo'<img src="/style/images/weapon/head/21.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/body/21.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/gloves/21.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/shield/21.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/arm/21.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<img src="/style/images/weapon/legs/21.png" width="48px" height="48px" class="weapon_3" alt=""/>';
echo'<br/><br/>';

echo'<img src="/style/images/body/chest.png" alt=""/><span class="yellow">Из золотого сундука:</span><br/>';
#-Комплект Знахаря-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Знахаря[15 ур.] <img src="/style/images/body/all.png" alt=""/>27000</span><br/>';
echo'<img src="/style/images/weapon/head/5.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/5.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/5.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/5.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/5.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/5.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';

#-Комплект Асторема-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Асторема[40 ур.] <img src="/style/images/body/all.png" alt=""/>36000</span><br/>';
echo'<img src="/style/images/weapon/head/14.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/14.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/14.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/14.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/14.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/14.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';

#-Комплект Приспешника-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Приспешника[70 ур.] <img src="/style/images/body/all.png" alt=""/>45000</span><br/>';
echo'<img src="/style/images/weapon/head/15.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/15.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/15.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/15.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/15.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/15.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';

#-Комплект Орвуна-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Орвуна[100 ур.] <img src="/style/images/body/all.png" alt=""/>126000</span><br/>';
echo'<img src="/style/images/weapon/head/16.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/16.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/16.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/16.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/16.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/16.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/><br/>';

echo'<img src="/style/images/body/achive.png" alt=""/><span class="yellow">За выполнение достижений:</span><br/>';
#-Комплект Паладина-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Паладина[1 ур.] <img src="/style/images/body/all.png" alt=""/>162000</span><br/>';
echo'<img src="/style/images/weapon/head/17.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/17.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/17.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/17.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/17.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/17.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';

#-Комплект Следопыта-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Следопыта[1 ур.] <img src="/style/images/body/all.png" alt=""/>216000</span><br/>';
echo'<img src="/style/images/weapon/head/22.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/22.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/22.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/22.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/22.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/22.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';

#-Комплект Охотника-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Охотника[1 ур.] <img src="/style/images/body/all.png" alt=""/>270000</span><br/>';
echo'<img src="/style/images/weapon/head/23.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/23.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/23.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/23.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/23.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/23.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';

#-Комплект Лорда-#
echo'<img src="/style/images/quality/7.png" alt=""/><span class="whit">Комплект Лорда[1 ур.] <img src="/style/images/body/all.png" alt=""/>324000</span><br/>';
echo'<img src="/style/images/weapon/head/24.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/body/24.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/gloves/24.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/shield/24.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/arm/24.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<img src="/style/images/weapon/legs/24.png" width="48px" height="48px" class="weapon_6" alt=""/>';
echo'<br/>';
echo'</div>';
echo'</div>';
}

echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>