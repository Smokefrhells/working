<?php
function vremja($time=NULL)
{
global $user;
if ($time==NULL)$time=time();
if (isset($user))$time=$time+$user['set_timesdvig']*60*60;
$timep="".date("j M в H:i:s", $time)."";
$time_p[0]=date("j n", $time);
$time_p[1]=date("H:i", $time);
if ($time_p[0]==date("j n"))$timep=date("H:i", $time);
if (isset($user)){
if ($time_p[0]==date("j n", time()+$user['set_timesdvig']*60*60))$timep=date("H:i:s", $time);
if ($time_p[0]==date("j n", time()-60*60*(24-$user['set_timesdvig'])))$timep="Вчера в $time_p[1]";}
else{
if ($time_p[0]==date("j n"))$timep=date("H:i:s", $time);
if ($time_p[0]==date("j n", time()-60*60*24))$timep="Вчера в $time_p[1]";}
$timep=str_replace("Jan","Янв",$timep);
$timep=str_replace("Feb","Фев",$timep);
$timep=str_replace("Mar","Марта",$timep);
$timep=str_replace("May","Мая",$timep);
$timep=str_replace("Apr","Апр",$timep);
$timep=str_replace("Jun","Июн",$timep);
$timep=str_replace("Jul","Июл",$timep);
$timep=str_replace("Aug","Авг",$timep);
$timep=str_replace("Sep","Сент",$timep);
$timep=str_replace("Oct","Окт",$timep);
$timep=str_replace("Nov","Нояб",$timep);
$timep=str_replace("Dec","Дек",$timep);
return $timep;
}


#-Функция подсчета оставшегося времени без секунд-#
function timer($param){
$day = floor($param/86400);
$hour =  ($param/3600) - $day*24;
$minut = $param/60%60;
if($day >= 1){
$d = ''.(int)($day).' дн. ';
}
if($hour >= 1){
$h = ''.(int)($hour).' час. ';
}
if($minut != 0 and $day == 0){
$m = ''.(int)($minut).' мин.';
}
$ost_timer = "$d$h$m";
return 	$ost_timer;
}

#-Функция подсчета оставшегося времени без секунд укороченная-#
function timer_u($param){
$day = floor($param/86400);
$hour =  ($param/3600) - $day*24;
$minut = $param/60%60;
if($day >= 1){
$d = ''.(int)($day).'д';
}
if($hour >= 1){
$h = ''.(int)($hour).'ч';
}
if($minut != 0 and $day == 0){
$m = ''.(int)($minut).'м';
}
$ost_timer = "$d$h$m";
return 	$ost_timer;
}

#-Функция подсчета оставшегося времени с секундами-#
function timers($param){
$day = floor($param/86400);
$hour =  ($param/3600) - $day*24;
$minut = $param/60%60;
$seconds = $param%60;
if($day >= 1){
$d = ''.(int)($day).' дн. ';
}
if($hour >= 1){
$h = ''.(int)($hour).' час. ';
}
if($minut != 0 and $day == 0){
$m = ''.(int)($minut).' мин. ';
}
if($seconds != 0 and $day == 0 and $h == 0){
$s = ''.(int)($seconds).' сек. ';	
}
$ost_timer = "$d$h$m$s";
return 	$ost_timer;
}

#-Функция подсчета оставшегося времени с секундами в строке-#
function timers_mini($param){
$day = floor($param/86400);
$hour =  ($param/3600) - $day*24;
$minut = $param/60%60;
$seconds = $param%60;
if($day >= 1){
$d = ''.(int)($day).':';
}
if($hour >= 1){
$h = ''.(int)($hour).':';
}
if($day == 0){
$m = ''.(int)($minut).'';
}
if($seconds != 0 and $day == 0 and $h == 0){
$s = ':'.(int)($seconds).'';	
}
$ost_timer = "$d$h$m$s";
return 	$ost_timer;
}

#-Подсчет сколько прошло времени с момента действия времени-#
function vremja_p($param){
$time = time();
$day = floor(($time-$param)/86400);
$hour =  (($time-$param)/3600) - $day*24;
$minut = ($time-$param)/60%60;
if($day >= 1){$d = ''.(int)($day).'д. ';}
if($hour >= 1){$h = ''.(int)($hour).'ч. ';}
if($day == 0){$m = ''.(int)($minut).'м.';}
$vrm_t = "$d$h$m$s";
return $vrm_t;
}
?>