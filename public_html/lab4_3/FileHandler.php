<?php
class FileHandler {
    protected $uploadDir;
    protected $allowedTypes = array();
    protected $maxSize;
    protected $dataFile;

    public function __construct($uploadDir = 'uploads/', $dataFile = 'files_data.json') {
        $this->uploadDir = $uploadDir;
        $this->dataFile = $dataFile;
        $this->maxSize = 5242880; // 5 MB по умолчанию

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function uploadFile($file, $comment = '') {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Ошибка загрузки файла.');
        }

        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileType, $this->allowedTypes)) {
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

    public function deleteFile($fileName) {
        $filePath = $this->uploadDir . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->removeFileData($fileName);
        return true;
    }

    protected function saveFileData($fileName, $originalName, $comment, $fileType) {
        $data = $this->loadFileData();

        $fileData = array(
            'file_name'     => $fileName,
            'original_name' => $originalName,
            'comment'       => $comment,
            'type'          => $fileType,
            'upload_date'   => date('Y-m-d H:i:s'),
            'class_type'    => get_class($this)
        );

        $data[] = $fileData;
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    protected function removeFileData($fileName) {
        $data = $this->loadFileData();
        $newData = array();

        foreach ($data as $item) {
            if ($item['file_name'] !== $fileName) {
                $newData[] = $item;
            }
        }

        file_put_contents($this->dataFile, json_encode(array_values($newData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function loadFileData() {
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $arr = json_decode($json, true);
            if (is_array($arr)) {
                return $arr;
            }
        }
        return array();
    }

    public function getAllFiles() {
        return $this->loadFileData();
    }

    public function getFilesByType($type) {
        $files = $this->getAllFiles();
        $result = array();
        foreach ($files as $f) {
            if (strtolower($type) === 'photo' && $f['class_type'] === 'PhotoHandler') {
                $result[] = $f;
            }
            if (strtolower($type) === 'document' && $f['class_type'] === 'DocumentHandler') {
                $result[] = $f;
            }
        }
        return $result;
    }

    public function generateCard($fileData) {
        $filePath = $this->uploadDir . $fileData['file_name'];
        $currentPage = basename($_SERVER['PHP_SELF']);
        $deleteUrl = $currentPage . '?delete=' . urlencode($fileData['file_name']);

        $card  = '<div class="col-md-4 mb-4">';
        $card .= '<div class="card h-100">';
        $card .= $this->generateCardBody($fileData, $filePath);
        $card .= '<div class="card-footer">';
        $card .= '<small class="text-muted">Загружено: ' . htmlspecialchars($fileData['upload_date']) . '</small>';
        $card .= '<a href="' . $deleteUrl . '" class="btn btn-danger btn-sm float-end" onclick="return confirm(\'Удалить файл?\')">Удалить</a>';
        $card .= '</div></div></div>';

        return $card;
    }

    protected function generateCardBody($fileData, $filePath) {
        // Базовая карточка — переопределяется в наследниках
        return '<div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($fileData['original_name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($fileData['comment']) . '</p>
                    <a href="' . htmlspecialchars($filePath) . '" class="btn btn-primary" download>Скачать</a>
                </div>';
    }
}