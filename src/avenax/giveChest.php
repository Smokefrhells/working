<?php
require_once '../system/system.php';
$head = 'Выдать сундук';
only_reg();
admin();
require_once H . 'system/head.php';

$type = [
    1 => 'Простой', 'Древний', 'Золотой', 'Сундук ванху'
];

if (filter_has_var(INPUT_POST, 'userID')) {
    $checkUser = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = ?");
    $checkUser->execute([$_POST['userID']]);
    $userId = $checkUser->fetch(PDO::FETCH_ASSOC);

    $err = null;
    if (empty($userId)) {
        $err = 'Такого игрока не существует!';
    }

    if (!array_key_exists($_POST['typeChest'], $type)) {
        $err = 'Такой тип сундука не существует!';
    }

    if (empty($_POST['count']) || $_POST['count'] <= 0) {
        $err = 'Введите количество сундуков';
    }

    if (empty($err)) {
        $setChest = $pdo->prepare("INSERT INTO `chest`(`type`, `user_id`, `time`) VALUES (?, ?, ?)");

        for ($i = 1; $i <= $_POST['count']; $i++) {
            $setChest->execute([$_POST['typeChest'], $userId['id'], time()]);
        }
        $_SESSION['ok'] = 'Успех!';
    } else {
        $_SESSION['err'] = $err;
    }
    header('Location: /admin_chest');
    exit();
}
?>
    <div class="page">
        <form method="post">
            <input class="input_form" type="number" name="userID" placeholder="ID игрока"/><br/>
            <label>
                <select name="typeChest" class="input_form">
                    <?php foreach ($type as $num => $value): ?>
                        <option value="<?= $num; ?>"><?= $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <input class="input_form" type="number" name="count" placeholder="Количество"/><br/>
            <input class="button_i_mini" type="submit" value="Выдать">
        </form>
        <div style="padding-top: 5px;"></div>
    </div>
<?php
require_once H . 'system/footer.php';