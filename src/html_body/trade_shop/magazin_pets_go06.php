<?php
require_once '../../system/system.php';
$head = 'Магазин питомцев';
echo only_reg();
echo pets_level();
require_once H.'system/head.php';

echo'<div class="page">';
echo'<img src="/style/images/location/trade_shop/pets.jpg" class="img"/>';

#-Выборка данных питомцев-#
$sel_pets = $pdo->query("SELECT * FROM `pets` WHERE `no_magaz` = 0 ORDER BY `id`");
while($pets = $sel_pets->fetch(PDO::FETCH_LAZY))  
{
#-Вывод питомцев-#
echo'<div class="t_max">';
echo'<img src="'.$pets['images'].'" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>'.$pets['name'].'</b><br/> <span class="t_param"><img src="/style/images/user/sila.png" alt=""/>'.$pets['sila'].' <img src="/style/images/user/zashita.png" alt=""/>'.$pets['zashita'].'  <img src="/style/images/user/health.png" alt=""/>'.$pets['health'].'</span><br/>'.pets_ability($pets['id'], 1).'</div>';
echo'</div>';
#-Достаточно золота или нет-#
if($user['gold'] >= $pets['gold']){
echo"<a href='/pets_buy?act=buy&pets_id=$pets[id]' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/>$pets[gold]</a>";
}else{
echo"<div class='button_red_a'>Купить <img src='/style/images/many/gold.png' alt=''/>$pets[gold]</div>";
}
echo'<div style="padding-top: 3px;"></div>';	
}

echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/trade_shop'><img src='/style/images/body/back.png' alt=''/> Торговая лавка</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>