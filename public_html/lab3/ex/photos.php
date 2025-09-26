<form action="photos.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required> <br>
        <input type="text" name="filename" placeholder="Введите имя файла" required> <br>
        <input type="submit" name="doupload" value="Закачать новую фотографию">
    </form>
<?php
print_r ($_FILES);
// из формы получаем имя временного файла $file
// также вместе с ним передаются
//$file_name исходное имя файла, которое он имел до своей отправки на сервер
//$file_type  тип загружаемого файла, если браузер смог его определить
//$file_size  размер файла в байтах
//Все эти переменные можно увидеть, если снять комментарии со следующих строк
//echo " переменная file= $file <br>\n";
//echo "переменная file_name= $file_name <br>\n";
//echo "переменная file_type= $file_type <br>\n";
//echo "переменная file_size= $file_size <br>\n";
$imgdir="images";  // каталог для хранения изображений
@mkdir($imgdir,0700); // Создаем, если его еще нет
// Атрибут 0700 указывает права доступа. В данном случае чтение, запись, исполнение. 
// проверяем, принят ли файл
    
if (!file_exists($imgdir . "/")) {
    mkdir($imgdir, 0700);
}

$filename = NULL;

if (isset($_POST["filename"])) {
    $filename = $_POST["filename"];


if ($_FILES && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {

    $name = $imgdir . "/" . $_FILES["file"]["name"];
    $image_info = getimagesize($_FILES["file"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    if ($image_width > 1000 ||$image_height > 1000)
    {
        echo "<script>alert('Файл слишком большой');document.location.href='photos.php';</script>";
    
    exit;
    }
    move_uploaded_file($_FILES["file"]["tmp_name"], $name);
     

}
}
// считываем в массив фотоальбом
$d=opendir($imgdir);  // открываем каталог (функция opendir возвращает идентификатор //каталога)
$photos=array(); // сначала альбом пуст
// перебираем все файлы
while (($e=readdir($d)) !==false) {
// readdir возвращает вместе с именами подкаталогов и файлов еще два символа .(ссылка на //текущий каталог)
// и .. (ссылка на родительский каталог)
// проверяем это изображение gif, jpg, png,используя регулярные выражения
// ereg ищет парные значения $e в регулярном выражении, указанном в //"^(.*)\\.(gif|jpg|png)$".
// Разделителем между ними служит символ . (\\.)
// первая скобка означает регулярное выражение "любой символ, встречающийся 0 и более //раз" в начале строки "^"
// вторая скобка (gif|jpg|png) - сочетание gif или jpg или png с конца строки "$"
if (!preg_match("/^(.*)\\.(gif|jpg|png)$/", $e, $p)) continue;
//если нет, переходим к следующему файлу
//иначе обрабатываем этот
$path="$imgdir/$e";   // адрес
$sz=getimagesize($path);  // размер
$tm=filemtime ($path); //время добавления
// вставляем изображение в массив $photos
$photos[$tm]=array(
'time'=>filemtime ($path),  //время добавления
'name'=>$e, // имя файла
'url'=> $path, // url файла
'w'  => $sz[0], // ширина картинки
'h' => $sz[1],  // высота картинки
'wh' => $sz[3]  // "width=xxx height=yyy"
);
}
//Ключ массива $photos - время в секундах, когда была добавлена
// та или иная фотография. Сортируем массив: наиболее "свежие"
// фотографии располагаем ближе к его началу, используя функцию krsort. 
// При этом массив сортируется по ключу, но в обратном порядке
krsort($photos);
// данные для вывода готовы. Далеее оформляем страницу.
?>
<h1> Фотоальбом </h1>
</center>
<table border="1" width="80%" align=center>
<?php foreach ($photos as $n=>$img) {?>
<tr>
<td align=center> Здесь будет название фотографии <br> 
<?php $q=$img['wh']; echo $q ?> <br> Добавлена
<?php $qq=date("d.m.Y H:i:s",$img['time']); echo $qq?>
</td>
<td align=center>
<img src=<?php echo $img['url'] ?> > 
</td>
</tr>
<?php
}
?>
</table> 
