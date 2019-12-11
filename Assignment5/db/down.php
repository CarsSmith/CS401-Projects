<?php
require_once __DIR__ . '/../app.php';

function drop_table ($table) {
    $conn = \sql\conn();
    $conn->exec("
        SET foreign_key_checks = 0;
        DELETE FROM $table;
        DROP TABLE IF EXISTS $table;
        SET foreign_key_checks = 1;
    ");
}

drop_table("admin");
drop_table("user");
drop_table("moderator");
drop_table("account");
drop_table("comment");
drop_table("post");
drop_table("category");
