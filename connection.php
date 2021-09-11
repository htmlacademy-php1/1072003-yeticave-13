<?php
session_start();

$con = mysqli_connect("localhost", "root", "root", "yeti_cave");
mysqli_set_charset($con, "utf8");

if (!$con) {
    $error = mysqli_connect_error();
    exit('Ошибка базы данных' . $error);
}
