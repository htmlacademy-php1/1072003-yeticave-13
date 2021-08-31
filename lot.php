<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

if (!isset($_GET['id'])) {
  http_response_code(404);
  show_error();
  exit("Ошибка подключения: не выбран лот");
}

$id = (int) $_GET['id'];

$sql_category = "SELECT * FROM category";

$result = mysqli_query($con, $sql_category);

if(!$result) {
    show_error($con);
    exit;
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql_lot = "SELECT l.name, l.start_cost, l.url_img, l.description, l.dt_end, c.title FROM lot l
LEFT JOIN category c ON l.category_id = c.id
WHERE l.id = $id";
$res = mysqli_query($con, $sql_lot);

if (!$res) {
    show_error($con);
    exit;
}

$lot = mysqli_fetch_array($res, MYSQLI_ASSOC);

$content = include_template('lot_template.php', [
    'categories' => $categories,
    'lot' => $lot,
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => $lot['name'],
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
