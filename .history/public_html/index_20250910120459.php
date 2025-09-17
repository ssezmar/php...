<html>
<body>
<?php  if (empty($_GET['doGo'])|| empty($_GET['name']) || empty($_GET['age'])) { 
/*Проверяем пустые ли переменные $doGo $name $age (функция empty()) 	Если пустые, то выводится форма */
?>
  	Заполните все поля формы! <br>
	<FORM ACTION="2.php">
        Ваше имя: <input type=text name="name"><br>
        Возраст: <input type=text name="age"><br>
        <input type=submit name="doGo" value="Жми сюда!">
	</form> 

<?php } else { 
/* В противном случае выводим значение переменных на экран*/
?>
Привет <?php echo ($_GET['name']) ?>! <br>
Мы знаем о вас все:<br>
Вам <?php echo($_GET['age'])?> лет!<br>

<!--Запрашиваем переменную окружения REMOTE_ADDR (IP-адрес хоста) и заносим ее в переменную $host -->

<?php $host = getenv('REMOTE_ADDR')?>

<!-- Выводим значение переменной $host на экран -->

Ваш IP-адрес  <?php echo($host) ?><br>

<!--	Запрашиваем переменную окружения HTTP_USER_AGENT (идентифицирует браузер пользователя) и заносим ее в переменную $br -->

<?php $br = getenv('HTTP_USER_AGENT')?>

<!-- Выводим значение переменной $br на экран -->

Вы используете броузер  <?php echo($br) ?>
<?php }?> 
</BODY>
</HTML>

