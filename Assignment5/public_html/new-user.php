<?php
require_once __DIR__ . '/../app.php';

$template = new \template\NewUserPage();
$template->start();

\forms\handle_user_signup();

$errors = \session\get_form_errors('sign-up');
$fname_data = \session\get_form_data('sign-up-fname');
$email_data = \session\get_form_data('sign-up-email');
$fname_value = $fname_data ? $fname_data : "";
$email_value = $email_data ? $email_data : "";
?>

<div class='sign-up'>
    <div class='create-user'>
      <h1>Sign Up</h1>
      <form method="POST">
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
        <input type="hidden" name="status" value="new" />
        <input type="hidden" name="is_admin" value="0" />
        <input type="hidden" name="is_moderator" value="0" />
        <input type="hidden" name="is_banned" value="0" /></br>
        <input type='submit' value='Sign Up' /></br>
      </form>
      <?php if(isset($errors['email'])): ?>
        <br><span class='error'><?=$errors['email']?></span></br>
      <?php endif; ?>
      <?php if(isset($errors['fname'])): ?>
        <br><span class='error'><?=$errors['fname']?></span></br>
      <?php endif; ?>
      <?php if(isset($errors['password'])): ?>
        <br><span class='error'><?=$errors['password']?></span></br>
      <?php endif; ?>
    </div>
<?=$template->renderFooter() ?>
    </div>
<?=$template->renderNav() ?>