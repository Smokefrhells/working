<?php
function reg() {
    global $user;
    if (isset($user['id'])) {
        header('Location: /');
        exit();
    }

}

function only_reg() {
    global $user;
    if (!isset($user['id'])) {
        header('Location: /');
        $_SESSION['err'] = 'Только для зарегистрированных!';
        exit();
    }
}

?>