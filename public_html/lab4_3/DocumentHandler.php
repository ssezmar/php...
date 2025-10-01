<?php
require_once 'FileHandler.php';

class DocumentHandler extends FileHandler {
    public function __construct($uploadDir = 'uploads/documents/', $dataFile = 'files_data.json') {
        parent::__construct($uploadDir, $dataFile);
        $this->allowedTypes = array('pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx');
        $this->maxSize = 20971520; // 20 MB
    }

    protected function generateCardBody($fileData, $filePath) {
        // Вставим иконку документа и инфу
        return '<div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($fileData['original_name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($fileData['comment']) . '</p>
                    <a href="' . htmlspecialchars($filePath) . '" class="btn btn-primary" download>Скачать</a>
                </div>';
    }
}