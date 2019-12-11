<?php namespace forms;

function handle_login() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = @$_POST['email'];
        $password = @$_POST['password'];
        if (\sql\User::is_valid_login($email, $password)) {
            \logging\Logger::getInstance()->debug('GOOD login for: ' . $email);
            $user = \sql\User::get_by_email($email);
            \logging\Logger::getInstance()->debug(print_r($user, true));
            \session\login_as($user);
            header('Location: /index.php');
            exit();
        } else {
            \logging\Logger::getInstance()->debug('BAD login attempt: ' . $email);
            header('Location: /login.php');
            exit();
        }
    }
}

function handle_user_update() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = @$_POST['id'];
        $name = @$_POST['name'];
        $is_admin = @$_POST['is_admin'];
        $is_banned = @$_POST['is_banned'];
        $is_moderator = @$_POST['is_moderator'];
        $user = \sql\User::get_by_id($id);
        if ($user) {
            $auth = \session\get_current_user();
            $user->name = trim($name);
            if ($auth->is_moderator || $auth->is_admin) {
                $user->is_banned = $is_banned;
                $user->is_moderator = $is_moderator;
            }
            if ($auth->is_admin) {
                $user->is_admin = $is_admin;
            }
            $errors = \validation\user_update_errors($user);
            if (count($errors)) {
                \session\add_form_data('user-update', $user);
                \session\add_form_errors('user-update', $errors);
            } else {
                \session\add_form_message('user-update', 'Successfully saved');
                \sql\User::update($user);
            }
        }
        header("Location: /admin/edit-user.php?id=$id");
        exit();
    }
}

function handle_user_signup() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (@$_POST['status'] == 'new') {
            $email = @$_POST['email'];
            $fname = @$_POST['fname'];
            $password = @$_POST['password'];
            $password2 = @$_POST['password2'];
            $is_admin = @$_POST['is_admin'];
            $is_moderator = @$_POST['is_moderator'];
            $is_banned = @$_POST['is_banned'];
            if($is_admin == "on") {
                $is_admin = 1;
            } else {
                $is_admin = 0;
            }
            if($is_moderator == "on") {
                $is_moderator = 1;
            } else {
                $is_moderator = 0;
            }
            if($is_banned == "on") {
                $is_banned = 1;
            } else {
                $is_banned = 0;
            }
            $values = array(
                trim($email), trim($fname), trim($password), trim($password2), $is_admin, $is_moderator, $is_banned
            );
            $errors = \validation\validate_user_signup_info($values);
            if (!$errors) {
                  \logging\Logger::getInstance()->debug('VALID user signup info');      //Tell the logger
                  $values = array( trim($email), trim($fname), sha1(trim($password)), $is_admin, $is_moderator, $is_banned ); //Configure the final $values array
                  $created_user_id = \sql\User::create_user($values);                   //Make the new user
                  $created_user = \sql\User::get_by_id($created_user_id);               //Get the new user
                  \session\login_as($created_user);                                     //Login the new user.
                  header("Location: /index.php");                                       //Go to login page
            } else {
                  \logging\Logger::getInstance()->debug('INVALID user signup info');    //You fucked up.
                  \session\add_form_data('sign-up-email', $email);
                  \session\add_form_data('sign-up-fname', $fname);
                  \session\add_form_errors('sign-up', $errors);
            }
        }
    }
}

function handle_user_creation() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (@$_POST['status'] == 'new') {
            $email = @$_POST['email'];
            $fname = @$_POST['fname'];
            $password = @$_POST['password'];
            $password2 = @$_POST['password2'];
            $is_admin = @$_POST['is_admin'];
            $is_moderator = @$_POST['is_moderator'];
            $is_banned = @$_POST['is_banned'];
            if($is_admin == "on") {
                $is_admin = 1;
            } else {
                $is_admin = 0;
            }
            if($is_moderator == "on") {
                $is_moderator = 1;
            } else {
                $is_moderator = 0;
            }
            if($is_banned == "on") {
                $is_banned = 1;
            } else {
                $is_banned = 0;
            }
            $values = array(
                trim($email), trim($fname), trim($password), trim($password2), $is_admin, $is_moderator, $is_banned
            );
            $errors = \validation\validate_user_signup_info($values);
            if (!$errors) {
                  \logging\Logger::getInstance()->debug('VALID user signup info');      //Tell the logger
                  $values = array( trim($email), trim($fname), sha1(trim($password)), $is_admin, $is_moderator, $is_banned ); //Configure the final $values array
                  $created_user_id = \sql\User::create_user($values);                   //Make the new user
                  \session\add_form_message('a-create-user', 'Successfully Created User!');
                  //header("Location: /admin/index.php");                                 //Go to admin main page
            } else {
                  \logging\Logger::getInstance()->debug('INVALID user signup info');    //You fucked up.
                  \session\add_form_data('a-sign-up-email', $email);
                  \session\add_form_data('a-sign-up-fname', $fname);
                  \session\add_form_errors('a-create-user', $errors);
                  //header("Location: /new-user.php");                                    //Try again lol.
            }
        }
    }
}
function handle_comment_creation() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
        $text = htmlspecialchars(@$_POST['text']);
        $account_id = @$_POST['account_id'];
        $post_id = @$_POST['post_id'];
        
        $values = array(
            trim($text), $account_id, $post_id
        );
        $errors = \validation\validate_comment_create($values);
        if(!$errors) {
            \logging\Logger::getInstance()->debug('GOOD comment created.');
            $created_comment_id = \sql\Comment::create_comment($values);
            header("Location: /show-post.php?id=$post_id");
        } else {
            \logging\Logger::getInstance()->warn('BAD comment created.');
            \session\add_form_data('comment-new-text', $text);
            \session\add_form_errors('comment-new', $errors);
        }
    }
}

