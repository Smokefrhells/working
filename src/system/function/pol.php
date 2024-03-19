<?php
#-Маленькая картинка пола игрока-#
function pol($pol) {
if($pol == 1){
return $img = '<img src="/style/images/user/pol_1.png" alt=""/>';
}
if($pol == 2){
return $img = '<img src="/style/images/user/pol_2.png" alt=""/>';
}
if($pol == 0){
return $img = '<img src="/style/images/user/pol_0.png" alt=""/>';
}
}
?>