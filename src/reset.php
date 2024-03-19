<?php
require_once 'system/system.php';

$allUsers = $pdo->prepare("SELECT `level_sila`, `id`, `level_zashita`, `level_health` FROM `users`");
$allUsers->execute();
// $sila = ($user['level_sila'] * 10);

$stats = $allUsers->fetchAll(PDO::FETCH_ASSOC);


//$upd_users = $pdo->prepare("UPDATE `users` SET `sila` = `sila` - :str, `zashita` = `zashita` - :def, `health` = `health` - :hp WHERE `id` = :id");
//
//foreach ($stats as $stat) {
//
//    $str = 0;
//    $def = 0;
//    $hp = 0;
//    for ($i = 1; $i < $stat['level_sila']; $i ++) {
//        $str += $i * 10;
//    }
//    for ($i = 1; $i < $stat['level_zashita']; $i ++) {
//        $def += $i * 10;
//    }
//
//    for ($i = 1; $i < $stat['level_health']; $i ++) {
//        $hp += $i * 500;
//    }
//    $newStr = $str - $stat['level_sila'] * 3;
//    $newDef = $def - $stat['level_zashita'] * 3;
//    $newHp = $hp - $stat['level_health'] * 3;
//
////    $upd_users->execute(array(':str' => $newStr, ':def' => $newDef, ':hp' => $newHp, ':id' => $stat['id']));
//
//}

//$sel_weapon_me2 = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `weapon_id` != :type AND `weapon_id` != :types AND `state` = 1");
//$sel_weapon_me2->execute(array(':type' => 167, ':types' => 168));
//
//$typeAll = $sel_weapon_me2->fetchAll();
//// 167, 168
//
//$sel_weapon2 = $pdo->prepare("SELECT * FROM `weapon`");
//$sel_weapon2->execute();
//$weaponsAll = $sel_weapon2->fetchAll(PDO::FETCH_ASSOC);
//$newWeapon = [];
//
//foreach ($weaponsAll as $ewrewr) {
//    $newWeapon[$ewrewr['id']] = $ewrewr;
//}
//
//
//
//$upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = `s_sila` - :str, `s_zashita` = `s_zashita` - :def, `s_health` = `s_health` - :hp WHERE `id` = :id LIMIT 1");
//
//
//foreach ($typeAll as $userItem) {
//    $str = $userItem['b_sila'] + $newWeapon[$userItem['weapon_id']]['sila'];
//    $def = $userItem['b_zashita'] + $newWeapon[$userItem['weapon_id']]['zashita'];
//    $hp = $userItem['b_health'] + $newWeapon[$userItem['weapon_id']]['health'];
//    debug($userItem['id']);
////    $upd_users2->execute(array(':str' => $str, ':def' => $def, ':hp' => $hp, ':id' => $userItem['user_id']));
//
//}



