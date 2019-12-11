<?php
require_once __DIR__ . '/../../app.php';
\session\require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Should be obvious.
    if (@$_POST['status'] == 'new') {  //Should only be called when a new post is being made on the page where this value is on the only page it's on.
        $title = @$_POST['title'];
        $description = @$_POST['description'];
        $image_url = @$_POST['image_url'];
        $category_id = @$_POST['category_id'];
        $account_id = @$_POST['account_id'];
        $values = array(
            trim($title), $description, $image_url, $account_id, $category_id
        );
        $category_of_post = \sql\Category::get_by_id($category_id);
        if (!\validation\validate_post($values)) {
            \logging\Logger::getInstance()->debug('GOOD post created.');
            $created_post_id = \sql\Post::create_post($values);
            $created_post = \sql\Post::get_by_id($created_post_id);
            header("Location: /show-post.php?id={$created_post->id}");
        } else {
            \logging\Logger::getInstance()->warn('BAD post created.');
            header("Location: /new-post.php?slug={$category_of_post->slug}");
        }
    }
}

?>
