<?php

require_once __DIR__ . '/../../app.php';
\session\require_login();
$user = \session\get_current_user();
$comment_id = @$_POST['id'];
$comment = \sql\Comment::get_by_id($comment_id); //Comment ref
$post = \sql\Post::get_by_id($comment->post_id); //Post ref
$comment_author_id = $comment->account_id;

if(($user->id != $comment_author_id) and ($user->is_moderator == false) and ($user->is_admin == false)) {
    http_response_code(404);
    echo "<h1>Not Found</h1>";
    echo "<a href='/index.php'>away you go!</a>";
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
    \logging\Logger::getInstance()->debug('IT GETS HERE DUDE IDK WHATS HAPPENING'); //NOT WORKING FOR ?????? REASONS
    \logging\Logger::getInstance()->debug("COMMENT ID: {$comment_id}");
    $returnval = \sql\Comment::delete_comment($comment_id);
    header("Location: /show-post.php?id={$post->id}");
}
?>