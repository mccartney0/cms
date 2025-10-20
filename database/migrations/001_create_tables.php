<?php

return [
    'CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        excerpt TEXT,
        body TEXT NOT NULL,
        meta_title TEXT,
        meta_description TEXT,
        status TEXT NOT NULL DEFAULT "draft",
        published_at TEXT,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )',
    'CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )',
    'CREATE TABLE IF NOT EXISTS post_category (
        post_id INTEGER NOT NULL,
        category_id INTEGER NOT NULL,
        PRIMARY KEY (post_id, category_id),
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    )',
    'CREATE INDEX IF NOT EXISTS idx_posts_status ON posts(status)',
    'CREATE INDEX IF NOT EXISTS idx_posts_published_at ON posts(published_at)',
    'CREATE INDEX IF NOT EXISTS idx_post_category_post_id ON post_category(post_id)',
    'CREATE INDEX IF NOT EXISTS idx_post_category_category_id ON post_category(category_id)'
];
