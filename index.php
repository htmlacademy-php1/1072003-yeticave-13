<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

$sql_category = "SELECT * FROM category";

$result = mysqli_query($con, $sql_category);

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$categories) {
    exit('Ошибка базы данных');
}

$sql_lot = "SELECT l.id, l.name, l.start_cost, l.url_img, l.dt_end, c.title FROM lot l
LEFT JOIN category c ON l.category_id = c.id
WHERE dt_add > DATE_SUB(NOW(), INTERVAL 7 DAY)";
$res = mysqli_query($con, $sql_lot);

$ads = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (!$ads) {
    $error = mysqli_error($con);
    $message = $error ? $error : "Страницы не существует";
    $content = include_template('error.php', ['error' => $message]);
} else {
    $content = include_template('main.php', [
    'categories' => $categories,
    'ads' => $ads,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
