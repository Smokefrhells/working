<?php
#-Изображение в зависимости от пола игрока-#
function avatar_img($avatar, $pol)
{
#-Закачан аватар или нет-#
    if ($avatar == '') {
        if ($pol == 1) {
            $img = '/style/images/user/img_1.jpg';
        }
        if ($pol == 2) {
            $img = '/style/images/user/img_2.jpg';
        }
        if ($pol == 0) {
            $img = '/style/images/user/img_0.jpg';
        }
    } else {
        $img = '/style/avatar/' . $avatar . '';
    }
    return $img;
}

#-Изображение в зависимости от пола игрока-#
function avatar_img_min($avatar, $pol)
{

#-Закачан аватар или нет-#
    if ($avatar == '' || !is_file($_SERVER['DOCUMENT_ROOT'] . '/style/avatar/' . $avatar)) {
        if ($pol == 1) {
            $img = '/style/images/user/img_min_1.jpg';
        }
        if ($pol == 2) {
            $img = '/style/images/user/img_min_2.jpg';
        }
        if ($pol == 0) {
            $img = '/style/images/user/img_min_0.jpg';
        }
    } else {
        $img = '/style/avatar/' . $avatar . '';
    }
    return $img;
}

#-Аватар клана-#
function avatar_clan($avatar)
{
#-Закачан аватар или нет-#
    if ($avatar == '') {
        $img = '/style/images/clan/avatar.png';
    } else {
        $img = '/style/avatar_clan/' . $avatar . '';
    }
    return $img;
}

?>