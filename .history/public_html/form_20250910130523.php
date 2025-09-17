<?php
// Имя файла для хранения данных
$file = __DIR__ . '/data.txt';

// Если форма отправлена — обрабатываем
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка обязательных полей
    $errors = [];

    // Собираем данные с фильтрацией
    $name  = trim($_POST['name'] ?? '');
    $age   = trim($_POST['age'] ?? '');
    $mail  = trim($_POST['mail'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    // Чекбоксы
    $courses = isset($_POST['intensive-courses']) ? 'Да' : 'Нет';
    $books   = isset($_POST['books']) ? 'Да' : 'Нет';
    $video   = isset($_POST['video']) ? 'Да' : 'Нет';

    // Радио
    $preference = $_POST['preference'] ?? '';

    if ($name === '') $errors[] = 'Введите имя';
    if ($mail === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный e-mail';
    if ($phone === '') $errors[] = 'Введите телефон';

    if (!$errors) {
        // Формируем строку
        $line = date('Y-m-d H:i:s') . "\t" .
                "Имя: $name; " .
                "Возраст: $age; " .
                "E-mail: $mail; " .
                "Телефон: $phone; " .
                "Увлечения: $comment; " .
                "Курсы: $courses; Книги: $books; Видео: $video; " .
                "Предпочтение: $preference" . PHP_EOL;

        // Записываем в файл (добавляем)
        file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

        echo "<p style='color:green'>Данные успешно сохранены!</p>";
    } else {
        echo "<p style='color:red'>".implode('<br>', $errors)."</p>";
    }
}
?>

<!-- HTML-форма -->
<form action="" method="post" style="width:50%">
    
    <fieldset>
      <legend>Персональные данные</legend>
      <ul>
        <li>
          <label for='name'>Имя:*</label>
          <input type='text' name='name' placeholder='Иван Иванов' id='name' required>
        </li>
        <li>
          <label for='age'>Возраст:</label>
          <input type='number' name='age' placeholder='27' id='age' min='0' max='125'>
        </li>
      </ul>
    </fieldset>
    <fieldset>
      <legend>Контакты</legend>
      <ul>
        <li>
          <label for='email'>E-mail:*</label>
          <input type='email' name='mail' placeholder='ivanov@gmail.com' id='email' required>
        </li>
        <li>
          <label for='number'>Телефон:*</label>
          <input type='tel' name='phone' placeholder='+7 000 000-00-00' id='number' maxlength='21' required>
        </li>
      </ul>
    </fieldset>
    <div>
      <label for='message'>Увлечения</label>
      <textarea name='comment' placeholder='Расскажите обо всём, что для вас важно' id='message'></textarea>
    </div>
    <fieldset>
      <legend>Учёба</legend>
      <ul>
        <li>
          <input type='checkbox' name='intensive-courses' id='courses' checked>
          <label for='courses'>Прохожу курсы</label>
        </li>
        <li>
          <input type='checkbox' name='books' id='books'>
          <label for='books'>Читаю книги</label>
        </li>
        <li>
          <input type='checkbox' name='video' id='video'>
          <label for='video'>Смотрю видео</label>
        </li>
      </ul>
    </fieldset>
    <fieldset>
      <legend>Предпочтения</legend>
      <ul>
        <li>
          <input type='radio' name='preference' id='front' value='frontend' checked>
          <label for='front'>Фронтенд-разработка</label>
        </li>
        <li>
          <input type='radio' name='preference' id='back' value='backend'>
          <label for='back'>Бэкенд-разработка</label>
        </li>
      </ul>
    </fieldset>
    <div>
      <button type='submit'>Отправить</button>
      <p>* — Обязательные поля</p>
    </div>
</form>
