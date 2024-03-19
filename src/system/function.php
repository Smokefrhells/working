<?php
$set['site'] = htmlspecialchars($_SERVER['HTTP_HOST']);
define("H", $_SERVER["DOCUMENT_ROOT"] . '/');
mb_internal_encoding("UTF-8");
$opdirbase = opendir(H . "system/function/");
while ($filebase = readdir($opdirbase)) {
    $arr = array('reg.php', 'hash.php', 'check.php', 'pol.php', 'start.php', 'save.php', 'time.php', 'online.php', 'pages.php', 'smiles.php', 'number.php', 'level.php', 'weapon.php', 'storona.php', 'zamki.php', 'online.php', 'avatar.php', 'prava.php', 'pets.php');
    $allowed = in_array($filebase, $arr);
    if (preg_match('#\.php$#i', $filebase)) {
        if ($filebase != $allowed) {
            echo '<center><h1>Internal Server Error</h1></center><b>Просим прощения за предоставленные неудобства. Вскоре сайт заработает. <br/>Персональный код ошибки: 600</b>';
            exit();
        }
        require_once H . ('system/function/' . $filebase);
    }
}
?>