<?php

/**
 * Clase para gestionar las operaciones relacionadas con los libros.
 * Esta clase incluye métodos para agregar, editar, eliminar libros y procesar archivos.
 */
class Books
{

    private $id;
    private $title;
    private $image;
    private $file;
    private $sumary;
    private $date;
    private $author;
    private $category;

    public function __construct($id, $title, $image, $file, $sumary, $date, $author, $category)
    {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->file = $file;
        $this->sumary = $sumary;
        $this->date = $date;
        $this->author = $author;
        $this->category = $category;
    }

    // Getters and Setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getSumary()
    {
        return $this->sumary;
    }

    public function setSumary($sumary)
    {
        $this->sumary = $sumary;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    // Metodos de mi clase Books

    /**
     * Agrega un nuevo libro a la base de datos.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param string $title Título del libro.
     * @param string $image Nombre del archivo de la imagen de portada.
     * @param string $file Nombre del archivo del libro.
     * @param string $sumary Resumen del libro.
     * @param string $author Autor del libro.
     * @param string $category Categoría del libro.
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     */
    public function agregarLibro($conexion, $title, $image, $file, $sumary, $author, $category)
    {
        $sql = "INSERT INTO books (titulo, imagen, archivo, resumen, autor, categoria) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $title, $image, $file, $sumary, $author, $category);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    /**
     * Edita un libro existente en la base de datos.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param int $id ID del libro a editar.
     * @param string $title Nuevo título del libro.
     * @param string $image Nuevo nombre del archivo de la imagen de portada.
     * @param string $file Nuevo nombre del archivo del libro.
     * @param string $sumary Nuevo resumen del libro.
     * @param string $author Nuevo autor del libro.
     * @param string $category Nueva categoría del libro.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function editarLibro($conexion, $id, $title, $image, $file, $sumary, $author, $category)
    {
        $sql = "UPDATE books SET titulo=?, imagen=?, categoria=?, resumen=?, autor=?, archivo=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $title, $image, $file, $sumary, $author, $category, $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    /**
     * Elimina un libro de la base de datos.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param int $id ID del libro a eliminar.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function eliminarLibro($conexion, $id)
    {
        $sql = "DELETE FROM books WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    /**
     * Procesa la subida de la portada del libro.
     * @param array $file Array de archivo subido.
     * @return string|bool Nombre del archivo subido o false en caso de error.
     */
    public function procesarPortada($file)
    {
        if (isset($file) && $file["error"] == 0) {
            $nombre = basename($file["name"]);
            $ruta = __DIR__ . "/../uploads/books/cover/" . $nombre;
            if (move_uploaded_file($file["tmp_name"], $ruta)) {
                return $nombre;
            }
        }
        return false;
    }

    /**
     * Procesa la subida del archivo del libro.
     * @param array $file Array de archivo subido.
     * @return string|bool Nombre del archivo subido o false en caso de error.
     */
    public function procesarArchivoLibro($file)
    {
        if (isset($file) && $file["error"] == 0) {
            $nombre = basename($file["name"]);
            $ruta = __DIR__ . "/../uploads/books/files/" . $nombre;
            if (move_uploaded_file($file["tmp_name"], $ruta)) {
                return $nombre;
            }
        }
        return false;
    }
}
