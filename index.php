<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

$sql_category = "SELECT * FROM category";

$result = mysqli_query($con, $sql_category);

if(!$result) {
    print("Ошибка: " . mysqli_error($con));
    exit;
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql_lot = "SELECT l.name, l.start_cost, l.url_img, c.title FROM lot l
LEFT JOIN category c ON l.category_id = c.id
WHERE dt_add > DATE_SUB(NOW(), INTERVAL 7 DAY)";
$res = mysqli_query($con, $sql_lot);

if (!$res) {
   print("Ошибка: " . mysqli_error($con));
    exit;
}

$ads = mysqli_fetch_all($res, MYSQLI_ASSOC);

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
