<?php

require_once('helpers.php');
require_once('functions.php');
//require_once('data.php');

$con = mysqli_connect("localhost", "root", "root", "yeti_cave");
mysqli_set_charset($con, "utf8");

if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {

    $sql_category = "SELECT * FROM category";

    if($result = mysqli_query($con, $sql_category)) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка: " . mysqli_error($con));
    }

    $sql_lot = "SELECT name, start_cost, url_img, category.title FROM lot
    LEFT JOIN category ON lot.category_id = category.id
    WHERE dt_add > DATE_SUB(NOW(), INTERVAL 7 DAY)";

    if ($res = mysqli_query($con, $sql_lot)) {
        $ads = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        print("Ошибка: " . mysqli_error($con));
    }
}

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
