<?php

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');

$content = include_template('main.php', [
    'categories' => $categories,
    'ads' => $ads,
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
