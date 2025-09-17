<?php
$menu = array(
    array("link"=>"Главная", "href"=>"index.php"),
    array("link"=>"О нас", "href"=>"about.php"),
    array("link"=>"Контакты", "href"=>"contact.php"),
    array("link"=>"Pagination", "href"=>"pagination.php")
);
echo '<nav><ul id="navigation">';
foreach ($menu as $item) {
    echo "<li><a href='{$item['href']}'>{$item['link']}</a></li>";
}
echo '</ul></nav>';
?>