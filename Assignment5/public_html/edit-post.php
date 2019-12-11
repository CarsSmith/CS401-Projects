<?php
require_once __DIR__ . '/../app.php';
\session\require_login();
$user = \session\get_current_user();
$post_id = $_GET['id'];
$post_being_edited = \sql\Post::get_by_id($post_id);
$pc = \sql\Category::get_by_id($post_being_edited->category_id);
$parent_cate = \sql\Category::get_by_id($pc->parent_category_id);
$post_author_id = $post_being_edited->account_id;

if(($user->id != $post_author_id) and ($user->is_moderator == false) and ($user->is_admin == false)) {
    http_response_code(404);
    echo "<h1>Not Found</h1>";
    echo "<a href='/index.php'>away you go!</a>";
    die();
}

\forms\handle_post_edit($post_being_edited->image_url);

$user = \session\get_current_user();
$template = new \template\StandardPage();
$template->title = 'Edit Post';
$template->user = $user;
$template->start();

$errors = \session\get_form_errors('post-edit');
$title_data = \session\get_form_data('post-edit-title');
$desc_data = \session\get_form_data('post-edit-description');
$title_value = $title_data ? $title_data : "";
$desc_value = $desc_data ? $desc_data : "";
?>
<div class='post-create'>
  <div id='breadcrumb'>
    <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a> | <?php echo "<a href=/category.php?slug={$parent_cate->slug}>{$parent_cate->name}</a> | <a href=/category.php?slug={$pc->slug}>{$pc->name}</a> | <a href='/show-post.php?id={$post_id}'>{$post_being_edited->title}</a> | <a href='/edit-post.php?id={$post_id}'>Edit Post</a>" ?></p>
  </div>
  <hr></hr>
  <h1>Edit Post</h1>
  <br></br>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Post Title..." value="<?=htmlentities($post_being_edited->title)?>" required=yes />
    <br></br>
    <textarea name="description" placeholder="Description..."><?=htmlentities($post_being_edited->description)?></textarea>
    <label><br></br>
      Image Upload:
      <input type="file" name="image_url" id="image_url" />
      Current Image: 
      <?php if($post_being_edited->image_url) { ?> 
        <img src=<?="/uploads/" . $post_being_edited->image_url ?> />
      <?php } else { ?>
        None
      <?php } ?>
    </label></br>
    <input type="hidden" name="post_id" value=<?php echo"'{$post_id}'" ?> />
    <input type="hidden" name="status" value="new" />
    <input class="submit-button" type="submit" value="Edit Post" />
  </form>
  <?php if(isset($errors['title'])): ?>
    </br><span class='error'><?=$errors['title']?></span>
  <?php endif; ?>
  <?php if(isset($errors['image'])): ?>
    </br><span class='error'><?=$errors['image']?></span>
  <?php endif; ?>
  <?=$template->renderFooter() ?>
</div>
<?=$template->renderNav("EDIT_POST") ?>
