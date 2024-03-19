<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$id = check($_GET['id']);
if (empty($_GET['id']))
    $error = 'Ошибка!';
if (!isset($_GET['id']))
    $error = 'Ошибка!';
if (!isset($error)) {
    $sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :id");
    $sel_clan->execute(array(':id' => $id));
    if ($sel_clan->rowCount() != 0) {
        $clan = $sel_clan->fetch(PDO::FETCH_LAZY);
    } else {
        header('Location: /clan');
        $_SESSION['err'] = 'Клан не найден!';
        exit();
    }
} else {
    header('Location: /clan');
    $_SESSION['err'] = $error;
    exit();
}
$head = 'Состав клана';
require_once H . 'system/head.php';
#-Навигация-#
$prava = '<a href="/clan/users/' . $clan['id'] . '?type=1" style="text-decoration:none;"><span class="body_sel">' . (($_GET['type'] == 1 or $_GET['type'] > 6 or !isset($_GET['type'])) ? "<img src='/style/images/clan/crown.png' alt=''/><b>Права</b>" : "<img src='/style/images/clan/crown.png' alt=''/>Права") . '</span></a>';
$exp = '<a href="/clan/users/' . $clan['id'] . '?type=2" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 2 ? "<img src='/style/images/user/exp.png' alt=''/><b>Опыт</b>" : "<img src='/style/images/user/exp.png' alt=''/>Опыт") . '</span></a>';
$level = '<a href="/clan/users/' . $clan['id'] . '?type=3" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 3 ? "<img src='/style/images/user/level.png' alt=''/><b>Уровень</b>" : "<img src='/style/images/user/level.png' alt=''/>Уровень") . '</span></a>';
$silver = '<a href="/clan/users/' . $clan['id'] . '?type=4" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 4 ? "<img src='/style/images/many/silver.png' alt=''/><b>Серебро</b>" : "<img src='/style/images/many/silver.png' alt=''/>Серебро") . '</span></a>';
$gold = '<a href="/clan/users/' . $clan['id'] . '?type=5" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 5 ? "<img src='/style/images/many/gold.png' alt=''/><b>Золото</b>" : "<img src='/style/images/many/gold.png' alt=''/>Золото") . '</span></a>';
$data = '<a href="/clan/users/' . $clan['id'] . '?type=6" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 6 ? "<img src='/style/images/body/time_reg.png' alt=''/><b>Дата</b>" : "<img src='/style/images/body/time_reg.png' alt=''/>Дата") . '</span></a>';

#-Тип рейтинга-#
$type = check($_GET['type']);
if (empty($type) or $type > 6) {
    $type = 1;
}
$page = check($_GET['page']);
if (empty($page)) {
    $page = 1;
}

#-Вывод-#
echo '<div class="body_list">';
echo '<div style="padding: 5px;">';
echo '' . $prava . '' . $exp . '' . $level . '' . $silver . '' . $gold . '' . $data . '';
echo '</div>';
echo '</div>';

#-Количество участников клана-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;
$page = $_GET['page'];
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;
$page = intval($page);
if (empty($page) or $page < 0)
    $page = 1;
if ($page > $total)
    $page = $total;
$start = $page * $num - $num;

#-Права-#
if ($type == 1 or $type > 6 or !isset($_GET['type'])) {
    $sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY clan_users.prava DESC LIMIT $start, $num");
    $sel_clan_u->execute(array(':clan_id' => $clan['id']));
}
#-Опыт-#
if ($type == 2) {
    $sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY clan_users.exp DESC LIMIT $start, $num");
    $sel_clan_u->execute(array(':clan_id' => $clan['id']));
}
#-Уровень-#
if ($type == 3) {
    $sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY users.level DESC LIMIT $start, $num");
    $sel_clan_u->execute(array(':clan_id' => $clan['id']));
}
#-Серебро-#
if ($type == 4) {
    $sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY clan_users.silver DESC LIMIT $start, $num");
    $sel_clan_u->execute(array(':clan_id' => $clan['id']));
}
#-Золото-#
if ($type == 5) {
    $sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY clan_users.gold DESC LIMIT $start, $num");
    $sel_clan_u->execute(array(':clan_id' => $clan['id']));
}
#-Дата-#
if ($type == 6) {
    $sel_clan_u = $pdo->prepare("SELECT * FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY clan_users.time LIMIT $start, $num");
    $sel_clan_u->execute(array(':clan_id' => $clan['id']));
}

echo '<div class="body_list">';
echo '<div class="menulist">';

