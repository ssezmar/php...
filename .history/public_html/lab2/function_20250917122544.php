<?php
function pagination($array, $per_page = 5) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $total = count($array);
    $pages = ceil($total / $per_page);
    $start = ($page - 1) * $per_page;
    $slice = array_slice($array, $start, $per_page);

    foreach ($slice as $item) {
        echo "<p>$item</p>";
    }

    for ($i = 1; $i <= $pages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
}
?>
