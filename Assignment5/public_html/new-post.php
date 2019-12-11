<?php
require_once __DIR__ . '/../app.php';
\session\require_login();
$user = \session\get_current_user();
$page_category_slug = $_GET['slug'];
$pc = \sql\Category::get_by_slug($page_category_slug);
$parent = \sql\Category::get_by_id($pc->parent_category_id);

\forms\handle_post_creation();

$template = new \template\StandardPage();
$template->title = 'Post';
$template->user = $user;
$template->start();

$errors = \session\get_form_errors('post-new');
$title_data = \session\get_form_data('post-new-title');
$desc_data = \session\get_form_data('post-new-description');
$title_value = $title_data ? $title_data : "";
$desc_value = $desc_data ? $desc_data : "";
?>
<div class='post-create'>
  <div id='breadcrumb'>
    <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a> | <?php echo "<a href=/category.php?slug={$parent->slug}>{$parent->name}</a> | <a href=/category.php?slug={$pc->slug}>{$pc->name}</a> | <a href='/new-post.php?slug={$pc->slug}'>New Post</a>" ?></p>
  </div>
  <hr></hr>
  <h1>Create a Post</h1>
  <br></br>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Post Title..." value="<?=htmlentities($title_value)?>" required=yes />
    <br></br>
    <textarea name="description" placeholder="Description..."><?=htmlentities($desc_value)?></textarea>
    <label><br></br>
      Image Upload:
      <input type="file" name="image_url" id="image_url" />
    </label></br>
    <input type="hidden" name="category_id" value=<?php echo"'{$pc->id}'" ?> />
    <input type="hidden" name="account_id" value=<?php echo"'{$user->id}'" ?> />
    <input type="hidden" name="status" value="new" />
    <input class="submit-button" type="submit" value="Create Post" />
  </form>
  <?php if(isset($errors['title'])): ?>
    </br><span class='error'><?=$errors['title']?></span>
  <?php endif; ?>
  <?php if(isset($errors['image'])): ?>
    </br><span class='error'><?=$errors['image']?></span>
  <?php endif; ?>
  <?=$template->renderFooter() ?>
</div>
<?=$template->renderNav("POST") ?>