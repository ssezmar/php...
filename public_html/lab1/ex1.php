<?php
// Функция для скачивания страницы
function downloadPage($url) {
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

// URL группы
$url = 'https://portal.novsu.ru/search/groups/i.2500/?page=search&grpname=3092';
echo "Скачиваем страницу: " . htmlspecialchars($url) . "<br>\n";

$html = downloadPage($url);

// Загружаем HTML
$dom = new DomDocument();
@$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' .$html);

// Массив для студентов
$students = [];

// Получаем все ul на странице
$uls = $dom->getElementsByTagName('ul');

foreach ($uls as $ul) {
    @$text = $ul->childNodes->item(6)->nodeValue;
    if (stripos($text, 'Форма обучения: очная') !== false) {
        // Следующий узел после ul — это таблица с студентами
        $table = $ul->nextSibling->nextSibling;
        if ($table->nodeName === 'table') {
            $rows = $table->getElementsByTagName('tr');
            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                if ($cols->length > 0) {
                    $students[] = trim($cols->item(1)->nodeValue); // Имя студента во втором столбце
                }
            }
        }
        break;
    }
}

// Вывод массива студентов
echo "<h2>Список студентов очной формы:</h2>";
echo "<pre>";
print_r($students);
echo "</pre>";
?>
