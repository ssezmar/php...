<?php
/**
 * Парсит текстовый список студентов.
 * Каждая строка: ФИО;email;телефон;группа
 * @param string $text
 * @return array [ ['name'=>..,'email'=>..,'phone'=>..,'group_name'=>..], ... ]
 */
function parseStudents(string $text): array {
    $lines = preg_split('/\r\n|\n/', trim($text));
    $result = [];
    foreach ($lines as $line) {
        $parts = array_map('trim', explode(';', $line));
        if (count($parts) >= 4) {
            $result[] = [
                'name'       => $parts[0],
                'email'      => $parts[1],
                'phone'      => $parts[2] ?: null,
                'group_name' => $parts[3],
            ];
        }
    }
    return $result;
}
