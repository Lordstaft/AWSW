<?php
namespace es\ucm\fdi\aw;
use es\ucm\fdi\aw\Aplicacion;

class Imagenes {

    private $rutaRaizApp;

    private function esDefault($imagen): bool {
        $defaultImgs = [
            'producto_default.jpg', 
            'usuario_default.jpg', 
            'categoria_default.jpg', 
            'avatar1.jpg', 
            'avatar2.jpg', 
            'avatar3.jpg'
        ];

        return in_array($imagen, $defaultImgs);
        
        foreach ($defaultImgs as $nombre){
            if ($imagen === $nombre) {
                return true;
            }
        }
        return false;
    }

    public function __construct() {
        $app = Aplicacion::getInstance();
        $this->rutaRaizApp = dirname($app->getDirInstalacion());
    }

    public function subirImagen($file, $directorio = 'img') {
        $nombreArchivo = '';
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $rutaDirectorio = $this->rutaRaizApp . '/' . trim($directorio, '/');
        if (!is_dir($rutaDirectorio)) {
            mkdir($rutaDirectorio, 0755, true);
        }
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $nombreArchivo = uniqid('img_', true) . '.' . $extension;
        $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivo;
        if (move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            return $nombreArchivo;
        }
        return null;
    }

    public function eliminarImagen(string $nombreArchivo = ''): bool {
        if (empty($nombreArchivo)) {
            return false;
        }
        $nombreArchivo = basename($nombreArchivo);

        if ($this->esDefault($nombreArchivo)) {
            return false;
        }

        $rutaCompleta = $this->rutaRaizApp . '/img/' . $nombreArchivo;
        if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
            return unlink($rutaCompleta);
        }
        return false;
    }

    public function reemplazarImagen($file, $imagenActual = null, $directorio = 'img') {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return $imagenActual;
        }
        $nuevoNombre = $this->subirImagen($file, $directorio);
        if ($nuevoNombre !== null) {
            if (!empty($imagenActual) && !$this->esDefault($imagenActual)) {
                $this->eliminarImagen($imagenActual);
            }
            return $nuevoNombre;
        }
        return $imagenActual;
    }
}