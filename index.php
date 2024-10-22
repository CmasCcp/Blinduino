<?php
require 'vendor/autoload.php'; // Cargar Parsedown con Composer

$Parsedown = new Parsedown();

// Función para obtener archivos de todas las subcarpetas
function obtenerArchivosPorTema($directory) {
    $temas = [];
    // Leer las carpetas dentro de docs
    $carpetas = array_filter(glob($directory . '/*'), 'is_dir');
    
    foreach ($carpetas as $carpeta) {
        $tema = basename($carpeta); // El nombre de la carpeta es el tema
        $archivos = array_filter(glob($carpeta . '/*.md'), 'is_file');
        
        $temas[$tema] = $archivos; // Asocia los archivos al tema
    }

    return $temas;
}

// Obtener todos los archivos organizados por tema
$temasConArchivos = obtenerArchivosPorTema(__DIR__ . '/docs');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-3 font-bold d-none d-sm-inline">Blinduino</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">

                        <?php
                            foreach ($temasConArchivos as $tema => $archivos) {
                                echo "<span class='ms-1 d-none d-sm-inline'>$tema</span>";

                                foreach ($archivos as $archivo) {
                                    $archivoNombre = basename($archivo, '.md');
                                    $archivoURL = urlencode("$tema/$archivoNombre");
                                    echo "<li class='w-100 '><a href='?page=$archivoURL' class='nav-link text-white pl-2 px-0'>$archivoNombre</a></li>";
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col py-3">
            <?php
        // Verificar si se ha solicitado una página específica
        if (isset($_GET['page'])) {
            $rutaArchivo = __DIR__ . '/docs/' . $_GET['page'] . '.md';

            if (file_exists($rutaArchivo)) {
                $markdownContent = file_get_contents($rutaArchivo);
                echo "<div class='container mt-5'>";
                echo $Parsedown->text($markdownContent); // Convertir Markdown a HTML
                echo "</div>";
            } else {
                echo "<div class='container mt-5'><h2>Archivo no encontrado</h2></div>";
            }
        }else{
            $markdownContent = file_get_contents(__DIR__ . '/docs/Tema 1/intro.md');
            echo "<div class='container mt-5'>";
            echo $Parsedown->text($markdownContent); // Convertir Markdown a HTML
            echo "</div>";
        }
        ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
