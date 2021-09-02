<?php

$is_auth = rand(0, 1);

$user_name = "Татьяна"; // укажите здесь ваше имя

function format_amount (int $num) {

    $amount = ceil($num);

    if ($amount > 1000) {
        $amount = number_format($amount, 0, "", " ");
    }

    return "$amount ₽";
};

function get_dt_range ($date) {
    $dt_end = date_create($date);
    $dt_now = date_create("now");
    $diff = date_diff($dt_end, $dt_now);

    $days = $diff -> format("%d");
    $hours = $diff -> format("%H");
    $minutes = $diff -> format("%I");

    if($days) {
        $hours += 24 * $days;
    }

    $arr = compact("hours", "minutes");

    return $arr;
};

function show_error ($con) {
    $error = mysqli_error($con);
    $content = include_template('error.php', ['error' => $error]);

    return print($content);
}
