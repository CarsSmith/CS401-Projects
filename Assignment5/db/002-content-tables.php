<?php
require_once __DIR__ . '/../app.php';

$conn = \sql\conn();
$conn->exec("
  CREATE TABLE category(
    id INTEGER NOT NULL,
    name NVARCHAR(512),
    slug NVARCHAR(512) UNIQUE NOT NULL ,
    category_id INTEGER DEFAULT NULL ,
    PRIMARY KEY(id),
    INDEX(slug),
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL ON UPDATE CASCADE
  );
  CREATE TABLE post(
    id INTEGER NOT NULL AUTO_INCREMENT,
    title VARCHAR(2000) NOT NULL,
    description TEXT,
    image_url NVARCHAR(512),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    account_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (account_id) REFERENCES account(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
  );
  CREATE TABLE comment(
    id INTEGER NOT NULL AUTO_INCREMENT,
    text TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    account_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (account_id) REFERENCES account(id),
    FOREIGN KEY (post_id) REFERENCES post(id)
  );
");
