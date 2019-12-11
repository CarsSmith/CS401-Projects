<?php namespace objects;

class User {
    public $id;
    public $name;
    public $email;
    public $is_admin = false;
    public $is_moderator = false;
    public $is_banned = false;
    public $created_at;
    public $updated_at;
}

class Category {
    public $id;
    public $name;
    public $slug;
    public $parent_category_id;
}

class Post {
    public $id;
    public $title;
    public $description;
    public $image_url;
    public $created_at;
    public $account_id;
    public $category_id;
}

class Comment {
    public $id;
    public $text;
    public $created_at;
    public $account_id;
    public $post_id;
}
