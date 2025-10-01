<?php
// Базовый класс для загрузки файлов
class FileUploader {
    protected $uploadDir;      // путь для загрузки
    protected $allowedTypes = array(); // разрешённые расширения
    protected $maxSize = 5242880; // 5 МБ по умолчанию
    protected $dataFile;       // JSON-файл с метаданными

    public function __construct($uploadDir = 'uploads/', $dataFile = 'files_data.json') {
        $this->uploadDir = $uploadDir;
        $this->dataFile = $dataFile;

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // Метод загрузки
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

    // Удаление файла
    public function deleteFile($fileName) {
        $filePath = $this->uploadDir . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->removeFileData($fileName);
        return true;
    }

    // Сохраняем в JSON
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
            if (is_array($arr)) return $arr;
        }
        return array();
    }

    public function getAllFiles() {
        return $this->loadFileData();
    }

    // HTML карточка
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

    // Тело карточки — переопределяется в наследниках
    protected function generateCardBody($fileData, $filePath) {
        return '<div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($fileData['original_name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($fileData['comment']) . '</p>
                    <a href="' . $filePath . '" class="btn btn-primary" download>Скачать</a>
                </div>';
    }
}

// Для фото
class PhotoUploader extends FileUploader {
    public function __construct($uploadDir = 'uploads/photos/', $dataFile = 'files_data.json') {
        parent::__construct($uploadDir, $dataFile);
        $this->allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $this->maxSize = 10485760; // 10 МБ
    }

    protected function generateCardBody($fileData, $filePath) {
        return '<img src="' . $filePath . '" class="card-img-top" style="max-height:200px;object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($fileData['original_name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($fileData['comment']) . '</p>
                    <a href="' . $filePath . '" class="btn btn-primary" download>Скачать</a>
                </div>';
    }
}

// Для документов
class DocumentUploader extends FileUploader {
    public function __construct($uploadDir = 'uploads/documents/', $dataFile = 'files_data.json') {
        parent::__construct($uploadDir, $dataFile);
        $this->allowedTypes = array('pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx');
        $this->maxSize = 20971520; // 20 МБ
    }

    protected function generateCardBody($fileData, $filePath) {
        return '<div class="card-body text-center">
                    <div style="font-size:50px;">📄</div>
                    <h5 class="card-title">' . htmlspecialchars($fileData['original_name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($fileData['comment']) . '</p>
                    <p class="text-muted">.' . htmlspecialchars($fileData['type']) . '</p>
                    <a href="' . $filePath . '" class="btn btn-primary" download>Скачать</a>
                </div>';
    }
}