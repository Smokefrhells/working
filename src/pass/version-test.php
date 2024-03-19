<?php
require "lib/password.php";
$pass_otkrut = '1234567891';
$baza = password_hash($pass_otkrut, PASSWORD_DEFAULT);
if(password_verify($pass_otkrut,$baza)){
echo"Да $baza ";
}else{
echo'нет';
}