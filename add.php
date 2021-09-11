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

$categories_id = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reqiured = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date', 'lot-img'];
    $errors = [];

    $rules = [
        'lot-name' => function($value) {
            return validateLength($value, 10, 255);
        },
        'category' => function($value) use ($categories_id) {
            return validateCategory($value, $categories_id);
        },
        'message' => function($value) {
            return validateLength($value, 10, 1000);
        },
        'lot-rate' => function($value) {
            return validateRate($value);
        },
        'lot-step' => function($value) {
            return validateStep($value);
        },
        'lot-date' => function($value) {
            return validateDate($value);
        }
    ];

    $lot = filter_input_array(INPUT_POST, ['lot-name' => FILTER_DEFAULT, 'category' => FILTER_DEFAULT, 'message' => FILTER_DEFAULT, 'lot-rate' => FILTER_DEFAULT, 'lot-step' => FILTER_DEFAULT, 'lot-date' => FILTER_DEFAULT], true);

    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $reqiured) && empty($value)) {
            $errors[$key] = "Поле надо заполнить";
        }
    }

    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_name = uniqid() . $_FILES['lot-img']['name'];
        $type_file = mime_content_type($tmp_name);

        if (!($type_file == 'image/jpeg' || $type_file == 'image/png' || $type_file == 'image/jpg')) {
            $errors['lot-img'] = 'Загрузите картинку в формате jpg, jpeg, png';
        }
    } else {
        $errors['lot-img'] = 'Вы не загрузили файл';
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $content = include_template('add_lot.php', ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);
    } else {
        move_uploaded_file($tmp_name, "uploads/$file_name");
        $lot['url_img'] = "uploads/$file_name";

        $dt_add = date('Y-m-d H:i:s');
        $user_id = 1;
        $winner_id = 2;
        $sql_add = 'INSERT INTO lot (dt_add, name, category, url_img, start_cost, description, dt_end, step_bet, user_id, winner_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($con, $sql_add);
        mysqli_stmt_bind_param($stmt, 'sssssssiiii', $dt_add, getPostVal('lot-name'), getPostVal('category'), $lot['url_img'], getPostVal('lot-rate'), getPostVal('message'), getPostVal('lot-date'), getPostVal('lot-step'), $user_id, $winner_id, getPostVal('category'));
        $res = mysqli_stmt_execute($stmt);

        if(!$res) {
            exit('Ошибка базы данных');
        }

        $lot_id = mysqli_insert_id($con);

        $sql_lot = "SELECT l.name, l.start_cost, l.url_img, l.description, l.dt_end, c.title FROM lot l
        LEFT JOIN category c ON l.category_id = c.id
        WHERE l.id = $lot_id";
        $result = mysqli_query($con, $sql_lot);

        $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (!$lot) {
            $error = mysqli_error($con);
            $message = $error ? $error : "Страницы не существует";
            $content = include_template('error.php', ['error' => $message]);
        } else {
            header("Location: lot.php?id=" . $lot_id);
        }
    }
} else {
    $content = include_template('add_lot.php', ['lot' => $lot, 'categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'YetiCave - создание лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);

