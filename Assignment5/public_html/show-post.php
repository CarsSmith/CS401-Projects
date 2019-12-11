<?php
   require_once __DIR__ . '/../app.php';
   
   $user = \session\get_current_user();
   
   $post_id       = $_GET['id'];
   $cp            = \sql\Post::get_by_id($post_id);
   $page_category = \sql\Category::get_by_id($cp->category_id);
   $parent        = \sql\Category::get_by_id($page_category->parent_category_id);
   $page_comments = \sql\Comment::get_by_post_id($post_id);
   
   $test_thingy = \sql\Post::get_by_id($post_id);
   if(!$post_id or !$test_thingy) {
      http_response_code(404);
      echo "<h1>Not Found</h1>";
      echo "<a href='/index.php'>This is not a valid Post, so away you go!</a>";
      die();
   }

   \forms\handle_comment_creation();
   
   if ($cp->image_url) {
       $post_image = $cp->image_url;
   }
   $post_author = \sql\User::get_by_id($cp->account_id);
   
   if (strlen($cp->title) > 20) {
       $smol_title = substr($cp->title, 0, 17);
       $smol_title = $smol_title . "...";
   } else {
       $smol_title = $cp->title;
   }
   $template        = new \template\StandardPage();
   $template->title = 'Post';
   $template->user  = $user;
   $template->start();
   
   $errors     = \session\get_form_errors('comment-new');
   $text_data  = \session\get_form_data('comment-new-text');
   $text_value = $text_data ? $text_data : "";
   ?>
<div id='main-content-2'>
   <div id='breadcrumb'>
      <p><a href='/index.php'>Welcome</a> | <a href='/category.php'>Categories</a> | <?php
         echo "<a href=/category.php?slug={$parent->slug}>{$parent->name}</a> | <a href=/category.php?slug={$page_category->slug}>{$page_category->name}</a> | <a href=/show-post.php?id={$cp->id}>{$smol_title}</a>";
         ?></p>
   </div>
   <?php
      if (($user->id == $post_author->id) or ($user->is_moderator) or ($user->is_admin)) {
      ?>
   <h1 class="post-title-2"><?= $cp->title ?></h1>
   <p class="edit-button"><a href= <?php
      echo "/edit-post.php?id={$post_id}";
      ?>>Edit</a></p>
   <?php
      } else {
      ?>
   <h1 class="post-title"><?= $cp->title ?></h1>
   <?php
      }
      ?>
   <div id='post-content'>
      <?php
         if ($cp->image_url) {
         ?>
      <div class='image-area'>
         <img src='/uploads/<?= $post_image ?>' />
      </div>
      <?php
         }
         ?>
      <div class='post-area'>
         <p><?= $cp->description ?></p>
      </div>
      <dl>
         <p>Author: <?php
            echo "{$post_author->name}";
            ?> | Posted On: <?php
            echo "{$cp->created_at}";
            ?></p>
            <?php if (($user->id == $post_author->id) or ($user->is_moderator) or ($user->is_admin)) { ?>
            <form class='del-button' action="/forms/post-delete.php" method="POST" >
                <input type="hidden" name="id" value=<?= $cp->id ?> />
                <input type="submit" value="Delete Post">
            </form>
            <?php } ?>
      </dl>
   </div>
   <div class='comment-section'>
      <h2>Comments</h2>
      <?php
         if ($user->email):
         ?>
      <ul>
         <?php
            foreach ($page_comments as $comment):
                $comment_author = \sql\User::get_by_id($comment->account_id);
            ?>
         <li>
            <div class="upper-level">
               <?php
                  if (($user->id == $comment_author->id) or ($user->is_moderator) or ($user->is_admin)):
                  ?>
               <div class="epost">
                  <dl class="lower-level">
                     <dt>Posted By:</dt>
                     <dd><?php
                        echo "{$comment_author->name}";
                        ?></dd>
                     <dt>On:</dt>
                     <dd><?php
                        echo "{$comment->created_at}";
                        ?></dd>
                     <dt><a href=<?php echo "/edit-comment.php?id={$comment->id}" ?>>Edit Comment</a></dt>
                     <dd>
                        <form action="/forms/comment-delete.php" method="POST" >
                           <input type="hidden" name="id" value=<?= $comment->id ?> />
                           <input type="submit" value="Delete Comment">
                        </form>
                     </dd>
                  </dl>
                  <p><?= $comment->text ?></p>
               </div>
            </div>
            <?php
               else:
               ?>
            <div class="nepost">
               <dl class="lower-level">
                  <dt>Posted By:</dt>
                  <dd><?php
                     echo "{$comment_author->name}";
                     ?></dd>
                  <dt>On:</dt>
                  <dd><?php
                     echo "{$comment->created_at}";
                     ?></dd>
               </dl>
               <p><?= $comment->text ?></p>
            </div>
   </div>
   <?php
      endif;
      ?>
   </li>
   <?php
      endforeach;
      ?>
   </ul>
   <hr>
   </hr></br>
   <div class='new-comment'>
      <form method="POST">
         <textarea class='comment-ta' name="text" placeholder="Comment..."><?= htmlentities($text_value) ?></textarea>
         <br></br>
         <input type="hidden" name="account_id" value=<?= $user->id ?> />
         <input type="hidden" name="post_id" value=<?= $post_id ?> />
         <input class="submit-button" type="submit" value="Post Comment" />
         <?php
            if (isset($errors['text'])):
            ?>
         </br><span class='error'><?= $errors['text'] ?></span>
         <?php
            endif;
            ?>
      </form>
   </div>
   <?php
      else:
      ?>
   <p><a href="index.php">Sign in</a> to View the Comments</p>
   <?php
      endif;
      ?>
</div>
<?= $template->renderFooter() ?>
</div>
<?= $template->renderNav("SHOW_POST") ?>