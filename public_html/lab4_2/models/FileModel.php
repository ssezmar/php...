<?php
class FileModel {
    private $uploadDir;
    private $dataFile;
    private $allowedTypes = [];
    private $maxSize = 5242880; // 5 MB

    public function __construct($uploadDir = 'uploads/', $dataFile = 'files_data.json') {
        $this->uploadDir = $uploadDir;
        $this->dataFile = $dataFile;
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function upload($file, $comment = '') {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Ошибка загрузки файла.');
        }

        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!empty($this->allowedTypes) && !in_array($fileType, $this->allowedTypes)) {
            throw new Exception('Недопустимый тип файла');
        }
        
        if ($file['size'] > $this->maxSize) {
            throw new Exception('Файл слишком большой');
        }

        $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        $filePath = $this->uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Не удалось сохранить файл');
        }

        $this->saveFileData($fileName, $file['name'], $comment, $fileType);
        return $fileName;
    }

    public function delete($fileName) {
        $filePath = $this->uploadDir . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $this->removeFileData($fileName);
        return true;
    }

    public function getAllFiles() {
        return $this->loadFileData();
    }

    private function saveFileData($fileName, $originalName, $comment, $fileType) {
        $data = $this->loadFileData();
        
        $fileData = [
            'file_name' => $fileName,
            'original_name' => $originalName,
            'comment' => $comment,
            'type' => $fileType,
            'upload_date' => date('Y-m-d H:i:s'),
            'class_type' => get_class($this)
        ];
        
        $data[] = $fileData;
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function removeFileData($fileName) {
        $data = $this->loadFileData();
        $newData = [];
        
        foreach ($data as $item) {
            if ($item['file_name'] !== $fileName) {
                $newData[] = $item;
            }
        }
        
        file_put_contents($this->dataFile, json_encode(array_values($newData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function loadFileData() {
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $arr = json_decode($json, true);
            if (is_array($arr)) return $arr;
        }
        return [];
    }
}

class PhotoModel extends FileModel {
    public function __construct($uploadDir = 'uploads/photos/', $dataFile = 'files_data.json') {
        parent::__construct($uploadDir, $dataFile);
        $this->allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $this->maxSize = 10485760; // 10 MB
    }
}

class DocumentModel extends FileModel {
    public function __construct($uploadDir = 'uploads/documents/', $dataFile = 'files_data.json') {
        parent::__construct($uploadDir, $dataFile);
        $this->allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
        $this->maxSize = 20971520; // 20 MB
    }
}