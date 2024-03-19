<?php
require_once '../../system/system.php';
$head = 'База знаний';
if($_GET['sort'] == 'location') $head = 'Игровые локации';
if($_GET['sort'] == 'clan') $head = 'Кланы';
if($_GET['sort'] == 'user') $head = 'Игрок';
if($_GET['sort'] == 'table_exp') $head = 'Таблицы опыта';
if($_GET['sort'] == 'other') $head = 'Другое';
if($_GET['sort'] == 'faq') $head = 'ЧаВо';
require_once H.'system/head.php';
echo'<div class="page">';

if(!isset($_GET['sort'])){
echo'<center>';
echo'<div style="padding-top:5px;"></div>';
echo'<a href="/knowledge_basa?sort=location" class="button_green_a">Игровые локации</a>';
echo'<div style="padding-top:3px;"></div>';  
echo'<a href="/knowledge_basa?sort=clan" class="button_green_a">Кланы</a>';
echo'<div style="padding-top:3px;"></div>';  
echo'<a href="/knowledge_basa?sort=user" class="button_green_a">Игрок</a>';
echo'<div style="padding-top:3px;"></div>';  
echo'<a href="/knowledge_basa?sort=table_exp" class="button_green_a">Таблицы опыта</a>';
echo'<div style="padding-top:3px;"></div>';  
echo'<a href="/knowledge_basa?sort=other" class="button_green_a">Другое</a>';
echo'<div style="padding-top:3px;"></div>';  
echo'<a href="/knowledge_basa?sort=faq" class="button_green_a">ЧаВо</a>';
echo'<div style="padding-top:5px;"></div>';                                                      
echo'</center>';																	 
}
                                                       #-Показываем справку в зависимости от выбора-#
#-Игровые локации-#
if(isset($_GET['sort']) and $_GET['sort'] == 'location'){
require_once 'location.php';	
}
#-Кланы-#
if(isset($_GET['sort']) and $_GET['sort'] == 'clan'){
require_once 'clan.php';	
}
#-Игрок-#
if(isset($_GET['sort']) and $_GET['sort'] == 'user'){
require_once 'user.php';	
}
#-Таблицы-#
if(isset($_GET['sort']) and $_GET['sort'] == 'table_exp'){
require_once 'table_exp.php';	
}
#-Другое-#
if(isset($_GET['sort']) and $_GET['sort'] == 'other'){
require_once 'other.php';	
}
#-ЧаВо-#
if(isset($_GET['sort']) and $_GET['sort'] == 'faq'){
require_once 'faq.php';	
}															 
echo'</div>';
require_once H.'system/footer.php';
?>