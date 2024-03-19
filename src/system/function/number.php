<?php
function true_wordform($num, $form_for_1, $form_for_2, $form_for_5){
	$num = abs($num) % 100; // берем число по модулю и сбрасываем сотни (делим на 100, а остаток присваиваем переменной $num)
	$num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
	if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
		return $form_for_5;
	if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
		return $form_for_2;
	if ($num_x == 1) // иначе если оканчивается на 1
		return $form_for_1;
	return $form_for_5;
}


function num_format($i) {
if($i >= 0 && $i < 1){
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = NULL;
}
if($i >= 1 && $i < 1000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = NULL;
}
if($i >= 1000 && $i < 1000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 'k';
}
if($i >= 1000000 && $i < 1000000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 'm';
}
if($i >= 1000000000 && $i < 1000000000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 'b';
}
if($i >= 1000000000000 && $i < 1000000000000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 't';
}
if($i >= 1000000000000000 && $i < 1000000000000000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 'q';
}
if($i >= 1000000000000000000 && $i < 1000000000000000000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 'u';
}
if($i >= 1000000000000000000000 && $i < 1000000000000000000000000) {
$i = number_format($i, 0, '', '.');
$i = round($i,1).'';
$b = 'x';
}
if ($i == 1000)$i =1;
return $i.$b;
}
/*function num_format($number){
$number = number_format($number, 0, '', '\'');
return $number;
}*/
?>