<?php
require_once __DIR__ . '/../../app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = @$_POST['email'];
    $password = @$_POST['password'];
    if (\sql\User::is_valid_login($email, $password)) {
        \logging\Logger::getInstance()->debug('GOOD login for: ' . $email);
        $user = \sql\User::get_by_email($email);
        \logging\Logger::getInstance()->debug(print_r($user, true));
        \session\login_as($user);
        header('Location: /index.php');
    } else {
        \logging\Logger::getInstance()->debug('BAD login attempt: ' . $email);
        header('Location: /login.php');
    }
}
