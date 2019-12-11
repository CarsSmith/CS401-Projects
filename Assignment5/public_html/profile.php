<?php
require_once __DIR__ . '/../app.php';

\session\require_login();
$user = \session\get_current_user();

//\forms\handle_profile_update();

$template = new \template\StandardPage();
$template->title = "My Profile";
$template->body_class = "profile-edit";
$template->user = $user;
$template->start();

$errors = \session\get_form_errors('user-update');
$data = \session\get_form_data('user-update');
$message = \session\get_form_message('user-update');
$name_value = $data ? $data->name : $user->name;
$pass_value = $data ? $data->password : "";
$match_value = $data ? $data->password_match: "";
?>
<h1>My Profile</h1>

<aside>
  <label>Email:</label> <strong><?=$user->email?></strong><br />
  <label>Created At:</label> <strong><?=$user->created_at?></strong><br />
</aside>

<?php if (!empty($message)): ?>
  <br />
  <span class='form-message'><?=$message?></span>
<?php endif; ?>

<form method="POST">
  <label>
    <h2>Name:</h2>
<?php if(isset($errors['name'])): ?>
    <span class='error'><?=$errors['name']?></span>
<?php endif; ?>
    <input type="text" name="name" value="<?=htmlentities($name_value)?>" />
  </label>

  <label>
    <h2>Password:</h2>
<?php if(isset($errors['password'])): ?>
    <span class='error'><?=$errors['password']?></span>
<?php endif; ?>
    <input type="password" name="password" value="<?=htmlentities($pass_value)?>" />
  </label>
  <label>
    <h2>Password Again:</h2>
<?php if(isset($errors['password_match'])): ?>
    <span class='error'><?=$errors['password_match']?></span>
<?php endif; ?>
    <input type="password" name="password_match" value="<?=htmlentities($match_value)?>" />
  </label>

  <input type="submit" value="Save" />
  <input type="reset" value="Reset" />
</form>
