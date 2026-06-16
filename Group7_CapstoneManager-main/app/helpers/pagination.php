<?php

function paginate($total, $limit = 10){

    $page = $_GET['p'] ?? 1;

    $page = max($page, 1);

    $offset = ($page - 1) * $limit;

    $pages = ceil($total / $limit);

    return [
        'limit' => $limit,
        'offset' => $offset,
        'current_page' => $page,
        'total_pages' => $pages
    ];
}