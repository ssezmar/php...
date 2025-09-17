<?php
include "header.php";
include "function.php";

$data = array("Собака1","Собака2","Собака3","Собака4","Собака5","Собака6","Собака7","Собака8");
pagination($data,5);

include "footer.php";
?>
