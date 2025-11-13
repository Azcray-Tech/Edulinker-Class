# gestión-recursos-aprendizaje

Aplicación web para la gestión de recursos de aprendizaje en un instituto de bachillerato. Permite a profesores crear y compartir artículos enriquecidos, y a administradores subir libros para estudiantes. Los estudiantes pueden acceder a los recursos y comentar en los artículos.

---

## **Estructura del Proyecto**

- **assets/**: Archivos estáticos como CSS, JavaScript e imágenes.
  - **css/**: Archivos de estilos.
  - **js/**: Archivos de scripts.
  - **img/**: Imágenes del proyecto.
  - **uploads/**: Archivos subidos por los usuarios.

- **config/**: Archivos de configuración, como la conexión a la base de datos.
  - `conexion_mysqli.php`: Archivo para conectar con la base de datos.

- **controllers/**: Lógica de negocio y controladores.
  - `logout.php`: Controlador para cerrar la sesión del usuario.
  - `search.php`: Controlador para realizar búsquedas en el blog.
  - **articles/**: Controladores relacionados con artículos.
  - **categories/**: Controladores relacionados con categorías.
  - **comments/**: Controladores relacionados con comentarios.
  - **users/**: Controladores relacionados con usuarios.
  - **roles/**: Controladores relacionados con roles de usuario.
  - **books/**: Controladores relacionados con libros.

- **includes/**: Componentes reutilizables como encabezados y pies de página.
  - `header.php`: Encabezado común.
  - `footer.php`: Pie de página común.

- **lib/**: Funciones y clases reutilizables.
  - `article_manager.php`: Clase para gestionar artículos.
  - `articles.php`: Funciones relacionadas con artículos.
  - `books.php`: Funciones relacionadas con libros.
  - `categories.php`: Funciones relacionadas con categorías.
  - `comments.php`: Funciones relacionadas con comentarios.
  - `common.php`: Funciones comunes.
  - `constants.php`: Constantes del proyecto.
  - `funtions.php`: Funciones adicionales.
  - `helpers.php`: Funciones auxiliares.
  - `notifications.php`: Funciones relacionadas con notificaciones.
  - `session.php`: Funciones relacionadas con la gestión de sesiones.
  - `user.php`: Funciones relacionadas con usuarios.

- **views/**: Vistas del proyecto.
  - `search_results.php`: Vista para mostrar los resultados de búsqueda.
  - **auth/**: Vistas relacionadas con autenticación (login, registro, etc.).
  - **articles/**: Vistas relacionadas con artículos.
  - **categories/**: Vistas relacionadas con categorías.
  - **users/**: Vistas relacionadas con usuarios.
  - **managers/**: Vistas administrativas.
  - **books/**: Vistas relacionadas con libros.

- **backups/**: Archivos de respaldo.

---

## **Requisitos del Proyecto**

- **Servidor web**: Apache (XAMPP, WAMP, etc.).
- **PHP**: Versión 7.4 o superior.
- **Base de datos**: MySQL.

---

## **Instalación Windows**

1. Clona este repositorio o descárgalo como archivo ZIP.
2. Coloca los archivos en el directorio raíz de tu servidor web (por ejemplo, `c:/xampp/htdocs/`).
3. Importa el archivo de base de datos (`database.sql`) en tu servidor MySQL.
4. Configura la conexión a la base de datos en `config/conexion_mysqli.php`.
5. Accede al proyecto desde tu navegador en `http://localhost/Edulinker-Class`.

---

## **Instalacion Linux**

1. Clona este repositorio o descargalo como archivo ZIP.
2. Coloca los archivos en el directorio raiz de tu servidor web (por ejemplo, `/var/www/html/`).
3. Crea una base de datos en tu servidor MySQL llamada 'edulinker' e importele los datos del archivo `blog_academico.sql`.
4. Configura la conexion a la base de datos en `.env`.
5. Acceda al proyecto desde tu navelados `http://localhost/Edulinker-Class`.

---

## **Funcionalidades**

- **Usuarios**:
  - Registro, inicio de sesión y cierre de sesión.
  - Edición de perfil.
- **Artículos**:
  - Creación, edición, eliminación y visualización.
  - Gestión de categorías.
- **Comentarios**:
  - Creación y moderación de comentarios.
- **Administración**:
  - Gestión de usuarios, artículos, categorías y comentarios.
- **Libros**:
  - Creación, edición, eliminación y visualización.

---

## **Estructura de la Base de Datos**

Incluye una breve descripción de las tablas principales. Por ejemplo:

- **users**: Contiene los datos de los usuarios registrados.
- **articles**: Contiene los artículos creados por los usuarios.
- **categories**: Contiene las categorías de los artículos.
- **comments**: Contiene los comentarios realizados en los artículos.
- **books**: Contiene los libros creados por los usuarios.

---

## **Contribuciones**

Si deseas contribuir al proyecto, por favor abre un issue o envía un pull request.

---

## **Licencia**

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).
