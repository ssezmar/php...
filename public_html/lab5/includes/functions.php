<?php
// Функция для выполнения подготовленных запросов
function execute_prepared_query($sql, $params = [], $types = '') {
    global $connection;
    
    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . mysqli_error($connection));
    }
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Ошибка выполнения запроса: " . mysqli_stmt_error($stmt));
    }
    
    return $stmt;
}

// Функция для получения всех факультетов
function get_all_faculties() {
    $sql = "SELECT * FROM faculties ORDER BY name ASC";
    $stmt = execute_prepared_query($sql);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Функция для получения факультета по ID
function get_faculty_by_id($id) {
    $sql = "SELECT * FROM faculties WHERE id = ?";
    $stmt = execute_prepared_query($sql, [$id], 'i');
    $result = mysqli_stmt_get_result($stmt);
    $faculty = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $faculty;
}

// Функция для добавления факультета
function add_faculty($name) {
    $sql = "INSERT INTO faculties (name) VALUES (?)";
    $stmt = execute_prepared_query($sql, [$name], 's');
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected > 0;
}

// Функция для получения всех студентов с информацией о факультетах
function get_all_students() {
    $sql = "SELECT s.*, f.name as faculty_name 
            FROM students s 
            LEFT JOIN faculties f ON s.faculty_id = f.id 
            ORDER BY s.created_at DESC";
    $stmt = execute_prepared_query($sql);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Функция для получения студента по ID с информацией о факультете
function get_student_by_id($id) {
    $sql = "SELECT s.*, f.name as faculty_name 
            FROM students s 
            LEFT JOIN faculties f ON s.faculty_id = f.id 
            WHERE s.id = ?";
    $stmt = execute_prepared_query($sql, [$id], 'i');
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $student;
}

// Функция для добавления студента
function add_student($name, $email, $phone, $faculty_id, $course) {
    $sql = "INSERT INTO students (name, email, phone, faculty_id, course) VALUES (?, ?, ?, ?, ?)";
    $stmt = execute_prepared_query($sql, [$name, $email, $phone, $faculty_id, $course], 'sssii');
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected > 0;
}

// Функция для обновления студента
function update_student($id, $name, $email, $phone, $faculty_id, $course) {
    $sql = "UPDATE students SET name = ?, email = ?, phone = ?, faculty_id = ?, course = ? WHERE id = ?";
    $stmt = execute_prepared_query($sql, [$name, $email, $phone, $faculty_id, $course, $id], 'sssiii');
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected > 0;
}

// Функция для удаления студента
function delete_student($id) {
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = execute_prepared_query($sql, [$id], 'i');
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected > 0;
}

// Функция для проверки существования email
function email_exists($email, $exclude_id = null) {
    if ($exclude_id) {
        $sql = "SELECT id FROM students WHERE email = ? AND id != ?";
        $stmt = execute_prepared_query($sql, [$email, $exclude_id], 'si');
    } else {
        $sql = "SELECT id FROM students WHERE email = ?";
        $stmt = execute_prepared_query($sql, [$email], 's');
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

// Функция для очистки входных данных
function clean_input($data) {
    return htmlspecialchars(trim($data));
}
?>