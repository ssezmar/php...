<?php
require_once 'controllers/FileController.php';

$controller = new FileController('document');

if (isset($_FILES['file'])) {
    $controller->upload();
} elseif (isset($_GET['delete'])) {
    $controller->delete();
} else {
    $controller->index();
}