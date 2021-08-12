<?php

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
