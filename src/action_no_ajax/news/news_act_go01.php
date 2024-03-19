<?php
require_once '../../system/system.php';
echo only_reg();
#-Права только админа-#
if ($user['prava'] == 1) {
#-Добавление новости-#
    switch ($act) {
        case 'add':
            if (isset($_POST['title']) and isset($_POST['msg'])) {
                $title = check($_POST['title']); //Заголовок
                $msg = check($_POST['msg']); //Сообщение
#-Проверяем данные-#
#-Заголовок-#
                if (mb_strlen($title) < 1)
                    $error = 'Пустой заголовок!';
#-Сообщение-#
                if (mb_strlen($msg) < 1)
                    $error = 'Пустое сообщение!';
#-Если нет ошибок-#
                if (!isset($error)) {
#-Если есть такая новость-#
                    $ins_news = $pdo->prepare("INSERT INTO `news` SET `title` = :title, `msg` = :msg, `user_id` = :user_id, `time` = :time");
                    $ins_news->execute(array(':title' => $title, ':msg' => $msg, ':user_id' => $user['id'], ':time' => time()));
#-Еовая новость-#
                    $upd_users = $pdo->query("UPDATE `users` SET `news_read` = 1");
                    header('Location: /news');
                    $_SESSION['ok'] = 'Новость добавлена!';
                    exit();
                } else {
                    header('Location: /news_add');
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header('Location: /news_add');
                $_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
                exit();
            }
    }

#-Редактирование новости-#
    switch ($act) {
        case 'edit':
#-Проверяем забанены или нет-#
            if ($user['ban_forum'] == 0) {
                if (isset($_POST['title']) and isset($_POST['msg']) and isset($_GET['news'])) {
                    $title = check($_POST['title']); //Заголовок
                    $msg = check($_POST['msg']); //Сообщение
                    $news_id = check($_GET['news']); //Топик
#-Проверяем существует ли такой топик-#
                    $sel_news = $pdo->prepare("SELECT * FROM `news` WHERE `id` = :news_id");
                    $sel_news->execute(array(':news_id' => $news_id));
                    if ($sel_news->rowCount() != 0) {
                        $news = $sel_news->fetch(PDO::FETCH_LAZY);
#-Проверяем данные-#
#-Заголовок-#
                        if (mb_strlen($title) < 1)
                            $error = 'Пустой заголовок!';
#-Сообщение-#
                        if (mb_strlen($title) < 1)
                            $error = 'Пустое сообщение!';
#-Раздел-#
                        if (!preg_match('/^[0-9]+$/u', $_GET['news']))
                            $error = 'Некорректный идентификатор!';
#-Если нет ошибок-#
                        if (!isset($error)) {
                            $upd_news = $pdo->prepare("UPDATE `news` SET `title` = :title, `msg` = :msg, `edit` = :edit WHERE `id` = :news_id");
                            $upd_news->execute(array(':title' => $title, ':msg' => $msg, ':edit' => $news['edit'] + 1, ':news_id' => $news_id));
                            header("Location: /news_read/$news_id");
                        } else {
                            header("Location: /news_read/edit/$news_id");
                            $_SESSION['err'] = $error;
                            exit();
                        }
                    } else {
                        header("Location: /forum_razdel");
                        $_SESSION['err'] = 'Топик не найден!';
                        exit();
                    }
                } else {
                    header("Location: /forum_razdel");
                    $_SESSION['err'] = 'Ошибка!';
                    exit();
                }
            } else {
                header('Location: /forum_razdel');
                $_SESSION['err'] = 'Вы забанены!';
                exit();
            }
    }

#-Открытие или закрытие новости-#
    switch ($act) {
        case 'close':
            if (isset($_GET['news'])) {
                $id = check($_GET['news']);// ID новости
                if (!preg_match('/^[0-9]+$/u', $_GET['news']))
                    $error = 'Некорректный идентификатор!';
#-Если нет ошибок-#
                if (!isset($error)) {
#-Выборка новости-#
                    $sel_news = $pdo->prepare("SELECT * FROM `news` WHERE `id` = :id");
                    $sel_news->execute(array(':id' => $id));
#-Если есть такая новость-#
                    if ($sel_news->rowCount() != 0) {
                        $news = $sel_news->fetch(PDO::FETCH_LAZY);
#-Проверяем закрыта новость или нет-#
#-Закрытие новости-#
                        if ($news['close'] == 0) {
                            $ins_news_com = $pdo->prepare("INSERT INTO `news_comment` SET `comment` = :comment, `news_id` = :news_id, `user_id` = :user_id, `time` = :time");
                            $ins_news_com->execute(array(':comment' => 'Топик закрыт!', ':news_id' => $news['id'], ':user_id' => $user['id'], ':time' => time()));
                            $upd_news = $pdo->prepare("UPDATE `news` SET `close` = :close WHERE `id` = :id");
                            $upd_news->execute(array(':close' => 1, ':id' => $news['id']));
                            header("Location: /news_read/$news[id]");
                            $_SESSION['ok'] = 'Новость закрыта';
                            exit();
                        } else {
                            $ins_news_com = $pdo->prepare("INSERT INTO `news_comment` SET `comment` = :comment, `news_id` = :news_id, `user_id` = :user_id, `time` = :time");
                            $ins_news_com->execute(array(':comment' => 'Топик открыт!', ':news_id' => $news['id'], ':user_id' => $user['id'], ':time' => time()));
                            $upd_news = $pdo->prepare("UPDATE `news` SET `close` = :close WHERE `id` = :id");
                            $upd_news->execute(array(':close' => 0, ':id' => $news['id']));
                            header("Location: /news_read/$news[id]");
                            $_SESSION['ok'] = 'Новость открыта';
                            exit();
                        }
                    } else {
                        header("Location: /news_read/$news[id]");
                        $_SESSION['err'] = 'Ошибка!';
                        exit();
                    }
                } else {
                    header("Location: /news_read/$news[id]");
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header("Location: /news_read/$news[id]");
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
    }

#-Удаление новости-#
    switch ($act) {
        case 'delete':
            if (isset($_GET['news'])) {
                $id = check($_GET['news']);// ID новости
                if (!preg_match('/^[0-9]+$/u', $_GET['news']))
                    $error = 'Некорректный идентификатор!';
#-Если нет ошибок-#
                if (!isset($error)) {
#-Выборка новости-#
                    $sel_news = $pdo->prepare("SELECT * FROM `news` WHERE `id` = :id");
                    $sel_news->execute(array(':id' => $id));
#-Если есть такая новость-#
                    if ($sel_news->rowCount() != 0) {
                        $news = $sel_news->fetch(PDO::FETCH_LAZY);
#-Удаляем новость-#
                        $del_news = $pdo->prepare("DELETE FROM `news` WHERE `id` = :id");
                        $del_news->execute(array(':id' => $news['id']));
#-Удаляем комментарии-#
                        $del_news_c = $pdo->prepare("DELETE FROM `news_comment` WHERE `news_id` = :news_id");
                        $del_news_c->execute(array(':news_id' => $news['id']));
                        header("Location: /news");
                        $_SESSION['ok'] = 'Новость удалена!';
                        exit();
                    } else {
                        header("Location: /news_read/$news[id]");
                        $_SESSION['err'] = 'Ошибка!';
                        exit();
                    }
                } else {
                    header("Location: /news_read/$news[id]");
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header("Location: /news_read/$news[id]");
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
    }

#-Редактирование комментария-#
    switch ($act) {
        case 'edit_comm':
            if (isset($_GET['comment_id']) and isset($_POST['comment'])) {
                $comment_id = check($_GET['comment_id']); //ID Комментария
                $comment_msg = check($_POST['comment']); //Комментарий
                if (!preg_match('/^([а-яА-яЁёa-zA-Z0-9!@#$%^&*()\-_\+;:,.\/?\|\~\[\]\{\}\s])+$/u', $_POST['comment']))
                    $error = 'Неккоректный комментарий!';
                if (mb_strlen($comment_msg) < 1)
                    $error = 'Пустой комментарий!';
                if (mb_strlen($comment_msg) > 2000)
                    $error = 'Слишком длинный комментарий!';
#-Проверяем существует ли такой Комментарий-#
                $sel_n_comment = $pdo->prepare("SELECT * FROM `news_comment` WHERE `id` = :comment_id");
                $sel_n_comment->execute(array(':comment_id' => $comment_id));
                if ($sel_n_comment->rowCount() == 0)
                    $error = 'Комментарий не найден!';
#-Если нет ошибок-#
                if (!isset($error)) {
                    $comment = $sel_n_comment->fetch(PDO::FETCH_LAZY);
#-Проверяем права и время-#
                    if ($comment['user_id'] == $user['id'] and $comment['time'] > time() - 600) {
                        $upd_news_c = $pdo->prepare("UPDATE `news_comment` SET `comment` = :comment WHERE `id` = :comment_id");
                        $upd_news_c->execute(array(':comment' => $comment_msg, ':comment_id' => $comment['id']));
                        header("Location: /news_read/$comment[news_id]");
                    } else {
                        header("Location: /news_read/$comment[news_id]");
                        $_SESSION['err'] = 'Нет прав на редактирование или истекло время!';
                        exit();
                    }
                } else {
                    header("Location: /forum_topic/read/$comment[topic_id]");
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header('Location: /forum_razdel');
                $_SESSION['err'] = 'Данные не переданы!';
                exit();
            }
    }

#-Удаление комментария-#
    switch ($act) {
        case 'del_com':
            if (isset($_GET['comment'])) {
                $comment_id = check($_GET['comment']); //Комментарий
                $news_id = check($_GET['news']);
#-Проверяем существует ли такой Комментарий-#
                $sel_n_comment = $pdo->prepare("SELECT * FROM `news_comment` WHERE `id` = :comment_id");
                $sel_n_comment->execute(array(':comment_id' => $comment_id));
                if ($sel_n_comment->rowCount() != 0) {
                    $del_news_c = $pdo->prepare("DELETE FROM `news_comment` WHERE `id` = :comment_id");
                    $del_news_c->execute(array(':comment_id' => $comment_id));
                    header("Location: /news_read/$news_id");
                } else {
                    header("Location: /news_read/$news_id");
                    $_SESSION['err'] = 'Комментарий не найден!';
                    exit();
                }
            } else {
                header("Location: /forum_razdel");
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
    }

#-Бан игрока-#
    switch ($act) {
        case 'ban':
            if (isset($_GET['user_id'])) {
                $user_id = check($_GET['user_id']);
                $news_id = check($_GET['news']);
#-Проверяем существует ли игрок-#
                $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
                $sel_users->execute(array(':user_id' => $user_id));
                if ($sel_users->rowCount() != 0) {
                    $all = $sel_users->fetch(PDO::FETCH_LAZY);
                    if ($all['id'] != $user['id']) {
#-Бан или разбан-#
                        if ($all['ban_forum'] == 0) {
                            $ban = '1';
                        } else {
                            $ban = '0';
                        }
                        $upd_users = $pdo->prepare("UPDATE `users` SET `ban_forum` = :ban WHERE `id` = :user_id");
                        $upd_users->execute(array(':ban' => $ban, ':user_id' => $user_id));
                        header("Location: /news_read/$news_id");
                    } else {
                        header("Location: /news_read/$news_id");
                        $_SESSION['err'] = 'Вы не можете забанить себя!';
                        exit();
                    }
                } else {
                    header("Location: /news_read/$news_id");
                    $_SESSION['err'] = 'Игрок не найден!';
                    exit();
                }
            } else {
                header("Location: /forum_razdel");
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
    }
} else {
    header('Location: /');
    $_SESSION['err'] = 'Вы не админ!';
    exit();
}
?>