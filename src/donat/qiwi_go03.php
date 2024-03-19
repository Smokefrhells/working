<?php
require_once '../system/system.php';
$head = 'Qiwi';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div style="padding: 3px; color: #bfbfbf;">';
echo'Переведите средства на один из Никнеймов киви. <b>с указанием вашего ID и НИКОМ в коментрариях</b>.<br/>';
echo'Далее напишите Администрации сообщение с текстом: <b>Покупка золота через Qiwi</b>.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>100 золота => 10 руб..<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>1000 золота => 100 руб.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>5000 золота => 500 руб.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>10000 золота => 1000 руб.<br/>';

/*
echo'<br>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>100</s></FONT> 300 золота => 10 руб..<br/>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>1000</s></FONT>  3000 золота => 100 руб.<br/>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>5000</s> </FONT> 15000 золота => 500 руб.<br/>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>10000</s> </FONT> 30000 золота => 1000 руб.<br/>';
*/
//echo '<span class="orange">';
echo"<span class='red'><img src='/style/images/user/user.png' alt=''/> Ваш ID: $user[id]</span><br/>";
echo"Оплатить автоматически:";
echo'<span class="yellow"><br/>';
echo'<center><a href="https://qiwi.com/n/ONECO421"><FONT color="lime">ОПЛАТИТЬ</FONT></a></center><br/></s>';

echo"<center>▼ Ручная оплата ▼ <br/></center>";
echo'</br>';
echo'</span>';
echo'<span class="#">1) Зайдите в QIWI Кошелек <br/> 2) Перевод по никнейму <br/>3) Введите никнейм игры, в поле Никнейм получателя: " ONECO421 " (БЕЗ КАВЫЧЕК) <br/> 4) Введите в коментарий Ваш ID: который указан выше, и НИК. <br/> 5) ☆ Оплатите ☆  <br/></span>';

echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Золото поступит на счет в течении 3 часов';
echo'</div>';
echo'</div>';

echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo'<a href="/payment"><img src="/style/images/ico/png/back.png" width="15px" height="15px" alt=""/> <span class="gray"><big>Вернуться назад</big></span></a>';
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>