<?php
function save(){
global $user;
if($user['save'] == 0){
header('Location: /');
$_SESSION['err'] = 'Сохраните игрока!';
exit();
}
}
function no_save(){
global $user;
if($user['save'] == 1){ 
header('Location: /');
}
}
?>