while ($clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY)) {
#-Выборка данных игрока-#
    $sel_users = $pdo->prepare("SELECT `id` , `nick`, `level`, `time_online`, `avatar`, `pol` FROM `users` WHERE `id` = :user_id");
    $sel_users->execute(array(':user_id' => $clan_u['user_id']));
    $all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Определяем какие права у участкика-#
    if ($clan_u['prava'] == 0) {
        $prava = 'Новичок';
    }
    if ($clan_u['prava'] == 1) {
        $prava = 'Боец';
    }
    if ($clan_u['prava'] == 2) {
        $prava = 'Ветеран';
    }
    if ($clan_u['prava'] == 3) {
        $prava = 'Старейшина';
    }
    if ($clan_u['prava'] == 4) {
        $prava = 'Основатель';
    }

    echo '<div class="line_1"></div>';
    echo '<div class="menulitl">';
    echo "<li><a href='/hero/$all[id]'><img src='" . avatar_img_min($all['avatar'], $all['pol']) . "' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>" . online($all['time_online']) . " <span class='menulitl_name'>" . $all['nick'] . " </span><br/><div class='menulitl_param'>" . ($type != 6 ? "<img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/many/gold.png' alt=''/>$clan_u[gold] <img src='/style/images/many/silver.png' alt=''/>$clan_u[silver] <img src='/style/images/user/exp.png' alt=''/>$clan_u[exp]" : "" . vremja($clan_u['time']) . " (" . vremja_p($clan_u['time']) . ")") . "<br/><img src='/style/images/clan/crown.png' alt=''/>$prava</div></div></a></li>";
    echo '</div>';

#-Если клан наш-#
    $sel_clan_me = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
    $sel_clan_me->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
    $clan_me = $sel_clan_me->fetch(PDO::FETCH_LAZY);
#-Если есть права-#
    if (($clan_me['prava'] == 4 or $clan_me['prava'] == 3) and $clan_u['prava'] != 4) {
        if ($clan_u['user_id'] != $user['id']) { //Только если не наш id
#-Кнопка Изменить-#
            if (!isset($_GET['ank_id'])) {
                if ($clan_me['prava'] == 3) {
                    if ($clan_u['prava'] < 3) {
                        echo '<div class="line_1"></div>';
                        echo '<li><a href="/clan/users/' . $clan['id'] . '' . (isset($_GET['page']) ? '?page=' . $_GET['page'] . '' : '?page=1') . '&type=' . $type . '&ank_id=' . $clan_u['user_id'] . '"><img src="/style/images/clan/edit.png" alt=""/> Изменить</a></li>';
                    }
                } else {
                    echo '<div class="line_1"></div>';
                    echo '<li><a href="/clan/users/' . $clan['id'] . '' . (isset($_GET['page']) ? '?page=' . $_GET['page'] . '' : '?page=1') . '&type=' . $type . '&ank_id=' . $clan_u['user_id'] . '"><img src="/style/images/clan/edit.png" alt=""/> Изменить</a></li>';
                }
            } else {
                $ank_id = check($_GET['ank_id']);
#-Проверяем что игрок состоит именно в этом клане-#	
                $sel_clan_red = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
                $sel_clan_red->execute(array(':clan_id' => $clan['id'], ':user_id' => $ank_id));
                if ($sel_clan_red->rowCount() != 0) {
                    $clan_red = $sel_clan_red->fetch(PDO::FETCH_LAZY);
                    if ($clan_u['user_id'] == $clan_red['user_id']) {
                        if ($clan_me['prava'] == 4 or $clan_me['prava'] == 3) {
                            echo '<div class="line_1"></div>';
                            echo '<div class="page">';
                            echo '<center>';
                            echo '<form method="post" action="/clan_prava?act=go&clan_id=' . $clan['id'] . '&clan_user=' . $ank_id . '&page=' . $page . '&type=' . $type . '">';
                            echo '<select name="prava">';
                            echo '<option value="0">Новичок</option>';
                            echo '<option value="1">Боец</option>';
                            echo '<option value="2">Ветеран</option>';
                            echo '<option value="3">Старейшина</option>';
                            echo '</select>';
                            echo '<input class="button_green_i" name="submit" type="submit"  value="Сохранить"/>';
                            echo '<div style="padding-top: 5px;"></div>';
                            echo '</form>';
                            echo '</center>';
                            echo '</div>';
                        }
#-Какие права у игрока которого хочем исключить-#
                        if ($clan_me['prava'] == 3) {
                            if ($clan_red['prava'] < 3) {
                                echo '<div class="line_1"></div>';
                                echo '<li><a href="/clan_exclude?act=del&clan_id=' . $clan['id'] . '&clan_user=' . $ank_id . '"><img src="/style/images/body/error.png" alt=""/> Исключить</a></li>';
                            }
                        } else {
                            echo '<div class="line_1"></div>';
                            echo '<li><a href="/clan_exclude?act=del&clan_id=' . $clan['id'] . '&clan_user=' . $ank_id . '"><img src="/style/images/body/error.png" alt=""/> Исключить</a></li>';
                        }
#-Передача прав основателя-#
                        if ($clan_me['prava'] == 4) {
                            if ($_GET['prava'] == 'ok') {
                                echo '<div class="line_1"></div>';
                                echo '<li><a href="/clan_prava?act=osnova&clan_id=' . $clan['id'] . '&clan_user=' . $ank_id . '&page=' . $page . '&type=' . $type . '"><img src="/style/images/body/ok.png" alt=""/> Подтверждаю передачу прав</a></li>';
                            } else {
                                echo '<div class="line_1"></div>';
                                echo '<li><a href="/clan/users/' . $clan['id'] . '?ank_id=' . $ank_id . '&prava=ok&page=' . $page . '&type=' . $type . '"><img src="/style/images/user/user.png" alt=""/> Передать права</a></li>';
                            }
                        }
                        echo '<div class="line_1"></div>';
                        echo '<li><a href="/clan/users/' . $clan['id'] . '&page=' . $page . '&type=' . $type . '"><img src="/style/images/body/error.png" alt=""/> Скрыть</a></li>';
                    }
                }
            }
        }
    }
}
echo '</div>';
echo '</div>';
#-Отображение постраничной навигации-#
if ($posts > $num) {
    $action = "&type=$type";
    echo '<div class="body_list">';
    echo '<div class="line_1"></div>';
    $z = pages($posts, $total, $action);
    echo '</div>';
}
require_once H . 'system/footer.php';
?>