# Biblioteca API

Una API RESTful construida con Laravel 12 para gestionar un catálogo de libros. Este proyecto también incluye una interfaz web simple (basada en jQuery + Bootstrap) para interactuar con la API, y un conjunto de pruebas automatizadas usando PHPUnit.

---

## Características

- CRUD completo de libros (Crear, Leer, Actualizar, Eliminar)
- Validación robusta al crear y actualizar libros
- Interfaz web amigable sin necesidad de frameworks frontend
- Pruebas unitarias con cobertura de todos los endpoints
- Modal de confirmación para eliminar libros y estados de carga en botones

---

## Tecnologías

- [Laravel 12](https://laravel.com/)
- PHP 8+
- SQLite (por defecto)
- jQuery 3.7
- Bootstrap 5.3
- PHPUnit (para pruebas)

---

## Instalación

1. **Clona el repositorio**  
   ```bash
   git clone https://github.com/Kyrock/biblioteca-api.git
   cd biblioteca-api
   ```

2. **Instala dependencias**  
   ```bash
   composer install
   ```

3. **Configura el entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Base de datos (SQLite)**
   Crea el archivo de base de datos:
   ```bash
   touch database/database.sqlite
   ```
   Luego en el archivo `.env`, asegúrate de tener:
   ```env
   DB_CONNECTION=sqlite
   ```

5. **Migraciones**
   ```bash
   php artisan migrate
   ```

6. **Inicia el servidor**
   ```bash
   php artisan serve
   ```
   Luego visita: [http://localhost:8000](http://localhost:8000)

---

## Endpoints API

| Método | Endpoint           | Descripción                |
|--------|--------------------|----------------------------|
| GET    | /api/books         | Obtener todos los libros   |
| GET    | /api/books/{id}    | Obtener un libro específico|
| POST   | /api/books         | Crear un nuevo libro       |
| PUT    | /api/books/{id}    | Actualizar un libro        |
| DELETE | /api/books/{id}    | Eliminar un libro          |

---

## Interfaz Web

Puedes acceder a una interfaz sencilla para probar la API en el navegador.

- URL: [http://localhost:8000](http://localhost:8000)
- Funcionalidades:
  - Crear libro
  - Editar libro (formulario desplegable)
  - Eliminar libro (modal de confirmación)
  - Listado dinámico con jQuery y Bootstrap
  - Indicadores de carga en botones

---

## Pruebas automatizadas

Para ejecutar las pruebas con PHPUnit:

```bash
php artisan test
```

Se testean todos los endpoints incluyendo:
- Creación exitosa y con errores
- Lectura de uno o todos los libros
- Actualización y eliminación
- Validación de estructura de respuesta y base de datos

---

## Créditos

Desarrollado por [Santiago](https://github.com/Kyrock) como parte de una prueba técnica
