<?php
#-Функция генирации hash кода-#
function hash_cod($hash){
$eng = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
$size = 60;
$q_size = strlen($eng);
$hash_rand ='';for($i=0;$i<$size;$i++){$hash_rand.=$eng[rand(0,$q_size-1)];
$hash = md5(md5($hash_rand));
return $hash;
}
}
?>