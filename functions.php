<?php

function format_amount (int $num) {

    $amount = ceil($num);

    if ($amount > 1000) {
        $amount = number_format($amount, 0, "", " ");
    }

    return "$amount ₽";
};

