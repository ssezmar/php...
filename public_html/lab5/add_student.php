<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$errors = [];
$form_data = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'faculty_id' => '',
    'course' => ''
];

$faculties = get_all_faculties();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем и очищаем данные
    $form_data['name'] = clean_input($_POST['name'] ?? '');
    $form_data['email'] = clean_input($_POST['email'] ?? '');
    $form_data['phone'] = clean_input($_POST['phone'] ?? '');
    $form_data['faculty_id'] = clean_input($_POST['faculty_id'] ?? '');
    $form_data['course'] = clean_input($_POST['course'] ?? '');
    
    // Валидация
    if (empty($form_data['name'])) {
        $errors[] = 'ФИО обязательно для заполнения';
    }
    
    if (empty($form_data['email'])) {
        $errors[] = 'Email обязателен для заполнения';
    } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат email';
    } elseif (email_exists($form_data['email'])) {
        $errors[] = 'Студент с таким email уже существует';
    }
    
    if (empty($form_data['course']) || $form_data['course'] < 1 || $form_data['course'] > 6) {
        $errors[] = 'Курс должен быть от 1 до 6';
    }
    
    // Если ошибок нет, добавляем студента
    if (empty($errors)) {
        // Если факультет не выбран, устанавливаем NULL
        $faculty_id = empty($form_data['faculty_id']) ? NULL : $form_data['faculty_id'];
        
        if (add_student(
            $form_data['name'],
            $form_data['email'], 
            $form_data['phone'],
            $faculty_id,
            $form_data['course']
        )) {
            header('Location: index.php?message=added');
            exit;
        } else {
            $errors[] = 'Ошибка при добавлении студента';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить студента</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h4 mb-0">
                                <i class="fas fa-user-plus me-2"></i>Добавить нового студента
                            </h1>
                            <a href="index.php" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Назад
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <h5 class="alert-heading">Ошибки валидации:</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">ФИО *</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo $form_data['name']; ?>" 
                                           required
                                           placeholder="Введите полное имя">
                                    <div class="invalid-feedback">
                                        Пожалуйста, введите ФИО студента.
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="<?php echo $form_data['email']; ?>" 
                                           required
                                           placeholder="example@university.ru">
                                    <div class="invalid-feedback">
                                        Пожалуйста, введите корректный email.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?php echo $form_data['phone']; ?>"
                                           placeholder="+7 (XXX) XXX-XX-XX">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="faculty_id" class="form-label">Факультет</label>
                                    <select class="form-select" id="faculty_id" name="faculty_id">
                                        <option value="">Выберите факультет</option>
                                        <?php while($faculty = mysqli_fetch_assoc($faculties)): ?>
                                            <option value="<?php echo $faculty['id']; ?>" 
                                                <?php echo $form_data['faculty_id'] == $faculty['id'] ? 'selected' : ''; ?>>
                                                <?php echo clean_input($faculty['name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="course" class="form-label">Курс *</label>
                                <select class="form-select" id="course" name="course" required>
                                    <option value="">Выберите курс</option>
                                    <?php for ($i = 1; $i <= 6; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                            <?php echo $form_data['course'] == $i ? 'selected' : ''; ?>>
                                            <?php echo $i; ?> курс
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Пожалуйста, выберите курс.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Отмена
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Добавить студента
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Валидация формы Bootstrap
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>

<?php mysqli_close($connection); ?>