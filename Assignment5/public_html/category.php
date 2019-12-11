<?php
require_once __DIR__ . '/../app.php';

$user = \session\get_current_user();
$page_category_slug = $_GET['slug'];
$page_category = \sql\Category::get_by_slug($page_category_slug);
$template = new \template\CategoryPage();

  


if($page_category_slug) {
  $slugless = False;
  
  if($page_category->parent_category_id && $slugless == False) {
    $top_level = False;
    $template->title = "{$page_category->name}";
    if(!$page_category) {
      http_response_code(404);
      echo "<h1>Not Found</h1>";
      echo "<a href='/index.php'>This is not a valid Category, so away you go!</a>";
      die();
    }
  } else {
    $top_level = True;
    $template->title = "{$page_category->name}";
  }
} else {
  $slugless = True;
  $template->title = 'Forum Categories';
}

$page = max((int) @$_GET['page'], 1);
$total = \sql\Post::get_post_count_by_cid($page_category->id);
$last = (ceil($total / 6));
$prev = $page - 1;
$next = $page + 1;

$template->user = $user;
$template->start();
?>

<div id='main-content'> 
  <?php if($slugless) : ?>
    <div id='breadcrumb'>
      <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a></p>
    </div>
    <h1>Forum Categories</h1>
    <div id='subforum-list'>
      <ul>
      <?php
       $categories = \sql\Category::get_top_level();
       foreach($categories as $cate): ?>
         <li>
           <div class='card-box'>
             <?php $latest_post = \sql\Category::get_most_recent_post_by_top_level_cid($cate->id); 
                   $latest_post_category = \sql\Category::get_by_id($latest_post->category_id); 
                   $latest_post_user = \sql\User::get_by_id($latest_post->account_id);
                   $pc = \sql\Category::get_tlpost_count($cate->id);
                   $scc = \sql\Category::get_subcategory_count($cate->id); ?> 
             <h2><a href=<?php echo "/category.php?slug={$cate->slug}&?page=1" ?>><?php echo "{$cate->name}" ?></a></h2>
             <p class='cate-stats'><?php echo "Total Posts: {$pc} | Sub-Categories: {$scc}"; ?></p>
             <dl> 
               <dt>Latest Post:</dt>
               <dd><a href=<?php echo "/show-post.php?id={$latest_post->id}" ?>><?php echo "{$latest_post->title}"; ?></a></dd>
               <dt>Author:</dt>
               <dd><?php echo "{$latest_post_user->name}"; ?></dd>
               <dt>Posted On:</dt>
               <dd><?php echo "{$latest_post->created_at}"; ?></dd>
               <dt>Category:</dt>
               <dd><a href= <?php echo "/category.php?slug={$latest_post_category->slug}&?page=1" ?>><?php echo "{$latest_post_category->name}"; ?></a></dd>
             </dl>
           </div>
         </li>
      <?php endforeach; ?>
      </ul>
    </div>
    
    
  <?php else : ?>
    <?php if($top_level) : ?>
      <div id='breadcrumb'>
        <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a> | <a href=<?php echo "/category.php?slug={$page_category->slug}" ?>><?php echo"{$page_category->name}" ?></a></p>
      </div>
      <h1><?php echo "{$page_category->name} Sub-Categories" ?></h1>
      <div id='subforum-list'>
      <ul>
      <?php
       $categories = \sql\Category::get_by_parent_cate_id($page_category->id);
       foreach($categories as $cate): ?>
         <li>
           <div class='card-box'>
             <?php $latest_post = \sql\Post::get_recent_post_by_cid($cate->id); 
                   $latest_post_category = \sql\Category::get_by_id($latest_post->category_id); 
                   $latest_post_user = \sql\User::get_by_id($latest_post->account_id);
                   $pc = \sql\Post::get_post_count_by_cid($cate->id); ?> 
             <h2><a href=<?php echo "/category.php?slug={$cate->slug}&?page=1" ?>><?php echo "{$cate->name}" ?></a></h2>
             <p class='cate-stats'><?php echo "Total Posts: {$pc}" ?></p>
             <dl> 
               <dt>Latest Post:</dt>
               <dd><a href=<?php echo "/show-post.php?id={$latest_post->id}" ?>><?php echo "{$latest_post->title}"; ?></a></dd>
               <dt>Author:</dt>
               <dd><?php echo "{$latest_post_user->name}"; ?></dd>
               <dt>Posted On:</dt>
               <dd><?php echo "{$latest_post->created_at}"; ?></dd>
               <dt>Category:</dt>
               <dd><a href= <?php echo "/category.php?slug={$latest_post_category->slug}&?page=1" ?>><?php echo "{$latest_post_category->name}"; ?></a></dd>
             </dl>
           </div>
         </li>
      <?php endforeach; ?>
      </ul>
    </div>
    <?php else : 
      $parent = \sql\Category::get_by_id($page_category->parent_category_id); ?>
      <div id='breadcrumb'>
        <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a> | <?php echo "<a href=/category.php?slug={$parent->slug}>{$parent->name}</a> | <a href=/category.php?slug={$page_category->slug}>{$page_category->name}</a></p>" ?>
      </div>
      <h1><?php echo "{$page_category->name} Posts" ?></h1>
      Page <?=$page?> of <?=$last?> (<?=$total?> Posts Total)
      <?php if ($page > 1): ?><a href=<?="category.php?slug={$page_category->slug}&page=1"?>>first</a><?php endif ?>
      <?php if ($page > 2): ?><a href=<?="category.php?slug={$page_category->slug}&page={$prev}"?>>prev</a><?php endif ?>
      <?php if ($page < $last - 1): ?><a href=<?="category.php?slug={$page_category->slug}&page={$next}"?>>next</a><?php endif ?>
      <?php if ($page < $last): ?><a href=<?="category.php?slug={$page_category->slug}&page=$last"?>>last</a><?php endif ?>
      <ul>
      <?php $posts = \sql\Post::get_all_posts_by_cid_sorted_paged($page_category->id, $page);
      foreach($posts as $p) : 
        $p_user = sql\User::get_by_id($p->account_id);
        if($p->image_url) {
            $p_image = "/uploads/{$p->image_url}";
        } else {
            $p_image = "";
        } ?>
        <li>
        <div class='card'>
          <a href=<?php echo "/show-post.php?id={$p->id}" ?>>
            <h2><?php echo "{$p->title}" ?></h2>
          </a>
            <?php if($p_image) { ?>
              <div class="image-prev">
              <a href=<?php echo "/show-post.php?id={$p->id}" ?>>
                <img src=<?= $p_image ?> />
              </a>
              </div>
            <?php } else { 
              $desc_prev = substr($p->description, 0, 50) ?>
              <div class="disc-prev">
                <p class="preview">Discussion Preview:</p>
                <p class="preview-s"><?= $desc_prev ?></p>
              </div>
            <?php } ?>
          <dl>
            <dt>Author:</dt>
            <dd><?php echo "{$p_user->name}" ?></dd>
            <dt>Posted On:</dt>
            <dd><?php echo "{$p->created_at}" ?></dd>
          </dl>
        </div>
        </li>
      <?php endforeach; ?>
      </ul>
      Page <?=$page?> of <?=$last?> (<?=$total?> Posts Total)
      <?php if ($page > 1): ?><a href=<?="category.php?slug={$page_category->slug}&page=1"?>>first</a><?php endif ?>
      <?php if ($page > 2): ?><a href=<?="category.php?slug={$page_category->slug}&page={$prev}"?>>prev</a><?php endif ?>
      <?php if ($page < $last - 1): ?><a href=<?="category.php?slug={$page_category->slug}&page={$next}"?>>next</a><?php endif ?>
      <?php if ($page < $last): ?><a href=<?="category.php?slug={$page_category->slug}&page=$last"?>>last</a><?php endif ?>
    <?php endif; ?>
  <?php endif; ?> 
<?=$template->renderFooter() ?>
</div>
<?=$template->renderNav($page_category) ?>