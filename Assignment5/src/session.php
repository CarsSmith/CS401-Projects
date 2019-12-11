<?php namespace session;

session_start();

function login_as($user) {
    session_regenerate_id();
    \logging\Logger::getInstance()->debug('Wha?'); 
    $_SESSION["email"] = $user->email;
}

function logout() {
    session_regenerate_id();
    unset($_SESSION["email"]);
}

function get_current_user() {
    $user = null;
    if (isset($_SESSION["email"])) {
        if ($user !== false) {
            $email = $_SESSION["email"];
            $user = \sql\User::get_by_email($email);
        }
    }
    return $user;
}

function require_login() {
    $user = get_current_user();
    if (!$user) {
        header("Location: /login.php");
        exit();
    }
}

function go_home() {
    header("Location: /index.php");
    exit();
}

function add_form_errors($name, $errors) {
    $_SESSION["$name-errors"] = $errors;
}

function get_form_errors($name) {
    $key = "$name-errors";
    $errors = isset($_SESSION[$key]) ? $_SESSION[$key] : array();
    unset($_SESSION[$key]);
    return $errors;
}

function add_form_data($name, $data) {
    $_SESSION["$name-data"] = $data;
}

function get_form_data($name) {
    $key = "$name-data";
    $data = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    unset($_SESSION[$key]);
    return $data;
}

function add_form_message($name, $message) {
    $_SESSION["$name-message"] = $message;
}

function get_form_message($name) {
    $key = "$name-message";
    $message = isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    unset($_SESSION[$key]);
    return $message;
}
