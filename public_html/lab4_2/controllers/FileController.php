<?php
require_once 'BaseController.php';
require_once '../models/FileModel.php';

class FileController extends BaseController {
    private $type;
    private $uploadDir;
    private $viewName;
    
    public function __construct($type = 'file') {
        parent::__construct($this->createModel($type));
        $this->type = $type;
        $this->setupPaths();
    }
    
    private function createModel($type) {
        switch ($type) {
            case 'photo':
                return new PhotoModel();
            case 'document':
                return new DocumentModel();
            default:
                return new FileModel();
        }
    }
    
    private function setupPaths() {
        $this->uploadDir = $this->model->getUploadDir();
        $this->viewName = $this->type . '_upload';
    }
    
    public function index() {
        $files = $this->model->getAllFiles();
        $this->render('upload_form', [
            'files' => $files,
            'uploadDir' => $this->uploadDir,
            'type' => $this->type,
            'viewName' => $this->viewName
        ]);
    }
    
    public function upload() {
        if (isset($_FILES['file'])) {
            try {
                $fileName = $this->model->upload($_FILES['file'], $_POST['comment'] ?? '');
                $this->redirect($_SERVER['PHP_SELF'] . '?success=1');
            } catch (Exception $e) {
                $this->handleError($e->getMessage());
            }
        } else {
            $this->handleError('Не выбран файл для загрузки');
        }
    }
    
    public function delete() {
        if (isset($_GET['delete'])) {
            try {
                $this->model->delete($_GET['delete']);
                $this->redirect($_SERVER['PHP_SELF'] . '?success=1');
            } catch (Exception $e) {
                $this->handleError($e->getMessage());
            }
        }
    }
}