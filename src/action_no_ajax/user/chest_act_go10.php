<?php
require_once '../../system/system.php';
require_once '../../avenax/RandomProbability.php';
only_reg();
#-Открытие сундука-#
switch ($act) {
    case 'open':
        if (isset($_GET['id']) and isset($_GET['type']) and isset($_GET['type_c'])) {
            $type = check($_GET['type']);
            $type_c = check($_GET['type_c']);
            $id = check($_GET['id']);

            #-Проверяем есть ли сундуки-#
            $sel_chest = $pdo->prepare("SELECT * FROM `chest` WHERE `id` = :id AND `user_id` = :user_id");
            $sel_chest->execute([':id' => $id, ':user_id' => $user['id']]);
            #-Если есть записи-#
            if ($sel_chest->rowCount() != 0) {
                $chest = $sel_chest->fetch(PDO::FETCH_LAZY);
                $gold_open = $chest['type'] * 10;
                #-Достаточно ли ключей-#
                if ($type == 'key') {
                    if ($user['key'] < 1)
                        $error = 'Недостаточно ключей!';
                }
                if ($type == 'gold') {
                    if ($user['gold'] < $gold_open)
                        $error = 'Недостаточно золота!';
                }
                if (!isset($error) and ($type == 'key' or $type == 'gold')) {
                    #-Если обычный сундук-#
                    if ($chest['type'] == 1) {
                        $name_chest = '<img src="/style/images/body/chest.png" alt=""/><span class="gray">Обычный сундук</span>';
                        $rand = rand(1, 100);
                        #-Маленькое количество серебра-#
                        if ($rand < 97) {
                            $silver_1 = (1000 * $user['level']) / 6;
                            $silver_2 = (1000 * $user['level']) / 2;
                            $silver_rand = rand($silver_1, $silver_2);
                            $silver_r = round($silver_rand);
                            $silver = "<img src='/style/images/many/silver.png' alt=''/>$silver_r";
                        } else {//Большой выигрышь
                            $silver = "<img src='/style/images/many/silver.png' alt=''/>100000";
                            $silver_r = 100000;
                        }
                        $exp_r = rand($user['level'] * 150 + 200, $user['level'] * 150 + 500);
                        $exp = "<img src='/style/images/user/exp.png' alt=''/>$exp_r";
                    }

                    #-Древний сундук-#
                    if ($chest['type'] == 2) {
                        $name_chest = '<img src="/style/images/body/chest.png" alt=""/><span class="green">Древний сундук</span>';
                        $exp_r = rand($user['level'] * 150 + 500, $user['level'] * 150 + 1000);
                        $exp = "<img src='/style/images/user/exp.png' alt=''/>$exp_r";
                    }

                    #-Золотой сундук-#
                    if ($chest['type'] == 3) {
                        $name_chest = '<img src="/style/images/body/chest.png" alt=""/><span class="orange">Золотой сундук</span>';
                        if ($user['level'] < 20) {
                            $type_r = 1;
                        } else {
                            $type_r = rand(1, 100);
                        }
                        #-Получаем золото и опыт-#
                        if ($type_r < 80) {
                            $exp_r = rand($user['level'] * 150 + 1000, $user['level'] * 150 + 2000);
                            $exp = "<img src='/style/images/user/exp.png' alt=''/>$exp_r";
                            $gold_1 = ($user['level'] / 4) * 2;
                            $gold_2 = ($user['level'] / 2) * 2;
                            $gold_rand = rand($gold_1, $gold_2);
                            $gold_r = round($gold_rand);
                            $gold = "<img src='/style/images/many/gold.png' alt=''/>$gold_r";
                        } else {
                            $exp_r = rand($user['level'] * 150 + 1000, $user['level'] * 150 + 2000);
                            $exp = "<img src='/style/images/user/exp.png' alt=''/>$exp_r";


                        }
                    }


                    #-Сундук Ванху-#
                    /**
                     * От 550 до 900 Золота. Шанс: 84,2%
                     * От 1500 до 3000 Золота. Шанс: 8%
                     * От 3001 до 6000 Золота. Шанс: 5.1%
                     * От 8000 до 20000 Золота. Шанс: 1.5%
                     * Редкий артефакт Шанс: 1.1%
                     */
                    if ($chest['type'] == 4) {

                        $data = [
                            'gold_1' => mt_rand(550, 900),
                            'gold_2' => mt_rand(1500, 3000),
                            'gold_3' => mt_rand(3001, 6000),
                            'gold_4' => mt_rand(8000, 20000),
                            'art'    => mt_rand(8000, 20000),
                        ];

                        $randomProbabilty = new RandomProbability();
                        $randomProbabilty->add('art', 1.6); // 1.1%
                        $randomProbabilty->add('gold_4', 3.5); // 1.5%
                        $randomProbabilty->add('gold_3', 5.5); // 5.1%
                        $randomProbabilty->add('gold_2', 8.2); //  8%
                        $randomProbabilty->add('gold_1', 81,2); // 84,2%

                        $name_chest = '<img src="/style/images/body/chest.png" alt=""/><span class="orange">Сундук Ванху</span>';

                        $sRes = $randomProbabilty->getResult();

                        $getChest = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
                        $getChest->execute([':id' => 173]);
                        $getChestById = $getChest->fetch(PDO::FETCH_ASSOC);

                        $gold_r = 0;

                        if ($sRes == 'art') {
                            $text = '<div class="post">
<table><tbody><tr><td style="width:1%;vertical-align:top;">
<img src="' . $getChestById['images'] . '" width="48px" height="48px" class="weapon_1" alt=""></td>
   <td style="vertical-align:top;"> ' . htmlspecialchars($getChestById['name']) . '
   <br>Ат: <span class="green">' . $getChestById['sila'] . '</span> | ХП: <span class="green">' . $getChestById['health'] . '</span> Защ: <span class="green">' . $getChestById['zashita'] . '</span></td> </tr></tbody></table></div>';
                            $ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
                            $ins_weapon_me->execute(array(':type' => 9, ':weapon_id' => 173, ':user_id' => $user['id'], ':time' => time()));
                        } else {
                            $gold_r = $data[$sRes];
                            $text = "<img src='/style/images/many/gold.png' alt=''/> $gold_r";
                        }

                        if ($type == 'key') {
                            $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `chest` = :chest WHERE `id` = :user_id");
                            $upd_users->execute([':gold' => $user['gold'] + $gold_r, ':key' => $user['key'] - 1, ':chest' => $user['chest'] + 1, ':user_id' => $user['id']]);
                        } else {
                            $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `chest` = :chest WHERE `id` = :user_id");
                            $upd_users->execute([':gold' => $user['gold'] - $gold_open + $gold_r, ':chest' => $user['chest'] + 1, ':user_id' => $user['id']]);
                        }
                        #-Удаляем сундук-#
                        $del_chest = $pdo->prepare("DELETE FROM `chest` WHERE `id` = :chest_id");
                        $del_chest->execute([':chest_id' => $chest['id']]);
                        $_SESSION['notif'] = "<img src='/style/images/body/ok.png' alt=''/> Вы открыли $name_chest и получили:<br/> $text";

                        header("Location: /chest?type=$type_c");
                        exit();
                    }


                    if ($type == 'key') {
                        $upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `gold` = :gold, `silver` = :silver, `key` = :key, `chest` = :chest WHERE `id` = :user_id");
                        $upd_users->execute([':exp' => $user['exp'] + $exp_r, ':gold' => $user['gold'] + $gold_r, ':silver' => $user['silver'] + $silver_r, ':key' => $user['key'] - 1, ':chest' => $user['chest'] + 1, ':user_id' => $user['id']]);
                    }
                    if ($type == 'gold') {
                        $upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `gold` = :gold, `silver` = :silver, `chest` = :chest WHERE `id` = :user_id");
                        $upd_users->execute([':exp' => $user['exp'] + $exp_r, ':silver' => $user['silver'] + $silver_r, ':gold' => $user['gold'] - $gold_open + $gold_r, ':chest' => $user['chest'] + 1, ':user_id' => $user['id']]);
                    }
                    #-Удаляем сундук-#
                    $del_chest = $pdo->prepare("DELETE FROM `chest` WHERE `id` = :chest_id");
                    $del_chest->execute([':chest_id' => $chest['id']]);
                    header("Location: /chest?type=$type_c");
                    $_SESSION['notif'] = "<img src='/style/images/body/ok.png' alt=''/> Вы открыли $name_chest и получили:<br/> $exp $silver $gold";
                    exit();
                } else {
                    header('Location: /chest');
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header('Location: /chest');
                $_SESSION['err'] = 'У вас нет сундуков!';
                exit();
            }
        } else {
            header("Location: /chest?type=$type_c");
            $_SESSION['err'] = 'Недостаточно ключей!';
            exit();
        }
}
?>