<?php
require_once '../../system/system.php';
only_reg();
$id = check($_GET['id']);
if (empty($_GET['id']))
    $error = 'Ошибка!';
if (!isset($_GET['id']))
    $error = 'Ошибка!';
if (!isset($error)) {
    $sel_forum_t = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :id");
    $sel_forum_t->execute([':id' => $id]);
    if ($sel_forum_t->rowCount() != 0) {
        $topic = $sel_forum_t->fetch(PDO::FETCH_LAZY);
    } else {
        header('Location: /forum_razdel');
        $_SESSION['err'] = 'Топик не найден!';
        exit();
    }
} else {
    header('Location: /forum_razdel');
    $_SESSION['err'] = $error;
    exit();
}
$head = 'Топик';
require_once H . 'system/head.php';
require_once H . 'avenax/Base.php';
$forPage = new Base();

#-Навигация по форуму-#
$sel_f_nav = $pdo->prepare("SELECT * FROM `forum_navigation` WHERE `user_id` = :user_id");
$sel_f_nav->execute([':user_id' => $user['id']]);
if ($sel_f_nav->rowCount() != 0) {
    $nav_f = $sel_f_nav->fetch(PDO::FETCH_LAZY);

    $forPage->setPage($nav_f, $topic['id']);

    #-Если не читали топик записываем-#
    if (!preg_match('/\{' . $topic['id'] . '\}/', $nav_f['topic'])) {
        $upd_nav_f = $pdo->prepare("UPDATE `forum_navigation` SET `topic` = :topic WHERE `user_id` = :user_id");
        $upd_nav_f->execute([':topic' => $nav_f['topic'] . '{' . $topic['id'] . '}', ':user_id' => $user['id']]);
    }
}



#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
$sel_users->execute([':user_id' => $topic['user_id']]);
#-Если есть такой игрок-#
if ($sel_users->rowCount() != 0) {
    $all = $sel_users->fetch(PDO::FETCH_LAZY);
}
echo '<div class="line_1_m"></div>';
echo '<div class="body_list">';
echo '<div class="menulist">';
#-Если игрок создал топик или есть соответ. права-#
if ($user['id'] == $topic['user_id'] or $user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3) {
    echo "<a href='/forum_topic/read/$topic[id]?panel_forum=on' id='button_p_f'><span class='gray'><img src='/style/images/forum/topic_setting.png' alt=''/>$topic[title]</span></a>";
} else {
    echo '<div style="padding: 5px;">';
    echo "<span class='gray'><img src='/style/images/forum/topic.png' alt=''/>$topic[title]</span>";
    echo '</div>';
}
echo '<div class="line_1"></div>';
#-ПАНЕЛЬ МОДЕРИРОВАНИЯ-#
//Параметры
$edit = '<li><a href="/forum_topic/edit/' . $topic['id'] . '"><img src="/style/images/forum/topic_red.png" alt=""/>Редактировать</a></li>';
$close = '<li><a href="/forum_act?act=close&topic=' . $topic['id'] . '"><img src="/style/images/forum/topic_close.png" alt=""/>Закрыть</a></li>';
$open = '<li><a href="/forum_act?act=close&topic=' . $topic['id'] . '"><img src="/style/images/forum/topic_open.png" alt=""/>Открыть</a></li>';
$attach = '<li><a href="/forum_act?act=fix&topic=' . $topic['id'] . '"><img src="/style/images/forum/topic_attach.png" alt="З"/>Закрепить</a></li>';
$no_attach = '<li><a href="/forum_act?act=fix&topic=' . $topic['id'] . '"><img src="/style/images/forum/topic_no_attach.png" alt=""/>Открепить</a></li>';
$delete = '<li><a href="/forum_topic/read/' . $topic['id'] . '?t=del"><img src="/style/images/forum/topic_del.png" alt=""/>Удалить</a></li>';
#-Показываем панель без Javascript-#
if (isset($_GET['panel_forum'])) {
    echo $edit;
    if ($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3) {
        echo '<div class="line_1"></div>';
        if ($topic['close'] == 0) {
            echo $close;
        } else {
            echo $open;
        }
        echo '<div class="line_1"></div>';
        if ($topic['verh'] == 0) {
            echo $attach;
        } else {
            echo $no_attach;
        }
        echo '<div class="line_1"></div>';
        if (isset($_GET['t'])) {
            if ($_GET['t'] == 'del') {
                echo '<li><a href="/forum_act?act=delete&topic=' . $topic['id'] . '"><img src="/style/images/forum/topic_del.png" alt=""/>Подтвердить удаление</a></li>';
            }
        } else {
            echo $delete;
        }
    }
    echo '<div class="line_1"></div>';
    echo "<li><a href='/forum_topic/read/$topic[id]'><img src='/style/images/body/error.png' alt=''>Скрыть</a></li>";
    echo '<div class="line_1"></div>';
}
#-Панель для javascript-#
echo '<div id="panel_forum">';
echo $edit;
if ($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3) {
    echo '<div class="line_1"></div>';
    if ($topic['close'] == 0) {
        echo $close;
    } else {
        echo $open;
    }
    echo '<div class="line_1"></div>';
    if ($topic['verh'] == 0) {
        echo $attach;
    } else {
        echo $no_attach;
    }
    echo '<div class="line_1"></div>';
    if (isset($_GET['t'])) {
        if ($_GET['t'] == 'del') {
            echo '<li><a href="/forum_act?act=delete&topic=' . $topic['id'] . '"><img src="/style/images/forum/topic_del.png" alt=""/>Подтвердить удаление</a></li>';
        }
    } else {
        echo $delete;
    }
}
echo '<div class="line_1"></div>';
echo '</div>';
echo '</div>';
echo '</div>';
#-ВЫВОД ТОПИКА-#
echo '<div class="page">';
echo '<div style="padding: 3px; white-space: pre-wrap;">';
echo "<a href='/hero/$all[id]'> <span class='user_nick'> $all[nick]</span></a>";
echo "<div style='font-size: 13px; float: right;'><span class='white'> " . vremja($topic['time']) . "</span></div><br/>";
echo "<span class='gray'>" . msg(bbcode($topic['msg'])) . "</span><br/>";
echo '<div style="padding-top: 3px;"></div>';
if ($topic['edit'] > 0) {
    $ed = $topic['edit'] . ' ' . true_wordform($topic['edit'], 'раз', 'раза', 'раз');
    echo "<div style='font-size: 13px;'><span class='white'>Отредактировано: $ed</span></div>";
}
echo '</div>';
echo '</div>';
echo '<div class="line_1_m"></div>';

