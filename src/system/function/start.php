<?php
#-Охота-#
function hunting_campaign(){
global $user;
if($user['start'] == 0){
header('Location: /');
}
}
#-При покупке снаряжения-#
function trade_shop_campaign(){
global $user;
if($user['start'] < 3){
header('Location: /');
}
}
#-При надевании снаряжения-#
function armor_campaign(){
global $user;
if($user['start'] < 4){
header('Location: /');
}
}
#-Тренировка-#
function training_campaign(){
global $user;
if($user['start'] < 5){
header('Location: /');
}
}
#-Боссы-#
function boss_campaign(){
global $user;
if($user['start'] < 6){
header('Location: /');
}
}
#-Сохранение-#
function save_campaign(){
global $user;
if($user['start'] < 7){
header('Location: /');
}
}
?>