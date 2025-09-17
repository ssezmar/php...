<?php
// Функция для скачивания страницы
function downloadPage($url) {
    //cURL —  библиотека, позволяющая выполнять HTTP-запросы из PHP
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $content = curl_exec($ch);
    
    if (curl_errno($ch)) {
        throw new Exception('Ошибка cURL: ' . curl_error($ch));
    }
    
    curl_close($ch);
    
    return $content;
}

// Основной код

    $url = 'https://portal.novsu.ru/search/groups/i.2500/?page=search&grpname=3092';
    
    echo "Скачиваем страницу: " . htmlspecialchars($url) . "<br>\n";
    $html = downloadPage($url);
    echo "<h2>Поиск списков студентов очной формы обучения</h2>\n";
   
$dom = new DomDocument();
@ $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' .$html);
//Получаем коллекцию всех ul, чтобы в дальнейшем рассматривать только очную форму, отбросив заочную
$links = $dom->getElementsByTagName('ul');
foreach($links as $link) {
    // Для каждого ul получаем его дочерние узлы, из них берем 6 по счету
    @$text = $link->childNodes->item(6)->nodeValue;
    //Для проверки используем строковую функцию позиции вхождения 
    if (stripos($text, 'Форма обучения: очная')!==false){
        echo ($text);
        //Чтобы получить таблицу получаем следующий за ul узел - это #text,  а следующий за ним -это таблица
        $t=$link->nextSibling->nextSibling;
       //Выводим таблицу
        echo $dom->saveHTML($t); 
        break;
    };
}
?>