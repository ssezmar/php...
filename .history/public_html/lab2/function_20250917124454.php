<?php
function paginateArray($data, $page = 1, $perPage = 5) {
    $totalPages = ceil(count($data) / $perPage);

    if ($page > $totalPages || $page <= 0) {
        return [];
    }

    $offset = ($page - 1) * $perPage;
    $pagedData = array_slice($data, $offset, $perPage);

    return [
        'current_page'   => $page,
        'pages_total'    => $totalPages,
        'items_per_page' => $perPage,
        'data'           => $pagedData
    ];
}

// Пример стилей для пагинации
?>
<style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.pagination a {
    padding: 7px 14px;
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    border-radius: 5px;
    margin-right: 5px;
    transition: 0.3s;
}
.pagination a:hover {
    background-color: #0056b3;
}
.pagination a.active {
    background-color: #be8605ff;
}
</style>