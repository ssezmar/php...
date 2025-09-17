<?php
$menu = [
    ['link'=>'Home','href'=>'index.php'],
    ['link'=>'About','href'=>'about.php'],
    ['link'=>'Services','href'=>'services.php'],
    ['link'=>'Contact','href'=>'contact.php']
];
?>

<nav>
    <ul id="navigation">
        <?php foreach($menu as $item): ?>
            <li><a href="<?= $item['href']?>"><?= $item['link']?></a></li>
        <?php endforeach;?>
    </ul>
</nav>