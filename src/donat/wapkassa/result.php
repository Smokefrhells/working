<?php
require_once '../../system/system.php';
require_once '../../copy/copy_func.php';
include_once __DIR__ . '/sett.php';
include_once __DIR__ . '/WapkassaClass.php';

try {
    // Инициализация класса с id сайта и секретным ключом
    $wapkassa = new WapkassaClass(WK_ID, WK_SECRET);

    // Проверка обработчика (PING)
    if ($wapkassa->ping($_POST)) {
        // возврат успешной проверки
        echo $wapkassa->successPing();
    } else {
        // Парсинг входящих параметров
        $params = $wapkassa->parseRequest($_POST);

        $params['id']; // id платежа в системе wapkassa
        $params['site_id']; // id площадки
        $params['time']; // время оплаты в unixtime
        $params['comm']; // комментарий платежа
        $params['amount']; // сумма платежа
        $params['add']; // массив с допольнительными параметрами

        // собственный код зачисления платежа на сайте
        if ($params['add']['type'] == 'gold' && !empty($wk_cena_gold[$params['add']['count']]) && $wk_cena_gold[$params['add']['count']] <= $params['amount']) {

            $gold = $params['add']['count'];
            $ref_gold = round(($gold * 0.15), 0); //Золото которое начисляем рефереру
            $sel_users = $pdo->prepare("SELECT `id`, `gold`, `clan_id`, `pol`, `nick`, `ref_id`, `level` FROM `users` WHERE `id` = :user_id");
            $sel_users->execute([':user_id' => $params['add']['user_id']]);
            $all = $sel_users->fetch(PDO::FETCH_LAZY); //Игрок
            #-Акция на x2 золото-#
            $sel_stock_2 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 2");
            if ($sel_stock_2->rowCount() != 0) {
                $gold = $gold * 2;
            }
            #-Акция на x3 золото-#
            $sel_stock_3 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 3");
            if ($sel_stock_3->rowCount() != 0) {
                $gold = $gold * 3;
            }
            #-Зачисление золота-#
            $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = `gold` + :gold WHERE `id` = :id LIMIT 1");
            $upd_users->execute([':gold' => $gold, ':id' => $params['add']['user_id']]);

            $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('gold_200'));
            $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $params['add']['user_id']));
            if($user_quest['ok'] == 0){
                qry("UPDATE `quest_user` SET `kolls` = ? WHERE `id` = ?", array($user_quest['kolls']+$gold, $user_quest['id']));
            }

            $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('gold_1000'));
            $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $params['add']['user_id']));
            if($user_quest['ok'] == 0){
                qry("UPDATE `quest_user` SET `kolls` = ? WHERE `id` = ?", array($user_quest['kolls']+$gold, $user_quest['id']));
            }

            #-Есть ли реферер у игрока-#
            if ($all['ref_id'] != 0) {
                #-Есть ли такой реферер-#
                $sel_users_r = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :ref_id");
                $sel_users_r->execute([':ref_id' => $all['ref_id']]);
                if ($sel_users_r->rowCount() != 0) {
                    #-Зачисляем золото-#
                    $upd_users_r = $pdo->prepare("UPDATE `users` SET `gold` = `gold` + :gold, `referer_gold` = `referer_gold` + :gold WHERE `id` = :id");
                    $upd_users_r->execute([':gold' => $ref_gold, ':id' => $all['ref_id']]);
                }
            }
            $msg = 'Здравсвуйте вы успешно купили: ' . $gold;//вот
            $ins_mail = $pdo->prepare("INSERT INTO `mail` SET `msg` = :msg, `send_id` = :send_id, `recip_id` = :recip_id, `time` = :time");
            $ins_mail->execute([':msg' => $msg, ':send_id' => 2, ':recip_id' => $all['id'], ':time' => time()]);
            #-Есть ли переписка или нет-#
            $sel_mail_k = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :user_id AND `kont_id` = :all_id");
            $sel_mail_k->execute([':all_id' => $all['id'], ':user_id' => 2]);
            if ($sel_mail_k->rowCount() == 0) {
                $ins_mail_k1 = $pdo->prepare("INSERT INTO `mail_kont` SET `new` = :new, `user_id` = :user_id, `kont_id` = :kont_id, `time` = :time");
                $ins_mail_k1->execute([':new' => 0, ':user_id' => 2, ':kont_id' => $all['id'], ':time' => time()]);
            } else {
                $upd_mail_m = $pdo->prepare("UPDATE `mail_kont` SET `time` = :time WHERE `user_id` = :user_id AND `kont_id` = :kont_id");
                $upd_mail_m->execute([':time' => time(), ':kont_id' => $all['id'], ':user_id' => 2]);
            }

            $sel_mail_k2 = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :all_id AND `kont_id` = :user_id");
            $sel_mail_k2->execute([':all_id' => $all['id'], ':user_id' => 2]);
            if ($sel_mail_k2->rowCount() == 0) {
                $ins_mail_k2 = $pdo->prepare("INSERT INTO `mail_kont` SET `new` = :new, `user_id` = :kont_id, `kont_id` = :user_id, `time` = :time");
                $ins_mail_k2->execute([':new' => 1, ':kont_id' => $all['id'], ':user_id' => 2, ':time' => time()]);
            } else {
                $upd_mail_k = $pdo->prepare("UPDATE `mail_kont` SET `new` = :new, `time` = :time WHERE `user_id` = :kont_id AND `kont_id` = :user_id");
                $upd_mail_k->execute([':time' => time(), ':new' => 1, ':kont_id' => $all['id'], ':user_id' => 2]);
            }
            #-Питомец в подарок-#
            $sel_stock_8 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 8");
            if ($sel_stock_8->rowCount() != 0 and ($gold >= 3000 or $gold >= 7500)) {
                if ($gold >= 3000) {
                    $pets_id = 4;
                    $pets_name = 'Змееглав';
                }
                if ($gold >= 7500) {
                    $pets_id = 5;
                    $pets_name = 'Гелаус';
                }
                #-Запись питомца-#
                $ins_pets_me = $pdo->prepare("INSERT INTO `pets_me` SET `pets_id` = :pets_id, `user_id` = :user_id");
                $ins_pets_me->execute([':pets_id' => $pets_id, ':user_id' => $params['add']['user_id']]);
            }


            $sel_donat = $pdo->prepare("SELECT * FROM `donate` WHERE `gold` = :gold AND `user_id` = :user_id");
            $sel_donat->execute([':gold' => $params['add']['count'], ':user_id' => $params['add']['user_id']]);
            if ($sel_donat->rowCount() != 0) {
                $upd_donat = $pdo->prepare("UPDATE `donate` SET `statys` = 1 WHERE `gold` = :gold AND `user_id` = :user_id LIMIT 1");
                $upd_donat->execute([':gold' => $params['add']['count'], ':user_id' => $params['add']['user_id']]);
            }
        }
        #-Клановая акция-#
        $sel_stock_1 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 1");
        if ($sel_stock_1->rowCount() != 0) {
            if ($all['clan_id'] != 0) {
                #-Выборка клана-#
                $sel_clan = $pdo->prepare("SELECT `id`, `gold` FROM `clan` WHERE `id` = :clan_id");
                $sel_clan->execute([':clan_id' => $all['clan_id']]);
                if ($sel_clan->rowCount() != 0) {
                    #-Зачисляем золото в казну клана-#
                    $upd_clan = $pdo->prepare("UPDATE `clan` SET `gold` = `gold` + :gold WHERE `id` = :clan_id");
                    $upd_clan->execute([':gold' => $gold, ':clan_id' => $all['clan_id']]);
                    #-Лог клана-#
                    if ($all['pol'] == 1) {
                        $b = 'пополнил';
                    } else {
                        $b = 'пополнила';
                    }
                    $ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
                    $ins_clan_l->execute([':type' => 3, ':log' => "<a href='/hero/" . $all['id'] . "' style='display:inline;text-decoration:underline;padding:0px;'>" . $all['nick'] . "</a> $b казну: <img src='/style/images/many/gold.png' alt=''/>" . $gold, ':clan_id' => $all['clan_id'], ':time' => time()]);
                }
            }
        }

        // возврат успешной обработки
        echo $wapkassa->successPayment();
    }
} catch (Exception $e) {
    // вывод ошибки
    echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
}

?>