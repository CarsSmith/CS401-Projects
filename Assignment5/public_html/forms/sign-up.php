<?php
require_once __DIR__ . '/../../app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (@$_POST['status'] == 'new') {
        $email = @$_POST['email'];
        $fname = @$_POST['fname'];
        $password = @$_POST['password'];
        $password2 = @$_POST['password2'];
        $values = array(
            trim($email), trim($fname), trim($password), trim($password2)
        );
        $errors = \validation\validate_user_signup_info($values);
        if (!$errors) {
              \logging\Logger::getInstance()->debug('VALID user signup info');      //Tell the logger
              $values = array( trim($email), trim($fname), sha1(trim($password)) ); //Configure the final $values array
              $created_user_id = \sql\User::create_user($values);                   //Make the new user
              $created_user = \sql\User::get_by_id($created_user_id);               //Get the new user
              \session\login_as($created_user);                                     //Login the new user.
              header("Location: /index.php");                                       //Go to login page
        } else {
              \logging\Logger::getInstance()->debug('INVALID user signup info');    //You fucked up.
              //TODO: More here? Maybe?
              header("Location: /new-user.php");                                    //Try again lol.
        }
    }
}
?>