<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

$sql_category = "SELECT * FROM category";

$result = mysqli_query($con, $sql_category);

if(!$result) {
    show_error($con);
    exit;
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
$categories_id = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reqiured = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
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
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $path = $_FILES['lot-img']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/png" OR $file_type !== "image/jpeg") {
            $errors['lot-img'] = 'Загрузите картинку в формате jpg, jpeg, png';
        }
        else {
            move_uploaded_file($tmp_name, 'uploads/' . $_FILES['lot-img']['name']);
            $lot['path'] = $_FILES['lot-img']['name'];
        }
    } else {
        $errors['lot-img'] = 'Вы не загрузили файл';
        }


    if (count($errors)) {
        $content = include_template('add_lot.php', ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);
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