#-КОММЕНТАРИИ-#
#-Считаем количество комментарий-#
$sel_forum_comm = $pdo->prepare("SELECT COUNT(*) FROM `forum_comment` WHERE `topic_id` = :topic_id");
$sel_forum_comm->execute([':topic_id' => $topic['id']]);
$amount = $sel_forum_comm->fetch(PDO::FETCH_LAZY);
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
$sel_comment = $pdo->prepare("SELECT * FROM `forum_comment` WHERE `topic_id` = :topic_id ORDER BY `time` LIMIT $start, $num");
$sel_comment->execute([':topic_id' => $topic['id']]);
#-Если есть записи-#
if ($sel_comment->rowCount() != 0) {
    #-Пишем сколько комментариев всего-#
    echo '<div class="body_list">';
    echo '<div class="error_list">';
    echo '<img src="/style/images/body/comm.png" alt="">Комментариев: ' . $amount[0] . '';
    echo '</div>';
    echo '<div class="line_1_m"></div>';
    echo '</div>';
    #-ВЫВОД КОММЕНТАРИЙ-#
    echo '<div style="padding-top: 5px;"></div>';
    echo '<div class="body_list">';
    while ($comment = $sel_comment->fetch(PDO::FETCH_LAZY)) {
        echo '<div class="line_1"></div>';
        #-Выборка игрока который оставил комментарий-#
        $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
        $sel_users->execute([':user_id' => $comment['user_id']]);
        $all = $sel_users->fetch(PDO::FETCH_LAZY);
        #-Показываем комментарии-#
        echo '<div style="padding: 3px;">';
        echo "<img src='" . avatar_img_min($all['avatar'], $all['pol']) . "' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><a href='/hero/$all[id]'><span class='user_nick'>$all[nick]</span></a> ";
        if ($comment['user_id'] != $user['id']) {
            echo "<a href='/forum_topic/read/$topic[id]?nick=$all[nick]'><span id='otv' class='$all[nick]'>[Отв.]</span></a> ";
        }
        if ($comment['user_id'] == $user['id'] and $comment['time'] > time() - 600 and $user['ban'] == 0) {
            echo "<a href='/forum_topic/edit_comment/$comment[id]'><span style='color: #666666; font-size: 13px;'>[Ред.]</span></a> ";
        }
        if ($user['prava'] == 1 or $user['prava'] == 2) {
            if ($comment['user_id'] != $user['id']) {
                if ($all['ban'] == 0) {
                    echo "<a href='/moder_ban?ank_id=$all[id]&redicet=$_SERVER[REQUEST_URI]'><span style='color: #666666; font-size: 13px;'>[Молч.]</span></a> ";
                } else {
                    echo "<a href='/ban_act?act=ban&user_id=$all[id]&redicet=$_SERVER[REQUEST_URI]'><span style='color: #666666; font-size: 13px;'>[Не молч.]</span></a> ";
                }
            }
            echo "<a href='/forum_act?act=del_com&comment=$comment[id]&topic=$topic[id]'><span style='color: #666666; font-size: 13px;'>[X]</span></a> ";
        }
        echo "<div style='float: right; font-size: 12px; color: #666666;'>" . vremja($comment['time']) . "</div><br/>";
        if ($all['premium'] != 0) {
            $color = 'orange';
        }
        if ($all['prava'] == 2) {
            $color = 'blue';
        }
        if ($all['prava'] == 3) {
            $color = 'yellow';
        }
        if ($all['prava'] == 1) {
            $color = 'red';
        }
        if ($all['prava'] == 0 and $all['premium'] == 0) {
            $color = 'gray';
        }
        echo "<span class='$color'>" . msg($comment['comment']) . "</span><br/>";
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {//Если нет комментарий
    echo '<div class="body_list">';
    echo '<div class="error_list">';
    echo '<img src="/style/images/body/error.png" alt="">Нет комментариев!';
    echo '</div>';
    echo '</div>';
}

#-Вывод постраничной навигации-#
if ($posts > $num) {
    $action = '';
    echo '<div class="body_list">';
    echo '<div class="line_1"></div>';
    $z = pages($posts, $total, $action);
    echo '</div>';
}
#-Форма комментирования-#
if ($topic['close'] == 0 and $user['ban'] == 0 and $user['level'] >= 30 and $user['save'] == 1) {
    echo '<div class="line_1"></div>';
    if (isset($_GET['nick'])) {
        $nick = check($_GET['nick']);
        $otv = "$nick, ";
    }
    #-Страница-#
    if (isset($_GET['page'])) {
        $page = check($_GET['page']);
    } else {
        $page = 1;
    }
    echo '<div style="padding-top: 5px;"></div>';
    echo '<center>';
    echo '<span class="white">Комментарий:</span>';
    echo '<form method="post" action="/forum_comment?act=comm&topic=' . $topic['id'] . '&page=' . $page . '">';
    echo "<textarea class='input_form' type='text' name='comment' id='mail' required>$otv</textarea><br/>";
    echo '</center>';
    echo '<input class="button_i_mini" name="submit" type="submit"  value="Отправить"/>';
    echo '</form>';
    echo '<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '</center>';
    echo '</div>';
} else {
    echo '<div class="line_1"></div>';
    echo '<div class="error_list">';
    echo '<img src="/style/images/body/error.png" alt=""/>Вы не можете оставлять комментарии!';
    echo '</div>';
}
#-Смайлы-#
echo '<div id="panel_smiles">';
echo '<div class="line_1_m"></div>';
echo smiles_kolobok();
echo '</div>';
echo '</div>';
#-Меню-#`
echo '<div class="body_list">';
echo '<div class="menulist">';
echo '<div class="line_1"></div>';
echo "<li><a href='/smiles'><img src='/style/images/chat/smiles_b.png' alt=''/> Смайлы</a></li>";
echo '<div class="line_1"></div>';
echo "<li><a href='/moderator'><img src='/style/images/chat/moder.png' alt=''/> Модераторы</a></li>";
echo '<div class="line_1"></div>';
echo "<li><a href='/forum_topic/$topic[razdel_id]'><img src='/style/images/body/back.png' alt=''/> Топики</a></li>";
echo '</div>';
require_once H . 'system/footer.php';
?>