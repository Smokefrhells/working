<?php
echo'<div class="body_list">';
echo'<div class="menulist">';

                                                                             /*КЛАНЫ*/
#-Общая информация-#
echo'<li><a href="/knowledge_basa?sort=clan&spr=obs_clan"><img src="/style/images/clan/edit.png" alt=""/> Общая информация</a></li>';
if($_GET['spr'] == 'obs_clan'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'Кланы доступны всем игрокам с <img src="/style/images/user/level.png" alt=""/>15 ур.<br/>';
echo'Каждый игрок может создать свой клан, цена создания <img src="/style/images/many/gold.png" alt=""/><span class="gray">1000 золота</span>.<br/>';
echo'Клан бывает 2-х типов <span class="gray">Открытый</span> и <span class="gray">Закрытый</span>.<br/>';
echo'При открытом типе в клан может вступить любой игрок, а при закрытом вступление осуществляеться только подачей заявки.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальный уровень <img src="/style/images/user/level.png" alt=""/>150</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Если вы покинули клан, то не сможете вступить в другой в течении суток</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">При создании своего клана время аннулируется</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Казна клана-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=clan&spr=clan_kazna"><img src="/style/images/clan/kazna.png" alt=""/> Казна клана</a></li>';
if($_GET['spr'] == 'clan_kazna'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/clan/kazna.png" alt=""/><span class="yellow">Казна клана</span> - это хранилище где храниться клановое <span class="gray">золото</span> и <span class="gray">серебро</span>.<br/>';
echo'Только <img src="/style/images/clan/crown.png" alt=""/><span class="gray">Основатель</span> или <img src="/style/images/clan/crown.png" alt=""/><span class="gray">Старейшина</span> клана может тратить средства из казны на развитие клана.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Обнуление лимита происходит в течении 12 часов или при получении нового уровня</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Кол-во взносов зависит от уровня игрока и уровня сокровищницы клана</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Состав клана-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=clan&spr=clan_users"><img src="/style/images/clan/users.png" alt=""/> Состав клана</a></li>';
if($_GET['spr'] == 'clan_users'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/clan/users.png" alt=""/><span class="yellow">Состав клана</span> - можно посмотреть кто состоит в клане, кол-во опыта (тот опыт который игрок заработал для клана), кол-во золота и серебра потраченое игроком на развитие клана и звание игрока в клане.<br/>';
echo'Также здесь <span class="gray">Основатель</span> или <span class="gray">Старейшина</span> может управлять игроками клана, повышать звание игрокам, передавать права, исключать игроков.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальный состав клана 100 игроков</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Постройки клана-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=clan&spr=clan_building"><img src="/style/images/clan/building.png" alt=""/> Постройки клана</a></li>';
if($_GET['spr'] == 'clan_building'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/clan/building.png" alt=""/><span class="yellow">Постройки клана</span> - улучшают характеристику клана перед другими кланами.<br/>';

#-Казармы-#
echo'<div style="padding: 5px;"></div>';
echo'<img src="/style/images/clan/building.png" alt=""/><span class="yellow">Казармы</span> - увеличивают количество мест в клане.<br/>';
echo'<span class="green">';
echo'10 - <img src="/style/images/many/gold.png">325<br/>';
echo'15 - <img src="/style/images/many/gold.png">450<br/>';
echo'20 - <img src="/style/images/many/gold.png">575<br/>';
echo'25 - <img src="/style/images/many/gold.png">700<br/>';
echo'30 - <img src="/style/images/many/gold.png">825<br/>';
echo'35 - <img src="/style/images/many/gold.png">950<br/>';
echo'40 - <img src="/style/images/many/gold.png">1\'075<br/>';
echo'45 - <img src="/style/images/many/gold.png">2\'300<br/>';
echo'50 - <img src="/style/images/many/gold.png">2\'525<br/>';
echo'55 - <img src="/style/images/many/gold.png">2\'750<br/>';
echo'60 - <img src="/style/images/many/gold.png">2\'975<br/>';
echo'65 - <img src="/style/images/many/gold.png">3\'200<br/>';
echo'70 - <img src="/style/images/many/gold.png">3\'425<br/>';
echo'75 - <img src="/style/images/many/gold.png">3\'650<br/>';
echo'80 - <img src="/style/images/many/gold.png">3\'875<br/>';
echo'85 - <img src="/style/images/many/gold.png">4\'100<br/>';
echo'90 - <img src="/style/images/many/gold.png">4\'325<br/>';
echo'95 - <img src="/style/images/many/gold.png">4\'550<br/>';
echo'100 - <img src="/style/images/many/gold.png">4\'775<br/>';
echo'</span>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальное количество 100</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Прокачка только за <img src="/style/images/many/gold.png" alt=""/>Золото</span><br/>';

#-Замки-#
echo'<div style="padding: 5px;"></div>';
echo'<img src="/style/images/clan/building.png" alt=""/><span class="yellow">Замки</span> - увеличивают защиту клана.<br/>';
echo'<span class="green">';
echo'<img src="/style/images/user/level.png">2ур. - <img src="/style/images/many/silver.png">32\'000<br/>';
echo'<img src="/style/images/user/level.png">3ур. - <img src="/style/images/many/silver.png">62\'000<br/>';
echo'<img src="/style/images/user/level.png">4ур. - <img src="/style/images/many/silver.png">92\'000<br/>';
echo'<img src="/style/images/user/level.png">5ур. - <img src="/style/images/many/silver.png">122\'000<br/>';
echo'<img src="/style/images/user/level.png">6ур. - <img src="/style/images/many/silver.png">152\'000<br/>';
echo'<img src="/style/images/user/level.png">7ур. - <img src="/style/images/many/silver.png">182\'000<br/>';
echo'<img src="/style/images/user/level.png">8ур. - <img src="/style/images/many/silver.png">212\'000<br/>';
echo'<img src="/style/images/user/level.png">9ур. - <img src="/style/images/many/silver.png">242\'000<br/>';
echo'<img src="/style/images/user/level.png">10ур. - <img src="/style/images/many/silver.png">272\'000<br/>';
echo'<img src="/style/images/user/level.png">11ур. - <img src="/style/images/many/gold.png">4\'000<br/>'; //Золото
echo'<img src="/style/images/user/level.png">12ур. - <img src="/style/images/many/silver.png">332\'000<br/>';
echo'<img src="/style/images/user/level.png">13ур. - <img src="/style/images/many/silver.png">362\'000<br/>';
echo'<img src="/style/images/user/level.png">14ур. - <img src="/style/images/many/silver.png">392\'000<br/>';
echo'<img src="/style/images/user/level.png">15ур. - <img src="/style/images/many/silver.png">422\'000<br/>';
echo'<img src="/style/images/user/level.png">16ур. - <img src="/style/images/many/silver.png">452\'000<br/>';
echo'<img src="/style/images/user/level.png">17ур. - <img src="/style/images/many/silver.png">482\'000<br/>';
echo'<img src="/style/images/user/level.png">18ур. - <img src="/style/images/many/silver.png">512\'000<br/>';
echo'<img src="/style/images/user/level.png">19ур. - <img src="/style/images/many/silver.png">542\'000<br/>';
echo'<img src="/style/images/user/level.png">20ур. - <img src="/style/images/many/silver.png">572\'000<br/>';
echo'<img src="/style/images/user/level.png">21ур. - <img src="/style/images/many/gold.png">7\'200<br/>'; //Золото
echo'<img src="/style/images/user/level.png">22ур. - <img src="/style/images/many/silver.png">632\'000<br/>';
echo'<img src="/style/images/user/level.png">23ур. - <img src="/style/images/many/silver.png">662\'000<br/>';
echo'<img src="/style/images/user/level.png">24ур. - <img src="/style/images/many/silver.png">692\'000<br/>';
echo'<img src="/style/images/user/level.png">25ур. - <img src="/style/images/many/silver.png">722\'000<br/>';
echo'<img src="/style/images/user/level.png">26ур. - <img src="/style/images/many/silver.png">752\'000<br/>';
echo'<img src="/style/images/user/level.png">27ур. - <img src="/style/images/many/silver.png">782\'000<br/>';
echo'<img src="/style/images/user/level.png">28ур. - <img src="/style/images/many/silver.png">812\'000<br/>';
echo'<img src="/style/images/user/level.png">29ур. - <img src="/style/images/many/silver.png">842\'000<br/>';
echo'<img src="/style/images/user/level.png">30ур. - <img src="/style/images/many/silver.png">872\'000<br/>';
echo'<img src="/style/images/user/level.png">31ур. - <img src="/style/images/many/gold.png">10\'400<br/>'; //Золото
echo'<img src="/style/images/user/level.png">32ур. - <img src="/style/images/many/silver.png">932\'000<br/>';
echo'<img src="/style/images/user/level.png">33ур. - <img src="/style/images/many/silver.png">962\'000<br/>';
echo'<img src="/style/images/user/level.png">34ур. - <img src="/style/images/many/silver.png">992\'000<br/>';
echo'<img src="/style/images/user/level.png">35ур. - <img src="/style/images/many/silver.png">1\'022\'000<br/>';
echo'<img src="/style/images/user/level.png">36ур. - <img src="/style/images/many/silver.png">1\'052\'000<br/>';
echo'<img src="/style/images/user/level.png">37ур. - <img src="/style/images/many/silver.png">1\'082\'000<br/>';
echo'<img src="/style/images/user/level.png">38ур. - <img src="/style/images/many/silver.png">1\'112\'000<br/>';
echo'<img src="/style/images/user/level.png">39ур. - <img src="/style/images/many/silver.png">1\'142\'000<br/>';
echo'<img src="/style/images/user/level.png">40ур. - <img src="/style/images/many/silver.png">1\'172\'000<br/>';
echo'<img src="/style/images/user/level.png">41ур. - <img src="/style/images/many/gold.png">13\'600<br/>'; //Золото
echo'<img src="/style/images/user/level.png">42ур. - <img src="/style/images/many/silver.png">1\'232\'000<br/>';
echo'<img src="/style/images/user/level.png">43ур. - <img src="/style/images/many/silver.png">1\'262\'000<br/>';
echo'<img src="/style/images/user/level.png">44ур. - <img src="/style/images/many/silver.png">1\'292\'000<br/>';
echo'<img src="/style/images/user/level.png">45ур. - <img src="/style/images/many/silver.png">1\'322\'000<br/>';
echo'<img src="/style/images/user/level.png">46ур. - <img src="/style/images/many/silver.png">1\'352\'000<br/>';
echo'<img src="/style/images/user/level.png">47ур. - <img src="/style/images/many/silver.png">1\'382\'000<br/>';
echo'<img src="/style/images/user/level.png">48ур. - <img src="/style/images/many/silver.png">1\'412\'000<br/>';
echo'<img src="/style/images/user/level.png">49ур. - <img src="/style/images/many/silver.png">1\'442\'000<br/>';
echo'<img src="/style/images/user/level.png">50ур. - <img src="/style/images/many/silver.png">1\'472\'000<br/>';
echo'<img src="/style/images/user/level.png">51ур. - <img src="/style/images/many/gold.png">16\'800<br/>'; //Золото
echo'<img src="/style/images/user/level.png">52ур. - <img src="/style/images/many/silver.png">1\'532\'000<br/>';
echo'<img src="/style/images/user/level.png">53ур. - <img src="/style/images/many/silver.png">1\'562\'000<br/>';
echo'<img src="/style/images/user/level.png">54ур. - <img src="/style/images/many/silver.png">1\'592\'000<br/>';
echo'<img src="/style/images/user/level.png">55ур. - <img src="/style/images/many/silver.png">1\'622\'000<br/>';
echo'<img src="/style/images/user/level.png">56ур. - <img src="/style/images/many/silver.png">1\'652\'000<br/>';
echo'<img src="/style/images/user/level.png">57ур. - <img src="/style/images/many/silver.png">1\'682\'000<br/>';
echo'<img src="/style/images/user/level.png">58ур. - <img src="/style/images/many/silver.png">1\'712\'000<br/>';
echo'<img src="/style/images/user/level.png">59ур. - <img src="/style/images/many/silver.png">1\'742\'000<br/>';
echo'<img src="/style/images/user/level.png">60ур. - <img src="/style/images/many/silver.png">1\'772\'000<br/>';
echo'<img src="/style/images/user/level.png">61ур. - <img src="/style/images/many/gold.png">20\'000<br/>'; //Золото
echo'<img src="/style/images/user/level.png">62ур. - <img src="/style/images/many/silver.png">1\'832\'000<br/>';
echo'<img src="/style/images/user/level.png">63ур. - <img src="/style/images/many/silver.png">1\'862\'000<br/>';
echo'<img src="/style/images/user/level.png">64ур. - <img src="/style/images/many/silver.png">1\'892\'000<br/>';
echo'<img src="/style/images/user/level.png">65ур. - <img src="/style/images/many/silver.png">1\'922\'000<br/>';
echo'<img src="/style/images/user/level.png">66ур. - <img src="/style/images/many/silver.png">1\'952\'000<br/>';
echo'<img src="/style/images/user/level.png">67ур. - <img src="/style/images/many/silver.png">1\'982\'000<br/>';
echo'<img src="/style/images/user/level.png">68ур. - <img src="/style/images/many/silver.png">2\'012\'000<br/>';
echo'<img src="/style/images/user/level.png">69ур. - <img src="/style/images/many/silver.png">2\'042\'000<br/>';
echo'<img src="/style/images/user/level.png">70ур. - <img src="/style/images/many/silver.png">2\'072\'000<br/>';
echo'<img src="/style/images/user/level.png">71ур. - <img src="/style/images/many/gold.png">23\'200<br/>'; //Золото
echo'<img src="/style/images/user/level.png">72ур. - <img src="/style/images/many/silver.png">2\'132\'000<br/>';
echo'<img src="/style/images/user/level.png">73ур. - <img src="/style/images/many/silver.png">2\'162\'000<br/>';
echo'<img src="/style/images/user/level.png">74ур. - <img src="/style/images/many/silver.png">2\'192\'000<br/>';
echo'<img src="/style/images/user/level.png">75ур. - <img src="/style/images/many/silver.png">2\'222\'000<br/>';
echo'<img src="/style/images/user/level.png">76ур. - <img src="/style/images/many/silver.png">2\'252\'000<br/>';
echo'<img src="/style/images/user/level.png">77ур. - <img src="/style/images/many/silver.png">2\'282\'000<br/>';
echo'<img src="/style/images/user/level.png">78ур. - <img src="/style/images/many/silver.png">2\'312\'000<br/>';
echo'<img src="/style/images/user/level.png">79ур. - <img src="/style/images/many/silver.png">2\'342\'000<br/>';
echo'<img src="/style/images/user/level.png">80ур. - <img src="/style/images/many/silver.png">2\'372\'000<br/>';
echo'<img src="/style/images/user/level.png">81ур. - <img src="/style/images/many/gold.png">26\'400<br/>'; //Золото
echo'<img src="/style/images/user/level.png">82ур. - <img src="/style/images/many/silver.png">2\'432\'000<br/>';
echo'<img src="/style/images/user/level.png">83ур. - <img src="/style/images/many/silver.png">2\'462\'000<br/>';
echo'<img src="/style/images/user/level.png">84ур. - <img src="/style/images/many/silver.png">2\'492\'000<br/>';
echo'<img src="/style/images/user/level.png">85ур. - <img src="/style/images/many/silver.png">2\'522\'000<br/>';
echo'<img src="/style/images/user/level.png">86ур. - <img src="/style/images/many/silver.png">2\'552\'000<br/>';
echo'<img src="/style/images/user/level.png">87ур. - <img src="/style/images/many/silver.png">2\'582\'000<br/>';
echo'<img src="/style/images/user/level.png">88ур. - <img src="/style/images/many/silver.png">2\'612\'000<br/>';
echo'<img src="/style/images/user/level.png">89ур. - <img src="/style/images/many/silver.png">2\'642\'000<br/>';
echo'<img src="/style/images/user/level.png">90ур. - <img src="/style/images/many/silver.png">2\'672\'000<br/>';
echo'<img src="/style/images/user/level.png">91ур. - <img src="/style/images/many/gold.png">29\'600<br/>'; //Золото
echo'<img src="/style/images/user/level.png">92ур. - <img src="/style/images/many/silver.png">2\'737\'000<br/>';
echo'<img src="/style/images/user/level.png">93ур. - <img src="/style/images/many/silver.png">2\'762\'000<br/>';
echo'<img src="/style/images/user/level.png">94ур. - <img src="/style/images/many/silver.png">2\'792\'000<br/>';
echo'<img src="/style/images/user/level.png">95ур. - <img src="/style/images/many/silver.png">2\'822\'000<br/>';
echo'<img src="/style/images/user/level.png">96ур. - <img src="/style/images/many/silver.png">2\'852\'000<br/>';
echo'<img src="/style/images/user/level.png">97ур. - <img src="/style/images/many/silver.png">2\'882\'000<br/>';
echo'<img src="/style/images/user/level.png">98ур. - <img src="/style/images/many/silver.png">2\'912\'000<br/>';
echo'<img src="/style/images/user/level.png">99ур. - <img src="/style/images/many/silver.png">2\'942\'000<br/>';
echo'<img src="/style/images/user/level.png">100ур. - <img src="/style/images/many/silver.png">2\'972\'000<br/>';
echo'</span>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Максимальная защита без учета здоровья игроков 99\'050\'000</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Прокачка каждого <img src="/style/images/user/level.png" alt=""/>10 ур. за <img src="/style/images/many/gold.png" alt=""/>Золото</span><br/>';

#-Амулет-#
echo'<div style="padding: 5px;"></div>';
echo'<img src="/style/images/clan/building.png" alt=""/><span class="yellow">Амулет</span> - увеличивает количество призовых мест в Рейтинге опыта. Максимальное количество призовых мест 10. Улучшение производиться за <img src="/style/images/many/gold.png">Золото.<br/>';
echo'Количество призовых мест и цена за улучшение:<br/>';
echo'<span class="green">';
echo'4 - <img src="/style/images/many/gold.png">4\'000<br/>';
echo'5 - <img src="/style/images/many/gold.png">5\'000<br/>';
echo'6 - <img src="/style/images/many/gold.png">6\'000<br/>';
echo'7 - <img src="/style/images/many/gold.png">7\'000<br/>';
echo'8 - <img src="/style/images/many/gold.png">8\'000<br/>';
echo'9 - <img src="/style/images/many/gold.png">9\'000<br/>';
echo'10 - <img src="/style/images/many/gold.png">10\'000<br/>';
echo'</span>';
echo'<img src="/style/images/body/gift.png">Награду за призовые места назначает Создатель клана<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Прокачка только за <img src="/style/images/many/gold.png" alt=""/>Золото</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Награда высчитывается из казны клана</span><br/>';

#-Сокровищница-#
echo'<div style="padding: 5px;"></div>';
echo'<img src="/style/images/clan/building.png" alt=""/><span class="yellow">Сокровищница</span> - увеличивает лимит взносов в казну.<br/>';
echo'<span class="green">';
echo'<img src="/style/images/user/level.png" alt=""/>1 ур. - <img src="/style/images/many/gold.png">1\'000<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>2 ур. - <img src="/style/images/many/gold.png">1\'800<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>3 ур. - <img src="/style/images/many/gold.png">2\'600<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>4 ур. - <img src="/style/images/many/gold.png">3\'400<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>5 ур. - <img src="/style/images/many/gold.png">4\'200<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>6 ур. - <img src="/style/images/many/gold.png">5\'000<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>7 ур. - <img src="/style/images/many/gold.png">5\'800<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>8 ур. - <img src="/style/images/many/gold.png">6\'600<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>9 ур. - <img src="/style/images/many/gold.png">7\'400<br/>';
echo'<img src="/style/images/user/level.png" alt=""/>10 ур. - <img src="/style/images/many/gold.png">8\'200<br/>';
echo'</span>';
echo'Лимит взносов без учета <img src="/style/images/user/level.png" alt=""/>уровня игрока:<br/>';
echo'<span class="green">';
echo'<img src="/style/images/user/level.png" alt=""/>1 ур. - <img src="/style/images/many/gold.png">200 <img src="/style/images/many/silver.png">100\'000<br/>';
echo'<img src="/style/images/user/level.png">2 ур. - <img src="/style/images/many/gold.png">400 <img src="/style/images/many/silver.png">200\'000<br/>';
echo'<img src="/style/images/user/level.png">3 ур. - <img src="/style/images/many/gold.png">600 <img src="/style/images/many/silver.png">300\'000<br/>';
echo'<img src="/style/images/user/level.png">4 ур. - <img src="/style/images/many/gold.png">800 <img src="/style/images/many/silver.png">400\'000<br/>';
echo'<img src="/style/images/user/level.png">5 ур. - <img src="/style/images/many/gold.png">1\'000 <img src="/style/images/many/silver.png">500\'000<br/>';
echo'<img src="/style/images/user/level.png">6 ур. - <img src="/style/images/many/gold.png">1\'200 <img src="/style/images/many/silver.png">600\'000<br/>';
echo'<img src="/style/images/user/level.png">7 ур. - <img src="/style/images/many/gold.png">1\'400 <img src="/style/images/many/silver.png">700\'000<br/>';
echo'<img src="/style/images/user/level.png">8 ур. - <img src="/style/images/many/gold.png">1\'600 <img src="/style/images/many/silver.png">800\'000<br/>';
echo'<img src="/style/images/user/level.png">9 ур. - <img src="/style/images/many/gold.png">1\'800 <img src="/style/images/many/silver.png">900\'000<br/>';
echo'<img src="/style/images/user/level.png">10 ур. - <img src="/style/images/many/gold.png">2\'000 <img src="/style/images/many/silver.png">1\'000\'000<br/>';
echo'</span>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Прокачка только за <img src="/style/images/many/gold.png" alt=""/>Золото</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Рейтинг опыта-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=clan&spr=rating_exp_clan"><img src="/style/images/user/exp.png" alt=""/> Рейтинг опыта</a></li>';
if($_GET['spr'] == 'rating_exp_clan'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/user/exp.png" alt=""/><span class="yellow">Рейтинг опыта</span> - кто наберет наибольшее количество <img src="/style/images/user/exp.png" alt=""/><span class="gray">Опыта</span> для клана за 24 часа, тот получит в награду <img src="/style/images/many/gold.png" alt=""/><span class="gray">Золото</span>.<br/>'; 
echo'Количество призовых мест зависит от постройки <img src="/style/images/clan/building.png"><span class="gray">Амулет</span>, начальное количество мест 3.<br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Кол-во золота для награды назначает Основатель</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Кол-во активных игроков должно быть не менее 3</span><br/>';
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Обнуление и награждение происходит в 00:00 по игровому времени</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-История клана-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=clan&spr=clan_history"><img src="/style/images/clan/history.png" alt=""/> История клана</a></li>';
if($_GET['spr'] == 'clan_history'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/clan/history.png" alt=""/><span class="yellow">История клана</span> - здесь сохраняеться вся информация об изменениях в клане.<br/>'; 
echo'<img src="/style/images/body/imp.png" alt=""/> <span style="font-size:14px; color:#dc50ff;">Логи сохраняються в течении 14 дней после чего удаляються</span><br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}

#-Звания клана-#
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa?sort=clan&spr=clan_rank"><img src="/style/images/clan/crown.png" alt=""/> Звания клана</a></li>';
if($_GET['spr'] == 'clan_rank'){
echo'<div class="line_1_m"></div>';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo'<span class="whit">';
echo'<img src="/style/images/clan/crown.png" alt=""/><span class="yellow">Новичок</span> - не имеет никаких привилегий.<br/>';
echo'<img src="/style/images/clan/crown.png" alt=""/><span class="yellow">Боец</span> - может приглашать других игроков в клан.<br/>';
echo'<img src="/style/images/clan/crown.png" alt=""/><span class="yellow">Ветеран</span> - может приглашать других игроков в клан и принимать заявки.<br/>';
echo'<img src="/style/images/clan/crown.png" alt=""/><span class="yellow">Старейшина</span> - заместитель создателя клана и обладает такими же правами что и создатель, за исключением того что не может исключить <span class="gray">Основателя</span> или <span class="gray">Старейшину</span> или понижать их в должности.<br/>';
echo'<img src="/style/images/clan/crown.png" alt=""/><span class="yellow">Основатель</span> - создатель клана обладает всеми правами на клан. Может исключать игроков из клана, передать права другому игроку, назначать звания игрокам, редактировать клан, тратить средства из казны клана на его развитие.<br/>';
echo'</span>';
echo'</div>';
echo'</div>';
}
   
echo'<div class="line_1"></div>';
echo'<li><a href="/knowledge_basa"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';                                                                     
echo'</div>';
echo'</div>';
?>