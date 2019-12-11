<?php

require_once __DIR__ . '/../../app.php';
\session\require_login();
$user = \session\get_current_user();
$post_id = @$_POST['id'];
$post = \sql\Post::get_by_id($post_id); //Post ref
$post_author_id = $post->account_id;
$page_category = \sql\Category::get_by_id($post->category_id);
\logging\Logger::getInstance()->debug("POST ID: {$post_id}");
\logging\Logger::getInstance()->debug(print_r($post, true));
\logging\Logger::getInstance()->debug("POST AUTHOR ID: {$post_author_id}");
\logging\Logger::getInstance()->debug(print_r($page_category, true));
if(($user->id != $post_author_id) and ($user->is_moderator == false) and ($user->is_admin == false)) {
    http_response_code(404);
    echo "<h1>Delete Post Not Found OR Ivalid Permissions</h1>";
    echo "<a href='/index.php'>Return to the Home Page!</a>";
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
    $returnval = \sql\Post::delete_post($post_id);     //This will also delete the comments under the post.
    header("Location: /category.php?slug={$page_category->slug}");
}
?>