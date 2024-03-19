<?php
require_once '../system/system.php';
$head = 'Банковская карта (Россия)';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div style="padding: 3px; color: #bfbfbf;">';
echo'Переведите средства на один из кошельков <b>с указанием вашего ID и НИКА</b>.<br/>';

echo'Далее напишите Администрации сообщение с текстом: <b>Покупка золота через Банковскую карту</b>.<br/>';
echo'<br/>';

echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>100 золота => 10 рублей.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>1000 золота => 100 рублей.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>5000 золота => 500 рублей.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>10000 золота => 1000 рублей.<br/>';
echo'<br/>';
/*
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>300 золота => 4 гривны.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>3000 золота => 40 гривен.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>15000 золота => 200 гривен.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>30000 золота => 400 гривен.<br/>';
*/
echo"<img src='/style/images/user/user.png' alt=''/> Ваш ID: $user[id]</span><br/>";
echo'<br/>';
echo"Доступные кошельки:";

echo'<span class="yellow"><br/>';
echo'<img src="/style/images/body/webmoney_ru.png" alt=""/> 4890 4945 8602 2865<br/>';
echo'</span>';
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Золото поступит на счет в течении 5 минут';
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