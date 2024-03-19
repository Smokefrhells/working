<?php
#-Права админа-#
function admin() {
global $user;
if($user['prava'] != 1){
header('Location: /');
$_SESSION['err'] = 'Вы не админ!';
exit();
}
}

#-Права модератора-#
function moder() {
global $user;
if($user['prava'] != 2){
header('Location: /');
$_SESSION['err'] = 'Вы не модератор!';
exit();
}
}

#-Права админа или модератора-#
function admod() {
global $user;
if($user['prava'] != 1 and $user['prava'] != 2 and $user['prava'] != 3){
header('Location: /');
$_SESSION['err'] = 'Вы не страж!';
exit();
}
}
?>