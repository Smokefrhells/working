<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 26.08.2019
 * Time: 22:12
 */


include_once $_SERVER['DOCUMENT_ROOT'] . '/system/system.php';

include_once H . 'avenax/Item.php';


Maneken::show();

class Maneken {

    public static $idItemReg = 30;

    public static function connect() {
        global $pdo;
        return $pdo;
    }

    public static function itemMore($weapon_id, $runa = 0) {
        $itemType = Item::Items();

        $base = false;

        foreach ($itemType as $id => $type) {

            if (in_array($weapon_id, $type->id)) {
                $base = $type;
                $base->levelSet = $id;

                foreach ($type->output_set as $sets) {

                    if (array_key_exists($weapon_id, $sets['output_item'])) {
                        $base->nameItem = $sets['output_item'][$weapon_id];
                    }
                }
            }
        }
        if (!empty($runa)) {
            $base->runa = Item::Runes($runa);
        }

        return $base;
    }

    /**
     * выборка итема с бд
     * @param $user
     * @param $id
     * @param int $state
     * @param bool $err
     * @return mixed
     */
    public static function getByUserId($user, $id, $state = 1, $err = false) {
        $pdo = self::connect();
        $userItem = $pdo->prepare("SELECT * FROM `item_user` WHERE `user_id` = ? AND `state` = ? AND `weapon_id` = ?");
        $userItem->execute(array($user, $state, $id));
        $resultObj = $userItem->fetch(PDO::FETCH_ASSOC);
        if (empty($resultObj)) {
            if ($err) {
                $_SESSION['err'] = $err;
            }
            header('Location: /hero/' . $user);
            exit();
        }
        return $resultObj;
    }

    /**
     * получаем ID предмета по типу, который одет
     * @param $user
     * @param $data
     * @return bool
     */
    public static function getUserState($user, $data) {

//        $whiteList = ['head', 'shoulder', 'armor', 'gloves', 'weapons_1', 'weapons_2', 'pants', 'boots'];
//        if (!in_array($type, $whiteList)) {
//            return false;
//        }
        $resultObj = Maneken::getUserItems($user);
        $itemType = Item::getTypeItem();
        $idNeed = false;

        // определям тип вещи, из которой переносим заточку
        foreach ($itemType as $k => $v) {
            if (in_array($data, $v)) {
                $idNeed = $k;
            }
        }

        if ($idNeed == false) {
            return false;
        }

        $id = false;
        // получаем id вещи, которая одета
        foreach ($resultObj as $obj) {
            if (in_array($obj['weapon_id'], $itemType[$idNeed])) {
                $id = $obj['weapon_id'];
            }
        }
        return $id;
    }

    /**
     * выборка все вещей надетых
     * @param $user
     * @return array
     */
    public static function getUserItems($user) {
        $pdo = self::connect();
        $userItem = $pdo->prepare("SELECT * FROM `item_user` WHERE `user_id` = :user_id AND `state` = :state");
        $userItem->execute(array(':user_id' => $user, ':state' => 1));
        $resultObj = $userItem->fetchAll(PDO::FETCH_ASSOC);
        $arr = [];
        foreach ($resultObj as $item) {
            $arr[$item['weapon_id']] = $item;
        }
        return $arr;
    }

    public static function getUser($user) {
        $pdo = self::connect();
        $userItem = $pdo->prepare("SELECT `pol` FROM `users` WHERE `id` = :user_id");
        $userItem->execute(array(':user_id' => $user));
        $resultObj = $userItem->fetch(PDO::FETCH_ASSOC);
        return $resultObj;
    }


    public static function countBag($user) {
        $pdo = self::connect();
        $userItem = $pdo->prepare("SELECT COUNT(*) FROM `item_user` WHERE `user_id` = :user_id AND `state` = :state");
        $userItem->execute(array(':user_id' => $user, ':state' => 1));
        $count = $userItem->fetchColumn();
        if ($count >= 20) {
            return false;
        }
        return true;
    }

    public static function show() {

        if (!isset($_GET['user'])) {
            return false;
        }

        $resultObj = self::getUserItems($_GET['user']);

        $itemType = Item::getTypeItem();

        $sex = self::getUser($_GET['user']);
        $baseImg = H . 'images/maneken/0_' . $sex['pol'] . '.png';


        $size = getimagesize($baseImg);

        $image = imagecreatetruecolor($size[0], $size[1]);
//        imagealphablending($image, false);
        imagesavealpha($image, true);
        $col = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefill($image, 0, 0, $col);
//        imagealphablending($image, true);

        /* add door glass */
        $img_doorGlass = imagecreatefrompng($baseImg);
        imagecopy($image, $img_doorGlass, 0, 0, 0, 0, $size[0], $size[1]);


        if (!empty($resultObj)) {
            $newBase = [];
            foreach ($resultObj as $item) {
                if (in_array($item['weapon_id'], $itemType['head'])) {
                    $newBase[1] = $item;
                }
                // штаны
                if (in_array($item['weapon_id'], $itemType['pants'])) {
                    $newBase[2] = $item;
                }
                if (in_array($item['weapon_id'], $itemType['armor'])) {
                    $newBase[3] = $item;
                }
                if (in_array($item['weapon_id'], $itemType['gloves'])) {
                    $newBase[4] = $item;
                }
                // боты
                if (in_array($item['weapon_id'], $itemType['boots'])) {
                    $newBase[5] = $item;
                }
                // плечо
                if (in_array($item['weapon_id'], $itemType['shoulder'])) {
                    $newBase[6] = $item;
                }
                // 1 рука
                if (in_array($item['weapon_id'], $itemType['weapons_1'])) {
                    $newBase[7] = $item;
                }
                // 2 рука
                if (in_array($item['weapon_id'], $itemType['weapons_2'])) {
                    $newBase[8] = $item;
                }
            }

            ksort($newBase, SORT_ASC );

            foreach ($newBase as $obj) {
                    $head = H . "images/maneken/{$sex['pol']}/{$obj['weapon_id']}.png";
                    $img_head = imagecreatefrompng($head);
                    imagealphablending($img_head, true);
                    imagecopy($image, $img_head, 0, 0, 0, 0, $size[0], $size[1]);
            }
        }

        header('content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }
}