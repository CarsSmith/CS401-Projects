<?php
require_once __DIR__ . '/../../app.php';

\session\require_login();
$user = \session\get_current_user();
if (!$user->is_admin && !$user->is_moderator) {
    \session\go_home();
}

$template = new \template\AdminPage();
$template->title = 'Admin Page';
$template->user = $user;
$template->start();
?>
<h1>Admin Page</h1>
<div class='main-nav'>
  <?=$template->renderNav() ?>
</div>