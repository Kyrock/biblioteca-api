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
    <h1>Lista de Libros</h1>
    <ul id="book-list"></ul>

    <h2>Nuevo Libro</h2>
    <form id="book-form">
        <input type="text" name="title" placeholder="Título" required><br>
        <input type="text" name="author" placeholder="Autor" required><br>
        <input type="text" name="published_year" placeholder="Año de publicación" required><br>
        <input type="text" name="genre" placeholder="Género" required><br>
        <button type="submit">Crear</button>
    </form>

    <h2>Editar libro</h2>
    <form id="edit-form" style="display:none;">
        <input type="hidden" id="edit-id">
        <input type="text" id="edit-title" placeholder="Título">
        <input type="text" id="edit-author" placeholder="Autor">
        <input type="text" id="edit-year" placeholder="Año de publicación">
        <input type="text" id="edit-genre" placeholder="Género">
        <button type="submit">Actualizar libro</button>
    </form>

    <script>
        function loadBooks() {
            $.get('/api/books', function(data) {
                $('#book-list').empty();
                data.forEach(book => {
                    $('#book-list').append(`
                        <li>
                            ${book.title} - ${book.author}
                            <button data-id="${book.id}" class="edit-button">Editar</button>
                            <button data-id="${book.id}" class="delete-button">Eliminar</button>
                        </li>
                    `);
                });
            });
        }

        $('#book-form').on('submit', function(e) {
            e.preventDefault();
            $.post('/api/books', $(this).serialize())
                .done(function() {
                    loadBooks();
                    $('#book-form')[0].reset();
                })
                .fail(function(err) {
                    alert('Error al crear el libro');
                    console.error(err);
                });
        });

        $(document).on('click', '.delete-button', function () {
            const bookId = $(this).data('id');

            if (confirm('¿Seguro que quieres eliminar este libro?')) {
                $.ajax({
                    url: `/api/books/${bookId}`,
                    type: 'DELETE',
                    success: function () {
                        loadBooks();
                    },
                    error: function (err) {
                        alert('Error al eliminar el libro');
                        console.error(err);
                    }
                });
            }
        });

        $(document).on('click', '.edit-button', function () {
            const bookId = $(this).data('id');

            $.get(`/api/books/${bookId}`, function(book) {
                $('#edit-id').val(book.id);
                $('#edit-title').val(book.title);
                $('#edit-author').val(book.author);
                $('#edit-year').val(book.published_year);
                $('#edit-genre').val(book.genre);
                $('#edit-form').show();
            });
        });

        $('#edit-form').submit(function (e) {
            e.preventDefault();

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
                    $('#edit-form').hide();
                    loadBooks();
                },
                error: function (err) {
                    alert('Error al actualizar el libro');
                    console.error(err);
                }
            });
        });


        loadBooks();
    </script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
