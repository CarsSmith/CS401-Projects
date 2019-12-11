<?php
require_once __DIR__ . '/../app.php';
\session\require_login();
$user = \session\get_current_user();
$comment_id = $_GET['id'];
$comment_being_edited = \sql\Comment::get_by_id($comment_id);
$post_of_comment = \sql\Post::get_by_id($comment_being_edited->post_id);
$cate_of_post = \sql\Category::get_by_id($post_of_comment->category_id);
$parent_cate = \sql\Category::get_by_id($cate_of_post->parent_category_id);
$comment_author_id = $comment_being_edited->account_id;

if(($user->id != $comment_author_id) and ($user->is_moderator == false) and ($user->is_admin == false)) {
    http_response_code(404);
    echo "<h1>Not Found</h1>";
    echo "<a href='/index.php'>away you go!</a>";
    die();
}

\forms\handle_comment_edit();

$template = new \template\StandardPage();
$template->title = 'Edit Comment';
$template->user = $user;
$template->start();

$errors = \session\get_form_errors('comment-edit');
$text_data = \session\get_form_data('comment-edit-text');
$text_value = $text_data ? $text_data : "";
?>
<div class='post-create'>
  <div id='breadcrumb'>
    <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a> | <?php echo "<a href=/category.php?slug={$parent_cate->slug}>{$parent_cate->name}</a> | <a href=/category.php?slug={$cate_of_post->slug}>{$cate_of_post->name}</a> | <a href='/show-post.php?id={$post_of_comment->id}'>{$post_of_comment->title}</a> | <a href='/edit-comment.php?id={$comment_id}'>Edit Comment</a>" ?></p>
  </div>
  <hr></hr>
  <h1>Edit Comment</h1>
  <br></br>
  <form method="POST">
    <textarea name="text" placeholder="Description..."><?=htmlentities($comment_being_edited->text)?></textarea>
    <input type="hidden" name="id" value=<?php echo"'{$comment_id}'" ?> />
    <input type="hidden" name="status" value="new" />
    <input class="submit-button" type="submit" value="Edit Comment" />
  </form>
  <?php if(isset($errors['text'])): ?>
    </br><span class='error'><?=$errors['text']?></span>
  <?php endif; ?>
  <?=$template->renderFooter() ?>
</div>
<?=$template->renderNav("EDIT_COMMENT") ?>
