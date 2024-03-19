<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
$head = 'Покупка золота';
require_once '../../system/head.php';
include_once __DIR__ . '/sett.php';
include_once __DIR__ . '/WapkassaClass.php';

echo'<div class="page">';

if (!empty($_GET['gold']) && !empty($wk_cena_gold[$_GET['gold']])) {
    try {
		
       // Инициализация класса с id сайта и секретным ключом
        $wapkassa = new WapkassaClass(WK_ID, WK_SECRET);

        // основные параметры - сумма и комментарий платежа
        $wapkassa->setParams($wk_cena_gold[$_GET['gold']], 'Покупка Золота ID ' . $user['id']);

        // допольнительные параметры в виде массива, необязательно
        $wapkassa->setParamsAdd(array(
            'user_id' => $user['id'],
            'type' => 'gold',
            'count' => $_GET['gold'],
        ));

        // получаем данные для генерации формы
        $formValue = $wapkassa->getValue();

        // генерируем форму
        echo '<form method="post" action="https://wapkassa.ru/merchant/payment2">';
        foreach ($formValue as $key => $value) {
			
            echo '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
		echo'<div style="padding-top: 3px;"></div>';
        echo'<button class="button_green_i"> Подтверждаю</button>';
		echo'<div style="padding-top: 3px;"></div>';
        echo '</form>';

    } catch (Exception $e){
        // вывод ошибки
        echo $e->getMessage();
        exit;
    }
}
/*if(!isset($_GET['gold'])){
    
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #cb862c;">';
echo'<center><img src="/style/images/body/gift.png" alt=""/> <b>В честь открытия 3 раза больше золота!</b> <img src="/style/images/body/gift.png" alt=""/></center>';
echo'Купи любое количество <img src="/style/images/many/gold.png" alt=""/>Золота и получи в 3 раза больше.';
echo'<br>';
echo'<br>';
echo'<div style="padding-top: 5px;"></div>';		
echo"<a href='?gold=3000' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt/><s>1000</s>  <img src='/style/images/many/gold.png' alt=''/>3000 за 100 руб.</a>";	
echo'<div style="padding-top: 3px;"></div>';	
echo"<a href='?gold=7500' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/><s>2500</s> <img src='/style/images/many/gold.png' alt=''/>7500 за 250 руб.</a>";	
echo'<div style="padding-top: 3px;"></div>';	
echo"<a href='?gold=15000' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/><s>5000</s> <img src='/style/images/many/gold.png' alt=''/>15000  за 500 руб.</a>";
echo'<div style="padding-top: 3px;"></div>';	
echo"<a href='?gold=30000' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/><s>10000</s>  <img src='/style/images/many/gold.png' alt=''/>30000 за 1000 руб.</a>";
echo'<div style="padding-top: 5px;"></div>';	
echo'<br>';
echo'<div class="menulist">';
//echo'<div class="line_1"></div>';
//echo'<div class="body_list">';
//echo'<div class="info_list">';
echo'<a href="/payment"><img src="/style/images/ico/png/back.png" width="15px" height="15px" alt=""/> <span class="gray"><big>Вернуться назад</big></span></a>';
echo'</div>';
echo'</div>';
echo'</div>';
    
    
}

*/

if(!isset($_GET['gold'])){
   
echo''; 
echo'<div style="padding-top: 5px;"></div>';		
echo"<a href='?gold=100' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt/> 100 за 10 руб.</a>";	
echo'<div style="padding-top: 5px;"></div>';		
echo"<a href='?gold=500' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt/> 500 за 50 руб.</a>";	
echo'<div style="padding-top: 5px;"></div>';		
echo"<a href='?gold=1000' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt/> 1000 за 100 руб.</a>";	
echo'<div style="padding-top: 3px;"></div>';	
echo"<a href='?gold=2500' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/> 2500 за 250 руб.</a>";	
echo'<div style="padding-top: 3px;"></div>';	
echo"<a href='?gold=5000' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/> 5000 за 500 руб.</a>";
echo'<div style="padding-top: 3px;"></div>';	
echo"<a href='?gold=10000' class='button_green_a'>Купить <img src='/style/images/many/gold.png' alt=''/> 10000 за 1000 руб.</a>";
echo'<div style="padding-top: 5px;"></div>';	
echo'<div style="padding-top: 5px;"></div>';	
echo'<br>';
echo'<div class="menulist">';
//echo'<div class="line_1"></div>';
//echo'<div class="body_list">';
//echo'<div class="info_list">';
echo'<a href="/payment"><img src="/style/images/ico/png/back.png" width="15px" height="15px" alt=""/> <span class="gray"><big>Вернуться назад</big></span></a>';
echo'</div>';
echo'</div>';
echo'</div>';

}

     if($_GET['gold']){
	 $sel_donat = $pdo->prepare("SELECT * FROM `donat` WHERE `gold` = :gold AND `user_id` = :user_id");
	 $sel_donat->execute(array(':gold' => $_GET['gold'], ':user_id' => $user['id']));
	 if($sel_donat->rowCount() == 0){	 
    $ins_donate = $pdo->prepare("INSERT INTO `donate` SET `user_id` = :user_id, `summa` = :summa, `gold` = :gold, `time` = :time");
    $ins_donate->execute(array(':user_id' => $user['id'], ':summa' => $_GET['gold']/10, ':gold' => $_GET['gold'], ':time' => time()));
	 }
	 } 
echo'</div>';
require_once '../../system/footer.php';
?>