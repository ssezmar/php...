<?php
class FileUtils {
    // Преобразование размера файла в человеко-читаемый формат
    public static function human_filesize($bytes, $dec = 2) {
        $sizes = ['B','KB','MB','GB'];
        if ($bytes == 0) return '0 B';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
    }

    // Генерация URL с сохранением текущих GET-параметров
    public static function page_url($p, $qs = []) {
        $current_qs = $_GET;
        $current_qs['page'] = $p;
        
        $script = basename($_SERVER['PHP_SELF']);
        return $script . '?' . http_build_query($current_qs);
    }
    
    // Пагинация массива
    public static function paginateArray($data, $page = 1, $perPage = 5) {
        $total = count($data);
        $totalPages = ($total === 0) ? 1 : (int)ceil($total / $perPage);
        $page = max(1, min($page, $totalPages));
        
        $offset = ($page - 1) * $perPage;
        $paginated = array_slice($data, $offset, $perPage);
        
        return [
            'items' => $paginated,
            'total' => $total,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'per_page' => $perPage
        ];
    }
}