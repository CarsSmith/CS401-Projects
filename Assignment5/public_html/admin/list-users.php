<?php
require_once __DIR__ . '/../../app.php';

\session\require_login();
$user = \session\get_current_user();
if (!$user->is_admin && !$user->is_moderator) {
    \session\go_home();
}

$template = new \template\AdminPage();
$template->title = 'Manage the Users';
$template->user = $user;
$template->start();

if($_GET['sort']) {
  $sort = $_GET['sort'];
} else {
  $sort = "date";
}

if($sort == "name") {
  $users = \sql\User::get_all_by_name();
} else {
  $users = \sql\User::get_all();
}
//TODO SORT BY TWO DIFFERENT THINGS name and creation date
?>

<h1>Users List</h1>
<p>Sort by <a href="/admin/list-users.php?sort=name">Name</a> or <a href="/admin/list-users.php?sort=date">Creation Date</a></p>
<ul style="padding:0;">
<?php foreach ($users as $user): ?>
  <li class='user-item'>
    <a href="/admin/edit-user.php?id=<?=$user->id?>">
      <h2><?=$user->name?></h2>
      <strong><?=$user->email?></strong>
      <code class="<?=$user->is_banned?'Y':'N'?>">is_banned</code>
      <code class="<?=$user->is_moderator?'Y':'N'?>">is_moderator</code>
      <code class="<?=$user->is_admin?'Y':'N'?>">is_admin</code>
    </a>
  </li>
<?php endforeach; ?>
</ul>
