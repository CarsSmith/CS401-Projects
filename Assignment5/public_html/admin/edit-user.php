<?php
require_once __DIR__ . '/../../app.php';

\session\require_login();
$authed = \session\get_current_user();

$user = \sql\User::get_by_id(@$_GET['id']);
if (!$user) {
  http_response_code(404);
  echo "<h1>Not Found</h1>";
  echo "<a href='/index.php'>away you go!</a>";
  die();
}

\forms\handle_user_update(); //Does this even do anything?

$template = new \template\AdminPage();
$template->title = "Editing {$user->email}";
$template->body_class = "user-edit";
$template->user = $authed;
$template->start();

$errors = \session\get_form_errors('user-update');
$data = \session\get_form_data('user-update');
$message = \session\get_form_message('user-update');
$name_value = $data ? $data->name : $user->name;
?>
<h1>User Editor</h1>

<aside>
  <label>ID:</label> <strong><?=$user->id?></strong><br />
  <label>Email:</label> <strong><?=$user->email?></strong><br />
  <label>Created At:</label> <strong><?=$user->created_at?></strong><br />
  <label>Last Updated:</label> <strong><?=$user->updated_at?></strong><br />
</aside>

<?php if (!empty($message)): ?>
  <br />
  <span class='form-message'><?=$message?></span>
<?php endif; ?>

<form method="POST">
  <input type="hidden" name="id" value="<?=$user->id?>"/>

  <label>
    <h2>Name:</h2>
<?php if(isset($errors['name'])): ?>
      <span class='error'><?=$errors['name']?></span>
<?php endif; ?>
    <input type="text" name="name" value="<?=htmlentities($name_value)?>" />
  </label>

  <h2>Attributes:</h2>
<?php if($authed->is_admin || $authed->is_moderator): ?>
    <label>
      <input type="checkbox" value="1" <?=$user->is_banned?"checked":""?> name="is_banned" />
      is_banned
    </label>
    <label>
      <input type="checkbox" value="1" <?=$user->is_moderator?"checked":""?> name="is_moderator" />
      is_moderator
    </label>
<?php endif; ?>
<?php if($authed->is_admin): ?>
    <label>
      <input type="checkbox" value="1" <?=$user->is_admin?"checked":""?> name="is_admin" />
      is_admin
    </label>
<?php endif; ?>

  <input type="submit" value="Update User" />
  <input type="reset" value="Reset" />
  <a href='/admin/list-users.php'>back to users</a>
</form>
