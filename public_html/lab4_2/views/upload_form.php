<?php $this->render('layout', [
    'content' => '
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Загрузить ' . ($type === 'photo' ? 'фото' : 'документ') . '</h2>
            <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="file" class="form-label">Выберите файл</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                    <div class="invalid-feedback">Пожалуйста, выберите файл</div>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Комментарий</label>
                    <input type="text" class="form-control" id="comment" name="comment" placeholder="Введите комментарий">
                </div>
                <button type="submit" class="btn btn-primary">Загрузить</button>
            </form>
        </div>
    </div>

    <div class="row">
        ' . $this->generateCards($files, $uploadDir) . '
    </div>
'
]); ?>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>