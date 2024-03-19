<?php
/**
 * Created by PhpStorm.
 * User: A.Gorbunov
 * Date: 10.10.2019
 * Time: 16:39
 */

include_once 'Base.php';

class Art extends Base
{
    public $user;
    public $countSlot = 3;

    public function __construct($user) {
        $this->user = $user;
        $pdo = $this->db();
        $getUser = $pdo->prepare("SELECT COUNT(*) FROM `art_user` WHERE `user_id` = :id");
        $getUser->execute([':id' => $this->user['id']]);
        $column = $getUser->fetchColumn();

        if (empty($column)) {
            $setChest = $pdo->prepare("INSERT INTO `art_user` (`user_id`) VALUES (?)");

            for ($i = 1; $i <= $this->countSlot; $i++) {
                $setChest->execute([$this->user['id']]);
            }
            header('Location: /artefact/' . $this->user['id'] . '/?type=1');
            exit();
        }
        return true;
    }

    public function getItemById($id) {
        $pdo = $this->db();
        $getItem = $pdo->prepare("SELECT `art_user`.*, `w`.`sila`, `w`.`zashita`, `w`.`health`, `w`.`images`, `w`.`level`, `w`.`name` FROM `art_user`
  LEFT JOIN `weapon` as w
  ON `w`.`id` = `art_user`.`weapon_id`
 WHERE `art_user`.`id` = :id");
        $getItem->execute([':id' => $id]);
        return $getItem->fetch(PDO::FETCH_ASSOC);
    }

    public function useItem($idArt, $idWeapon) {
        $pdo = $this->db();

        $getItem = $pdo->prepare("SELECT `weapon_me`.`weapon_id`, `weapon_me`.`id`, `w`.`sila`, `w`.`zashita`, `w`.`health` FROM `weapon_me`
             INNER JOIN `weapon` as w
             ON `w`.`id` = `weapon_me`.`weapon_id`
             WHERE `weapon_me`.`id` = :id");
        $getItem->execute([':id' => $idWeapon]);
        $itemWeapon = $getItem->fetch(PDO::FETCH_ASSOC);

        $sila = $this->user['s_sila'] + $itemWeapon['sila'];
        $zashita = $this->user['s_zashita'] + $itemWeapon['zashita'];
        $health = $this->user['s_health'] + $itemWeapon['health'];

        $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
        $upd_users2->execute([':s_sila' => $sila, ':s_zashita' => $zashita, ':s_health' => $health, ':id' => $this->user['id']]);

        $updArtUsers = $pdo->prepare("UPDATE `art_user` SET `weapon_id` = :weapon_id WHERE `id` = :id");
        $updArtUsers->execute(array(':weapon_id' => $itemWeapon['weapon_id'], ':id' => $idArt));

        $upd = $pdo->prepare("UPDATE `weapon_me` SET `state` = 1 WHERE `id` = :id");
        $upd->execute(array(':id' => $itemWeapon['id']));
        return true;
    }

    public function takeItem($idItem, $idWeapon) {
        $pdo = $this->db();

        $getItem = $pdo->prepare("SELECT `weapon_me`.`weapon_id`, `weapon_me`.`id`, `w`.`sila`, `w`.`zashita`, `w`.`health` FROM `weapon_me`
             INNER JOIN `weapon` as w
             ON `w`.`id` = `weapon_me`.`weapon_id`
             INNER JOIN `art_user` a
             ON `a`.`id` = :id_item AND `a`.`weapon_id` = `weapon_me`.`weapon_id`
             WHERE `w`.`id` = :id AND `weapon_me`.`user_id` = :user_id AND `weapon_me`.`state` = 1");
        $getItem->execute([':id_item' => $idItem, ':id' => $idWeapon, ':user_id' => $this->user['id']]);
        $itemWeapon = $getItem->fetch(PDO::FETCH_ASSOC);



        $sila = $this->user['s_sila'] - $itemWeapon['sila'];
        $zashita = $this->user['s_zashita'] - $itemWeapon['zashita'];
        $health = $this->user['s_health'] - $itemWeapon['health'];

        $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
        $upd_users2->execute([':s_sila' => $sila, ':s_zashita' => $zashita, ':s_health' => $health, ':id' => $this->user['id']]);

        $updArtUsers = $pdo->prepare("UPDATE `art_user` SET `weapon_id` = :weapon_id WHERE `id` = :id AND `user_id` = :user_id");
        $updArtUsers->execute(array(':weapon_id' => 0, ':id' => $idItem, ':user_id' => $this->user['id']));


        $upd = $pdo->prepare("UPDATE `weapon_me` SET `state` = :state WHERE `id` = :id");
        $upd->execute(array(':state' => 0, ':id' => $itemWeapon['id']));

        return true;
    }

    /**
     * список не одетых арт
     * @return array
     */
    public function getNotState() {
        $pdo = $this->db();
        $weapon = $pdo->prepare("SELECT `weapon_me`.*, `w`.`images`, `w`.`level`, `w`.`name`, `w`.`sila`, `w`.`health`, `w`.`zashita` FROM `weapon_me`
 LEFT JOIN `weapon` as w
  ON `w`.`id` = `weapon_me`.`weapon_id`
 WHERE `weapon_me`.`type` = :type AND `weapon_me`.`user_id` = :user_id AND `weapon_me`.`state` = 0 ORDER BY `weapon_me`.`id` DESC");
        $weapon->execute(array(':type' => 9, ':user_id' => $this->user['id']));
        return $weapon->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * список арт у игрока
     * @return array
     */
    public function getListArt() {
        $pdo = $this->db();
        $getArt = $pdo->prepare("SELECT `wm`.*, `w`.`images`, `w`.`level`, `w`.`name`, `w`.`sila`, `w`.`health`, `w`.`zashita` FROM `art_user` as wm
 LEFT JOIN `weapon` as w
 ON `w`.`id` = `wm`.`weapon_id` WHERE `wm`.`user_id` = :user_id");
        $getArt->execute([':user_id' => $this->user['id']]);
        return $getArt->fetchAll(PDO::FETCH_ASSOC);
    }
}