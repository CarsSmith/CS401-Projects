<?php
require_once __DIR__ . '/../app.php';

$conn = \sql\conn();
$conn->exec("
  CREATE TABLE account(
    id INTEGER NOT NULL AUTO_INCREMENT,
    email VARCHAR(256) NOT NULL,
    password VARCHAR(256) NOT NULL,
    full_name VARCHAR(256) NOT NULL,
    is_admin BOOLEAN,
    is_moderator BOOLEAN,
    is_banned BOOLEAN,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    INDEX(email)
  );
");
