<?php namespace sql;

function conn() {
    $logger = \logging\Logger::getInstance();
    static $conn;
    if (!$conn) {
        $logger->debug('sql connection requested');
        $user = posix_getpwuid(posix_geteuid())['name'];
        $pass = trim(file_get_contents("/home/$user/.mysql_password"));
        $dbname = $user . "_app";
        $logger->info("Connecting to: $user:xxx@localhost:$dbname");
        try {
            $conn = new \PDO("mysql:dbname=$dbname;host=localhost;", $user, $pass);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $logger->debug('sql connection successful');
        } catch (Exception $e) {
            $logger->warn('Failed to connect to MySQL');
            $logger->error($e);
        }
    } else {
        $logger->debug('sql connection shared');
    }
    return $conn;
}

class Post {

    static function delete_post($id) {
        self::delete_post_comments($id);
        $sql = "
            DELETE FROM post WHERE id = ?";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute([$id]);
        $stmt = null;
    }
    static function delete_post_comments($id) {
        $sql = "
            DELETE FROM comment WHERE post_id = ?";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute([$id]);
        $stmt = null;
    }
    static function update($values) {
        $sql = "
            UPDATE post
            SET title=?, description=?, image_url=?
            WHERE id=?
        ";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute($values);
        $stmt = null;
    }
    static function create_post($values) {
        $sql = "
            INSERT INTO post (title, description, image_url, account_id, category_id) VALUES (?, ?, ?, ?, ?)";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute($values);
        $stmt = null;
        $the_post_id = conn()->lastInsertId();
        return $the_post_id;
    }
    static function posts_select($where, $values) {
        $sql = "
            SELECT
                id, title, description, image_url, created_at, account_id, category_id
            FROM post $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $posts = array();
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $post = new \objects\Post();
                $post->id = (int)$row['id'];
                $post->title = $row['title'];
                $post->description = $row['description'];
                $post->image_url = $row['image_url'];
                $post->created_at = $row['created_at'];
                $post->account_id = $row['account_id'];
                $post->category_id = $row['category_id'];
                $posts[] = $post;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->warn(print_r($posts, true));
        return $posts;
    }
    static function post_select($where, $values) {
        $posts = self::posts_select($where, $values);
        if (count($posts) > 0) {
            return $posts[0];
        }
        return null;
    }
    static function post_count_select($where, $values) {
        $sql = "
            SELECT
                COUNT(*)
            FROM post $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $count;
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $number = $row['COUNT(*)'];
                $count = $number;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->warn(print_r($count, true));
        return $count;
    }
    static function get_all() {
        \logging\Logger::getInstance()->debug("Post::get_all()");
        return self::posts_select("", array());
    }
    static function get_by_category_id($category_id) {
        \logging\Logger::getInstance()->debug("Post::get_by_category_id($category_id)");
        return self::posts_select("WHERE category_id = ?", [$category_id]);
    }
    static function get_recent_post_by_cid($category_id) {
        \logging\Logger::getInstance()->debug("Post::get_recent_post_by_cid($category_id)");
        return self::post_select("WHERE category_id = ? ORDER BY created_at DESC LIMIT 1", [$category_id]);
    }
    static function get_post_count_by_cid($category_id) {
        \logging\Logger::getInstance()->debug("Post::get_post_count_by_cid($category_id)");
        return self::post_count_select("WHERE category_id = ?", [$category_id]);
    }
    static function get_recent_posts() {
        \logging\Logger::getInstance()->debug("Post::get_recent_posts()");
        return self::posts_select("ORDER BY created_at DESC LIMIT 4", array());
    }
    static function get_all_posts_by_cid_sorted($category_id) {
        \logging\Logger::getInstance()->debug("Post::get_all_posts_by_cid_sorted($category_id)");
        return self::posts_select("WHERE category_id = ? ORDER BY created_at DESC", [$category_id]);
    }
    static function get_all_posts_by_cid_sorted_paged($category_id, $page_i) {
        \logging\Logger::getInstance()->debug("Post::get_all_posts_by_cid_sorted_paged($category_id)");
        $all_posts = self::posts_select("WHERE category_id = ? ORDER BY created_at DESC", [$category_id]);
        $page = max(0, $page_i - 1) * 6;
        $important_posts = array_slice($all_posts, $page, 6);
        \logging\Logger::getInstance()->warn(print_r($page, true));
        \logging\Logger::getInstance()->warn(print_r($important_posts, true));
        return $important_posts;
    }
    static function get_page_posts_by_cid($category_id, $page) {
        $sql = "SELECT 
                    * 
                FROM post WHERE category_id = ? ORDER BY created_at DESC LIMIT :start , 6";
        $values = array( $category_id, $page );
        $page = max(0, $page - 1) * 6;
        $posts = array();
        $stmt = conn()->prepare($sql);
        //$stmt->bindValue(':fart', (int) $category_id, \PDO::PARAM_INT);
        $stmt->bindValue(':start', (int) $page, \PDO::PARAM_INT);
        if ($stmt->execute($category_id)) {
            foreach($stmt->fetchAll() as $row) {
                $post = new \objects\Post();
                $post->id = (int)$row['id'];
                $post->title = $row['title'];
                $post->description = $row['description'];
                $post->image_url = $row['image_url'];
                $post->created_at = $row['created_at'];
                $post->account_id = $row['account_id'];
                $post->category_id = $row['category_id'];
                $posts[] = $post;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->debug("Post::getNEFASEGSAGEASGEASGASEGASGESA({$category_ID})");
        \logging\Logger::getInstance()->warn(print_r($posts, true));
        return $posts;
    }
    static function get_by_title($title) {
        \logging\Logger::getInstance()->debug("Post::get_by_title($title)");
        return self::post_select("WHERE title = ? ORDER BY created_at DESC", [$title]);
    }
    static function get_by_id($id) {
        \logging\Logger::getInstance()->debug("Post::get_by_id($id)");
        return self::post_select("WHERE id = ?", [$id]);
    }
    static function get_page_count($category_id) {
        $total = get_post_count_by_cid($category_id);
        return (ceil($total / 10));
    }
}
class Category {
    static function categories_select($where, $values) {
        $sql = "
            SELECT
                id, name, slug, category_id
            FROM category $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $categories = array();
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $category = new \objects\Category();
                $category->id = (int)$row['id'];
                $category->name = $row['name'];
                $category->slug = $row['slug'];
                $category->parent_category_id = $row['category_id'];
                $categories[] = $category;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->debug(print_r($categories, true));
        return $categories;
    }
    static function category_select($where, $values) {
        $categories = self::categories_select($where, $values);
        if (count($categories) > 0) {
            return $categories[0];
        }
        return null;
    }
    static function cposts_select($where, $values) {
        $sql = "
            SELECT
                post.id, post.title, post.description, post.image_url, post.created_at, post.account_id, post.category_id
            FROM category $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $cposts = array();
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $post = new \objects\Post();
                $post->id = (int)$row['id'];
                $post->title = $row['title'];
                $post->description = $row['description'];
                $post->image_url = $row['image_url'];
                $post->created_at = $row['created_at'];
                $post->account_id = $row['account_id'];
                $post->category_id = $row['category_id'];
                $cposts[] = $post;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->debug(print_r($cposts, true));
        return $cposts;
    }
    static function cpost_select($where, $values) {
        $cposts = self::cposts_select($where, $values);
        if (count($cposts) > 0) {
            return $cposts[0];
        }
        return null;
    }
    static function post_count($where, $values) {
        $sql = "
            SELECT
                COUNT(*)
            FROM category $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $count;
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $number = $row['COUNT(*)'];
                $count = $number;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->debug(print_r($count, true));
        return $count;
    }
    static function get_all() {
        \logging\Logger::getInstance()->debug("Category::get_all()");
        return self::categories_select("", array());
    }
    static function get_by_parent_cate_id($parent_category_id) {
        \logging\Logger::getInstance()->debug("Category::get_by_parent_cate_id($parent_category_id)");
        return self::categories_select("WHERE category_id = ?", [$parent_category_id]);
    }
    static function get_by_id($id) {
        \logging\Logger::getInstance()->debug("Category::get_by_id($id)");
        return self::category_select("WHERE id = ?", [$id]);
    }
    static function get_top_level() {
        \logging\Logger::getInstance()->debug("Category::get_top_level()");
        return self::categories_select("WHERE category_id IS NULL", array());
    }
    static function get_most_recent_post_by_top_level_cid($id) {
        \logging\Logger::getInstance()->debug("Category::get_most_recent_post_by_top_level_cid($id)");
        return self::cpost_select("LEFT JOIN post ON post.category_id = category.id WHERE category.category_id = ? ORDER BY post.created_at DESC LIMIT 1", [$id]);
    }
    static function get_by_slug($slug) {
        \logging\Logger::getInstance()->debug("Category::get_by_slug($slug)");
        return self::category_select("WHERE slug = ?", [$slug]);
    }
    static function get_tlpost_count($id) {
        \logging\Logger::getInstance()->debug("Category::get_post_count($id)");
        return self::post_count("LEFT JOIN post ON post.category_id = category.id WHERE category.category_id = ?", [$id]);
    }
    static function get_subcategory_count($id) {
        \logging\Logger::getInstance()->debug("Category::get_subcategory_count($id)");
        return self::post_count("WHERE category_id = ?", [$id]);
    }
    static function get_by_name($name) {
        \logging\Logger::getInstance()->debug("Category::get_by_name($name)");
        return self::category_select("WHERE name = ?", [$name]);
    }
}

class Comment {
    static function update($values) {
        $sql = "
            UPDATE comment
                SET text=?
            WHERE id=?";
        $stmt = conn()->prepare($sql);
        $stmt->execute($values);
        $stmt = null;
        $the_comment_id = conn()->lastInsertId();
        return $the_comment_id;
    }
    static function create_comment($values) {
        $sql = "
            INSERT INTO comment (text, account_id, post_id) VALUES (?, ?, ?)";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute($values);
        $stmt = null;
        $the_comment_id = conn()->lastInsertId();
        return $the_comment_id;
    }
    static function comments_select($where, $values) {
        $sql = "
            SELECT
                *
            FROM comment $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $comments = array();
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $comment = new \objects\Comment();
                $comment->id = (int)$row['id'];
                $comment->text = $row['text'];
                $comment->created_at = $row['created_at'];
                $comment->account_id = $row['account_id'];
                $comment->post_id = $row['post_id'];
                $comments[] = $comment;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->debug(print_r($comments, true));
        return $comments;
    }
    static function comment_select($where, $values) {
        $comments = self::comments_select($where, $values);
        if (count($comments) > 0) {
            return $comments[0];
        }
        return null;
    }
    static function delete_comment($values) {
        $sql = "
          DELETE FROM comment WHERE id = ?";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute([$values]);
        $stmt = null;
        return null;
    }
    static function get_by_post_id($post_id) {
        \logging\Logger::getInstance()->debug("Comment::get_by_post_id($post_id)");
        return self::comments_select("WHERE post_id = ?", [$post_id]);
    }
    static function get_by_id($comment_id) {
        \logging\Logger::getInstance()->debug("Comment:get_by_id($comment_id)");
        return self:: comment_select("WHERE id = ?", [$comment_id]);
    }
}

class User {
    static function create_user($values) {
        $sql = "
            INSERT INTO account (email, full_name, password, is_admin, is_moderator, is_banned) VALUES (?, ?, ?, ?, ?, ?)";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $stmt->execute($values);
        $stmt = null;
        $the_user_id = conn()->lastInsertId();
        return $the_user_id;
    }
    static function update($user) {
        $sql = "
            UPDATE account
            SET full_name=?, is_banned=?, is_admin=?, is_moderator=?
            WHERE id=?
        ";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $values = array(
            $user->name,
            $user->is_banned, $user->is_admin,
            $user->is_moderator, $user->id
        );
        $stmt->execute($values);
        $stmt = null;
    }
    static function users_select($where, $values) {
        $sql = "
            SELECT
                id, email, full_name,
                is_banned, is_moderator, is_admin,
                created_at, updated_at
            FROM account $where";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        $users = array();
        if ($stmt->execute($values)) {
            foreach($stmt->fetchAll() as $row) {
                $user = new \objects\User();
                $user->id = (int)$row['id'];
                $user->email = $row['email'];
                $user->name = $row['full_name'];
                $user->is_banned = (bool)$row['is_banned'];
                $user->is_moderator = (bool)$row['is_moderator'];
                $user->is_admin = (bool)$row['is_admin'];
                $user->created_at = $row['created_at'];
                $user->updated_at = $row['updated_at'];
                $users[] = $user;
            }
        }
        $stmt = null;
        \logging\Logger::getInstance()->debug(print_r($users, true));
        return $users;
    }
    
    static function user_select($where, $values) {
        $users = self::users_select($where, $values);
        if (count($users) > 0) return $users[0];
        return null;
    }
    static function get_all() {
        \logging\Logger::getInstance()->debug("User::get_all()");
        return self::users_select("", array());
    }
    static function get_all_by_name() {
        \logging\Logger::getInstance()->debug("User::get_all_by_name()");
        return self::users_select("ORDER BY full_name", array());
    }
    static function get_by_id($id) {
        \logging\Logger::getInstance()->debug("User::get_by_id($id)");
        return self::user_select("WHERE id = ?", [$id]);
    }
    static function get_by_email($email) {
        \logging\Logger::getInstance()->debug("User::get_by_email($email)");
        return self::user_select("WHERE email = ?", [$email]);
    }
    
    function is_valid_login($email, $password) {
        $sql = "
            SELECT count(*) AS found
            FROM account
            WHERE email = ? AND password = ?
            AND is_banned IS NULL
        ";
        \logging\Logger::getInstance()->info($sql);
        $stmt = conn()->prepare($sql);
        if ($stmt->execute(array($email, sha1($password)))) {
            list($found) = $stmt->fetch();
            \logging\Logger::getInstance()->debug('found creds? ' . ($found ? 'true' : 'false'));
            $stmt = null;
            return $found;
        } else {
            \logging\Logger::getInstance()->warn('failed to lookup user');
            return false;
        }
    }
}
