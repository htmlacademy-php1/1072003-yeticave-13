<?php

$con = mysqli_connect("localhost", "root", "root", "yeti_cave");
mysqli_set_charset($con, "utf8");

if (!$con) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit;
}
