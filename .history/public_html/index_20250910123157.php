<?php
$temp = "guest.txt";
// определяем файл, в котором будут храниться данные
if ( isset( $_POST["hid"]))
// если существует переменная, значит данные формы были отправлены
{
if ( ! empty ($_POST["new"] ) && ! empty ($_POST["nick"]))
// проверяем наличие данных в переменных
    {
    $data = date("j M Y G:i");
    // генерируем дату
    function str($a)
        {
        $a = str_replace( "<", "", $a );
        $a = str_replace( ">", "", $a );
        return $a;
/* определяем функцию, которая удаляет '<' и '>', вернее заменяет их на пустую строку. Это не даст использовать теги*/
        }
    $new = str($_POST["new"]);
    $nick = str($_POST["nick"]);
    $email = str($_POST["email"]);
    $name = "$new<br>написал: $nick, <a href=\"mailto:$email\">$email</a><br&lg;$data<br><br>\n";
    // создаём строку из переменных, отправленных пользователем
    file_exists( $temp ) or die("Файл $temp не существует");
/* проверка существования файла. В противном случае прекращаем работу программы с выводом соответствующего сообщения - конструкция or die()*/
    is_file( $temp ) or die("$temp - это не файл");
    // проверяем, действительно ли файл - это файл. А то вдруг каталог.
    is_readable( $temp ) or die("$temp нельзя читать");
    is_writable( $temp ) or die("В $temp нельзя писать");
    $fp = fopen( $temp, "a" ) or die("Не могу открыть $temp");
    // открываем файл для записи данных в конец файла
    fwrite( $fp, $name);
    // добавляем запись в файл
    fclose( $fp );
    // закрываем файл
    print "Ваше сообщение успешно добавлено!<br>";
    }
else
    {
    print "неправильно заполнена форма!";
    }
}
file_exists( $temp ) or die("ОШИБКА СЦЕНАРИЯ");
is_file( $temp ) or die("ОШИБКА СЦЕНАРИЯ");
is_readable( $temp ) or die("ОШИБКА СЦЕНАРИЯ");
is_writable( $temp ) or die("ОШИБКА СЦЕНАРИЯ");
$fp = fopen( $temp, "r" ) or die("ОШИБКА СЦЕНАРИЯ");
// открываем файл для чтения
while ( ! feof( $fp ) )
    {
    $line[] = fgets( $fp, 1024 );
    // читаем в цикле строку за строкой и добавляем строки в массив
    }
fclose( $fp );
$line_reverce = array_reverse( $line );
// переворачиваем массив
foreach ( $line_reverce as $line_print )
    print "$line_print<br>";
    // выводим массив
?>
