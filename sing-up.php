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

if (isset($_SESSION['user_id'])) {
  http_response_code(403);
  exit("Вы уже зарегистрированы на сайте.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reqiured = ['email', 'password', 'name', 'message'];
    $errors = [];

    $rules = [
		'email' => function($value) use ($con) {
            return validateEmail($value, $con);
        },
		'password' => function($value) {
            return validateLength($value, 6, 255);
        },
        'name' => function($value) {
            return validateLength($value, 3, 64);
        },
        'message' => function($value) {
            return validateLength($value, 10, 255);
        }
    ];

	$add_user = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT, 'message' => FILTER_DEFAULT], true);

	foreach ($add_user as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $reqiured) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }
	$errors = array_filter($errors);

    if (count($errors)) {
        $content = include_template('sing-up_template.php', ['add_user' => $add_user, 'errors' => $errors, 'categories' => $categories]);
	} else {
		$dt_add = date('Y-m-d H:i:s');
		$hash_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$sql_new_user = 'INSERT INTO user (dt_registarion, email, name, password, contacts) VALUES (?, ?, ?, ?, ?)';
		$stmt = mysqli_prepare($con, $sql_new_user);
		mysqli_stmt_bind_param($stmt, 'sssss', $dt_add, getPostVal('email'), getPostVal('name'), $hash_password, getPostVal('message'));

		if (!mysqli_stmt_execute($stmt)) {
		show_error($con);
		exit;
		}

		header('Location: login.php');
	}
}	else {
	$content = include_template('sing-up_template.php',['categories' => $categories]);
	}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'Регистрация пользователя',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
