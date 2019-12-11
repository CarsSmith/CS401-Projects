<?php
require_once __DIR__ . '/../../app.php';

\session\require_login();
$user = \session\get_current_user();
if (!$user->is_admin && !$user->is_moderator) {
    \session\go_home();
}

\forms\handle_user_creation();

$template = new \template\AdminPage();
$template->title = 'User Creation';
$template->user = $user;
$template->start();

$errors = \session\get_form_errors('a-create-user');
$fname_data = \session\get_form_data('a-sign-up-fname');
$email_data = \session\get_form_data('a-sign-up-email');
$message = \session\get_form_message('a-create-user');
$fname_value = $fname_data ? $fname_data : "";
$email_value = $email_data ? $email_data : "";
?>

  
<div class='create-user'>
  <h1>User Creation</h1>
  <form method='POST'>
    <div id='forms' >
      <div id='labels' >
        <label for=email>Email: </label>
        </br>
        <label for=fname>Username: </label>
        </br>
        <label for=password>Password: </label>
        </br>
        <label for=password2>Verify Password: </label>
      </div>
      <div id='inputs' >
        <input class="text-ar" id=email type='text' name='email' value="<?=htmlentities($email_value)?>" placeholder="Email..." required=true /></br>
        <input class="text-ar" id=fname type='text' name='fname' value="<?=htmlentities($fname_value)?>" placeholder="Username..." required=true /></br>
        <input class="text-ar" id=password type='password' name='password' value='' placeholder="Password..." required=true /></br>
        <input class="text-ar" id=password2 type='password' name='password2' value '' placeholder="Password..." required=true /></br>
      </div>
    </div>
    </br>
    <input type="checkbox" name="is_moderator" /> Moderator |
    <input type="checkbox" name="is_admin" /> Admin |
    <input type="checkbox" name="is_banned" /> Ban User
    <br></br>
    <input type="hidden" name="status" value="new" />
    <input class="submit-button" type='submit' value='Sign Up' />
  </form>
  <?php if (!empty($message)): ?>
    <br />
    <span class='form-message'><?=$message?></span>
  <?php endif; ?>
  <?php if(isset($errors['email'])): ?>
    </br><span class='error'><?=$errors['email']?></span>
  <?php endif; ?>
  <?php if(isset($errors['fname'])): ?>
    </br><span class='error'><?=$errors['fname']?></span>
  <?php endif; ?>
  <?php if(isset($errors['password'])): ?>
    </br><span class='error'><?=$errors['password']?></span>
  <?php endif; ?>
</div>