<?php
#-Изображение в зависимости онлайн игрок или нет-#
function online($on_time){
$time = time() - 3600;
if($on_time > $time){
$img = '<img src="/style/images/user/online.png" alt=""/>';
}else{
$img = '<img src="/style/images/user/offline.png" alt=""/>';
}
return $img;
}
?>