<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Libros</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Biblioteca 📚</h1>

        <!-- Crear libro -->
        <div class="card mb-4">
            <div class="card-header">Crear nuevo libro</div>
            <div class="card-body">
                <form id="create-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Título">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="author" name="author" class="form-control" placeholder="Autor">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="year" name="published_year" class="form-control" placeholder="Año de publicación">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="genre" name="genre" class="form-control" placeholder="Género">
                        </div>
                    </div>
                    <button id="create-button" type="submit" class="btn btn-primary mt-3">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        Agregar
                    </button>
                </form>
            </div>
        </div>

        <!-- Editar libro -->
        <div class="card mb-4" id="edit-card" style="display:none;">
            <div class="card-header">Editar libro</div>
            <div class="card-body">
                <form id="edit-form">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" id="edit-title" name="title" class="form-control" placeholder="Título">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="edit-author" name="author" class="form-control" placeholder="Autor">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="edit-year" name="published_year" class="form-control" placeholder="Año de publicación">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="edit-genre" name="genre" class="form-control" placeholder="Género">
                        </div>
                    </div>
                    <button id="update-button" type="submit" class="btn btn-success mt-3">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        Actualizar
                    </button>
                    <button type="button" class="btn btn-secondary mt-3 ms-2" id="cancel-edit">Cancelar</button>
                </form>
            </div>
        </div>

        <!-- Lista de libros -->
        <h2 class="mb-3">Lista de libros</h2>
        <ul id="book-list" class="list-group"></ul>
    </div>

    <!-- Modal de confirmación antes de eliminar -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres eliminar este libro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-button">
                    <span id="spinner-delete" class="spinner-border spinner-border-sm me-2" style="display: none;" role="status" aria-hidden="true"></span>
                    Eliminar
                </button>
            </div>
            </div>
        </div>
    </div>


    <script>
        let bookIdToDelete = null;

        function loadBooks() {
            $.get('/api/books', function(data) {
                $('#book-list').empty();

                if (data.length <= 0) {
                    $('#book-list').append('<p class="text-center">No hay libros</p>')
                } else {
                    data.forEach(book => {
                        $('#book-list').append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${book.title}</strong> - ${book.author}
                                </div>
                                <div>
                                    <button data-id="${book.id}" class="btn btn-sm btn-warning me-2 edit-button">Editar</button>
                                    <button data-id="${book.id}" class="btn btn-sm btn-danger delete-button">Eliminar</button>
                                </div>
                            </li>
                        `);
                    });
                }

            });
        }

        $('#create-form').on('submit', function(e) {
            e.preventDefault();

            const button = $('#create-button');
            const spinner = button.find('.spinner-border');

            button.prop('disabled', true);
            spinner.removeClass('d-none');
            button.contents().last()[0].textContent = ' Agregando...';

            $.post('/api/books', $(this).serialize())
                .done(function() {
                    loadBooks();
                    $('#create-form')[0].reset();
                })
                .fail(function(err) {
                    alert('Error al crear el libro');
                    console.error(err);
                })
                .always(function () {
                    button.prop('disabled', false);
                    spinner.addClass('d-none');
                    button.contents().last()[0].textContent = ' Agregar';
                });
        });

        $(document).on('click', '.delete-button', function () {
            bookIdToDelete = $(this).data('id');
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        });

        $('#confirm-delete-button').on('click', function () {
            if (!bookIdToDelete) return;

            $('#spinner-delete').show();
            $('#confirm-delete-button').attr('disabled', true);

            $.ajax({
                url: `/api/books/${bookIdToDelete}`,
                type: 'DELETE',
                success: function () {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                    modal.hide();
                    loadBooks();
                },
                error: function (err) {
                    alert('Error al eliminar el libro');
                    console.error(err);
                },
                complete: function () {
                    $('#spinner-delete').hide();
                    $('#confirm-delete-button').attr('disabled', false);
                    bookIdToDelete = null;
                }
            });
        });

        $(document).on('click', '.edit-button', function () {
            const bookId = $(this).data('id');

            $.get(`/api/books/${bookId}`, function(book) {
                $('#edit-id').val(book.id);
                $('#edit-title').val(book.title);
                $('#edit-author').val(book.author);
                $('#edit-year').val(book.published_year);
                $('#edit-genre').val(book.genre);
                $('#edit-card').show();
            });
        });

        $('#edit-form').submit(function (e) {
            e.preventDefault();

            const button = $('#update-button');
            const spinner = button.find('.spinner-border');

            button.prop('disabled', true);
            spinner.removeClass('d-none');
            button.contents().last()[0].textContent = ' Actualizando...';

            const id = $('#edit-id').val();
            const updatedBook = {
                title: $('#edit-title').val(),
                author: $('#edit-author').val(),
                published_year: $('#edit-year').val(),
                genre: $('#edit-genre').val()
            };

            $.ajax({
                url: `/api/books/${id}`,
                type: 'PUT',
                data: JSON.stringify(updatedBook),
                contentType: 'application/json',
                success: function () {
                    $('#edit-card').hide();
                    loadBooks();
                },
                error: function (err) {
                    alert('Error al actualizar el libro');
                    console.error(err);
                },
                complete: function () {
                    button.prop('disabled', false);
                    spinner.addClass('d-none');
                    button.contents().last()[0].textContent = ' Actualizar';
                }
            });
        });


        $('#cancel-edit').click(function () {
            $('#edit-form')[0].reset();
            $('#edit-card').hide();
        });

        loadBooks();
    </script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
