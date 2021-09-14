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

mysqli_query($con, 'CREATE FULLTEXT INDEX lot_ft_search ON lot(name, description)');

if (isset($_GET['find'])) {
    $search_query = trim($_GET['search']);

    $sql_search = "SELECT l.id, l.dt_add, l.name, l.description, l.url_img, l.start_cost, l.dt_end, l.step_bet, c.title FROM lot l JOIN category c ON l.category_id = c.id WHERE (MATCH(l.name, l.description) AGAINST(?)) AND (l.dt_end > NOW()) ORDER BY l.dt_add DESC";

    $stmt = mysqli_prepare($con, $sql_search);
    mysqli_stmt_bind_param($stmt, 's', $search_query);
    mysqli_stmt_execute($stmt);
    $lots_search = mysqli_stmt_get_result($stmt);
    $lots_search_count = mysqli_num_rows($lots_search);

    if (!mysqli_stmt_execute($stmt)) {
      exit("Ошибка базы данных");
    }

    $stmt -> close();

    if (isset($_GET['page'])) {
      $cur_page = $_GET['page'];
    } else {
        $cur_page = 1;
    }
    $page_items = 9;
    $offset = ($cur_page - 1) * $page_items;
    $pages_count = (int)ceil($lots_search_count / $page_items);

    $prev = (int)$cur_page - 1;
    $next = (int)$cur_page + 1;

    $parametrs_query['search'] = $_GET['search'];
    $parametrs_query['find'] = $_GET['find'];
    $query = http_build_query($parametrs_query);
    $url = 'search.php?' . $query;

    $sql_search_select = "$sql_search LIMIT $page_items OFFSET $offset";

    $stmt = mysqli_prepare($con, $sql_search_select);
    mysqli_stmt_bind_param($stmt, 's', $search_query);
    mysqli_stmt_execute($stmt);
    $result_query_search = mysqli_stmt_get_result($stmt);
    $results_search = mysqli_fetch_all($result_query_search, MYSQLI_ASSOC);

    if (!mysqli_stmt_execute($stmt)) {
      exit("Ошибка базы данных");
    }

    $content = include_template('search_template.php', ['categories' => $categories, 'results_search' => $results_search, 'page_items' => $page_items, 'lots_search_count' => $lots_search_count, 'pages_count' => $pages_count, 'url' => $url, 'cur_page' => $cur_page, 'prev' => $prev, 'next' => $next]);
} else {
    $content = include_template('search_template.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => 'YetiCave - поиск лотов',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
