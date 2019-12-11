<?php
require_once __DIR__ . '/../app.php';

if (\session\get_current_user()) {
  \session\go_home();
}

$user = \session\get_current_user();
$template = new \template\MinimalPage();
$template->title = 'Login';
$template->start();
?>

<form action="/forms/user-login.php" method="POST">
  <label style="display: block;">
    Email<br />
    <input type="text" name="email" value="admin@example.com" />
  </label>
  <br />
  <label style="display: block;">
    Password<br />
    <input type="password" name="password" value="password" />
  </label>
  <br />
  <input type="submit" value="Login" /> <a href="/new-user.php">Sign Up Here</a>
</form>
