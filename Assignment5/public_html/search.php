<?php
require_once __DIR__ . '/../app.php';

$user = \session\get_current_user();
$template = new \template\StandardPage();
$template->title = 'Search';
$template->user = $user;
$template->start();
?>

search results
