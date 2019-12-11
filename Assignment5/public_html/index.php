<?php
require_once __DIR__ . '/' . '../app.php';

$user = \session\get_current_user();

$template = new \template\StandardPage();
$template->user = $user;
$template->start();

$users = \sql\User::get_all();
$categories = \sql\Category::get_top_level();
$recent_posts = \sql\Post::get_recent_posts();
?>
<?php if($user-email) { ?>
  <div id='main-content'> 
    <div id='breadcrumb'>
      <p><a href='/index.php'>Welcome</a></p>
    </div>
    <?php if($recent_posts) : ?>
    <div id='recent-posts'>
      <h1>Recent Posts</h1>
      <ul>
      <?php foreach($recent_posts as $rp) :
        $rp_user = sql\User::get_by_id($rp->account_id);
        $rp_category = \sql\Category::get_by_id($rp->category_id);
        if($rp->image_url) {
            $rp_image = "/uploads/{$rp->image_url}";
        } else {
            $rp_image = "";
        }
        ?>
        <li>
        <div class='card'>
          <a href=<?php echo "/show-post.php?id={$rp->id}" ?>>
            <h2><?php echo "{$rp->title}" ?></h2>
          </a>
          <?php if($rp_image) { ?>
            <?php if(exif_imagetype("uploads/{$rp->image_url}") == IMAGETYPE_GIF) { ?>
              <div class="image-prev">
                <a href=<?php echo "/show-post.php?id={$rp->id}" ?>>
                  <img class="other-image" src=<?= $rp_image ?> />
                </a>
              </div>
            <?php } else { ?>
              <div class="image-prev">
                <a href=<?php echo "/show-post.php?id={$rp->id}" ?>>
                  <img class="other-image" src=<?= $rp_image ?> />
                </a>
              </div>
            <?php } ?>
          <?php } else { 
            $desc_prev = substr($rp->description, 0, 50) ?>
            <div class="disc-prev">
              <p class="preview">Discussion Preview:</p>
              <p class="preview-s"><?= $desc_prev ?></p>
            </div>
          <?php } ?>
          <dl>
            <dt>Author:</dt>
            <dd><?php echo "{$rp_user->name}" ?></dd>
            <dt>Posted On:</dt>
            <dd><?php echo "{$rp->created_at}" ?></dd>
            <dt>Category:</dt>
            <dd><a href=<?php echo "/category.php?slug={$rp_category->slug}" ?>><?php echo "{$rp_category->name}" ?></a></dd>
          </dl>
        </div>
        </li>
      <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <?php if($categories): ?>
      <div id='subforum-list'>
        <h1>Forums</h1>
        <ul>
          <?php foreach($categories as $cate): ?>
          <li>
            <div class='card-box'>
              <?php $latest_post = \sql\Category::get_most_recent_post_by_top_level_cid($cate->id); 
                    $latest_post_category = \sql\Category::get_by_id($latest_post->category_id); 
                    $latest_post_user = \sql\User::get_by_id($latest_post->account_id); ?>
              <a href=<?php echo "/category.php?slug={$cate->slug}" ?>><h2><?php echo "{$cate->name}" ?></h2></a>
              <dl>
                <dt>Latest Post:</dt>
                <dd><a href=<?php echo "/show-post.php?id={$latest_post->id}" ?>><?php echo "{$latest_post->title}"; ?></a></dd>
                <dt>Author:</dt>
                <dd><?php echo "{$latest_post_user->name}"; ?></dd>
                <dt>Posted On:</dt>
                <dd><?php echo "{$latest_post->created_at}"; ?></dd>
                <dt>Category:</dt>
                <dd><a href= <?php echo "/category.php?slug={$latest_post_category->slug}" ?>><?php echo "{$latest_post_category->name}"; ?></a></dd>
              </dl>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
<?php } else { ?>
    <div class='login'>
    <div class='login-user'>
      <h1>Login Page</h1>
      <form action='/forms/user-login.php' method='POST'>
        <div id="forms">
          <div id="labels">
            <label for=email>Email: </label></br>
            <label for=password>Password: </label></br>
          </div>
          <div id="inputs">
            <input id=email type='text' name='email' value='admin@example.com' /></br>
            <input id=password type='password' name='password' value='password' /></br>
          </div>
        </div>
        </br>
        <input class="submit-button" type='submit' value='Login' /><br></br>
        <p>Sign up <a href="/new-user.php">Here</a></p>
      </form>
    </div>
<?php } ?>
<?=$template->renderFooter() ?>
    </div>
<?=$template->renderNav("HOME") ?>