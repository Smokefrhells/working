<?php
require_once '../../system/system.php';
$head = 'Выдача питомца';
echo only_reg();
echo admin();
require_once H.'system/head.php';
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `level` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $_GET['id']));
if($sel_users->rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
echo'<div class="body_list">';

echo'<div class="info_list">';
echo"<img src='/style/images/user/user.png' alt=''/>Выдача игроку: $all[nick]";
echo'</div>';	
echo'<div class="line_1"></div>';

echo'<div class="menulist">';
echo'<li><a href="/admin_pets_act?act=get&pets_id=1&id='.$all['id'].'"><img src="/style/images/body/pets.png" alt=""/> Мышь</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/admin_pets_act?act=get&pets_id=1&id='.$all['id'].'"><img src="/style/images/body/pets.png" alt=""/> Волк</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/admin_pets_act?act=get&pets_id=3&id='.$all['id'].'"><img src="/style/images/body/pets.png" alt=""/> Лев</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/admin_pets_act?act=get&pets_id=4&id='.$all['id'].'"><img src="/style/images/body/pets.png" alt=""/> Змееглав</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/admin_pets_act?act=get&pets_id=5&id='.$all['id'].'"><img src="/style/images/body/pets.png" alt=""/> Гелаус</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/hero/'.$all['id'].'"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png">Игрок не найден!</div>';		
}
require_once H.'system/footer.php';
?>