function handle_comment_edit() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
        //Basic Values
        $text = htmlspecialchars(@$_POST['text']);
        $id = @$_POST['id'];
            
        //Making Values
        $values = array(
            trim($text), $id
        );
        $errors = \validation\validate_comment_create($values);
        if (!$errors) {
            \logging\Logger::getInstance()->debug('GOOD comment edit.');
            \sql\Comment::update($values);
            $the_comment = \sql\Comment::get_by_id($id);
            header("Location: /show-post.php?id={$the_comment->post_id}");
        } else {
            \logging\Logger::getInstance()->warn('BAD comment edit.');
            \session\add_form_data('comment-edit-text', $text);
            \session\add_form_errors('comment-edit', $errors);
        }
    }
}

function handle_post_creation() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
        if (@$_POST['status'] == 'new') {  //Should only be called when a new post is being made on the page where this value is on the only page it's on.
        
            //Basic Values
            $title = htmlspecialchars(@$_POST['title']);
            $description = htmlspecialchars(@$_POST['description']);
            $category_id = @$_POST['category_id'];
            $account_id = @$_POST['account_id'];
            
            //Image Values
            $image_url = basename($_FILES["image_url"]["name"]);
            
            //Making Values
            $values = array(
                trim($title), trim($description), $image_url, $account_id, $category_id
            );
            $errors = \validation\validate_post_create($values);
            if (!$errors) {
                \logging\Logger::getInstance()->debug('GOOD post created.');
                $created_post_id = \sql\Post::create_post($values);
                $created_post = \sql\Post::get_by_id($created_post_id);
                header("Location: /show-post.php?id={$created_post->id}");
            } else {
                \logging\Logger::getInstance()->warn('BAD post created.');
                \session\add_form_data('post-new-title', $title);
                \session\add_form_data('post-new-description', $description);
                \session\add_form_errors('post-new', $errors);
            }
        }
    }
}

function handle_post_edit($old_img_url) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
        if (@$_POST['status'] == 'new') {  //Should only be called when a new post is being made on the page where this value is on the only page it's on.
        
            //Basic Values
            $title = htmlspecialchars(@$_POST['title']);
            $description = htmlspecialchars(@$_POST['description']);
            $post_id = @$_POST['post_id'];
            //Image Values
            $image_url = basename($_FILES["image_url"]["name"]);
            \logging\Logger::getInstance()->debug("HERER CARSN LOOK HERE AAA {$image_url}");
            \logging\Logger::getInstance()->debug(print_r($image_url, true));
            if($image_url) { //If the new image url is empty, use the old image.
                
            } else {
                $image_url == $old_img_url;
                \logging\Logger::getInstance()->debug("YEARG {$old_img_url}");
            }
            //Making Values
            $values = array(
                trim($title), trim($description), $image_url, $post_id
            );
            $errors = \validation\validate_post_create($values);
            if (!$errors) {
                \logging\Logger::getInstance()->debug('GOOD post created.');
                \sql\Post::update($values);
                header("Location: /show-post.php?id={$post_id}");
            } else {
                \logging\Logger::getInstance()->warn('BAD post created.');
                \session\add_form_data('post-edit-title', $title);
                \session\add_form_data('post-edit-description', $description);
                \session\add_form_errors('post-edit', $errors);
            }
        }
    }
}

/* function handle_todo_done() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (@$_POST['status'] == 'done') {
            if (@$_POST['id']) {
                $todo = \data\get_todo($_POST['id']);
                $todo->done_at = date("Y-m-d H:i:s");
                \data\save_todo($todo, array('done_at'));
            }
            header('Location: /todos.php');
        }
    }
}

function handle_todo_add() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (@$_POST['status'] == 'new') {
            if (@$_POST['todo']) {
                $todo = new \data\Todo();
                $todo->text = $_POST['todo'];
                \data\create_todo($todo);
            }
            header('Location: /todos.php');
        }
    }
}

function handle_todo_edit() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (@$_POST['id'] && @$_POST['todo']) {
            $todo = \data\get_todo($_POST['id']);
            if ($todo) {
                $todo->text = $_POST['todo'];
                \data\save_todo($todo, array('text'));
                header("Location: /edit.php?id={$todo->id}");
                return;
            }
        }
        header("Location: /todos.php");
    }
}
*/
