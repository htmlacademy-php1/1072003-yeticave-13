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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($con, $form['email']);
    $sql_email = "SELECT * FROM user WHERE email = '$email'";
    $res = mysqli_query($con, $sql_email);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $content = include_template('login_template.php', ['form' => $form, 'errors' => $errors, 'categories' => $categories]);
    } else {
        $sql_user = "SELECT * FROM user WHERE user.email = ?";
        $stmt = mysqli_prepare($con, $sql_user);
        mysqli_stmt_bind_param($stmt, 's', getPostVal('email'));
        mysqli_stmt_execute($stmt);
        $result_user = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result_user);

        if (!$user) {
            $error = mysqli_error($con);
            $message = $error ? $error : "Страницы не существует";
            $content = include_template('error.php', ['error' => $message]);
        }   else {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: index.php");
                exit();
        }
    }
} else {
    $content = include_template('login_template.php', ['categories' => $categories]);

    if (isset($_SESSION['user'])) {
        header("Location: index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'авторизация пользователя',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
