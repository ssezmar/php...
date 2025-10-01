<?php
require_once 'FileHandler.php';

class PhotoHandler extends FileHandler {
    public function __construct($uploadDir = 'uploads/photos/', $dataFile = 'files_data.json') {
        parent::__construct($uploadDir, $dataFile);
        $this->allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $this->maxSize = 10485760; // 10 MB
    }

    protected function generateCardBody($fileData, $filePath) {
        return '<img src="' . htmlspecialchars($filePath) . '" class="card-img-top" style="max-height:200px;object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($fileData['original_name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($fileData['comment']) . '</p>
                    <a href="' . htmlspecialchars($filePath) . '" class="btn btn-primary" download>Скачать</a>
                </div>';
    }
}