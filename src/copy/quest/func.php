<?
require_once('../../system/system.php');
require_once('../../copy/copy_func.php');
$kvest = fch("SELECT * FROM `kvest` WHERE `id_user` = ?", array($user['id']));
$monster = fch("SELECT * FROM `monsters_quest` WHERE `id` = ?", array($kvest['id_monster']));

$battle = fch("SELECT * FROM `kvest_battle` WHERE `id_kvest` = ? AND `start` != ?", array($kvest['id'], 3));

if ($battle) {
    $str_ = round(get_power($user['id']) / 3);
    $str__ = round(get_power($user['id']) / 5);
    $str = rand($str_, $str__);
//        if(mt_rand(0, 100) < get_krit($user['id'])){
//            $str *= 2;
//        }

    $def_opp_ = round($monster['block'] / 6);
    $def_opp__ = round($monster['block'] / 8);
    $def_opp = rand($def_opp_, $def_opp__);

    $str -= $def_opp;
    if ($str < 0)
        $str = 0;
    if ($str > $battle['monster_hp'])
        $str = $battle['monster_hp'];

    $str_opp_ = round($monster['power'] / 3);
    $str_opp__ = round($monster['power'] / 5);
    $str_opp = rand($str_opp__ , $str_opp_);

    $def_ = round(get_block($user['id']) / 6);
    $def__ = round(get_block($user['id']) / 8);
    $def = rand($def_, $def__);

    $str_opp -= $def;
    if ($str_opp < 0) {
        $str_opp = 0;
    }

    if ($str_opp > $battle['user_hp']) {
        $str_opp = $battle['user_hp'];
    }


    qry("UPDATE `kvest_battle` SET `user_hp` = ?, `monster_hp` = ? WHERE `id` = ?", array($battle['user_hp'] - $str_opp, $battle['monster_hp'] - $str, $battle['id']));

    if ($battle['monster_hp'] - $str <= 0) {
        qry("UPDATE `kvest_battle` SET `start` = ?, `wins` = ? WHERE `id` = ?", array(2, 1, $battle['id']));
    } elseif ($battle['user_hp'] - $str_opp <= 0) {
        qry("UPDATE `kvest_battle` SET `start` = ?, `wins` = ? WHERE `id` = ?", array(2, 2, $battle['id']));
    }
    ?>
    {"att_user":"<?= $str ?>","att_vrag":"<?= $str_opp ?>","error":"0","wins":"<?= $battle['wins'] ?>"}
    <?
} else {
    ?>
    {"error":"1"}
    <?
}
?>