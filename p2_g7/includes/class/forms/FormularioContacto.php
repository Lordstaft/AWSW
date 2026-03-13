<?php
namespace es\ucm\fdi\aw\forms;
use es\ucm\fdi\aw\forms\Formulario;

class FormularioContacto extends Formulario
{
    public function __construct() {
        parent::__construct('formContacto', ['action' => 'mailto:pablsa18@ucm.es']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $nombre = $datos['nombre'] ?? '';
        $email = $datos['email'] ?? '';
        $mensaje = $datos['mensaje'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'email', 'checkbox'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Formulario de contacto</legend>
            <div>
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="email">Correo electrónico:</label>
                <input id="email" type="email" name="email" value="$email" />
                {$erroresCampos['email']}
            </div>
            <div id="contactMotive">
                    <p>Motivo de contacto: </p>
                    <input type="radio" id="contactMotive1" name="contact" value="evaluacion">
                    <label for="contactMotive1">Evaluación</label>

                    <input type="radio" id="contactMotive2" name="contact" value="sugerencia">
                    <label for="contactMotive2">Sugerencia</label>

                    <input type="radio" id="contactMotive3" name="contact" value="critica">
                    <label for="contactMotive3">Crítica</label>
            </div>
            <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="4" cols="50">$mensaje</textarea>

                <p>
                    <input type="checkbox" name="checkbox" id="checkbox">
                    <label for="checkbox">Marque esta casilla para verificar que ha leído nuestros términos y
                        condiciones
                        del servicio</label>
                </p>
                {$erroresCampos['checkbox']}
            <div>
                <button type="submit" name="enviar">Enviar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || empty($nombre) ) {
            $this->errores['nombre'] = 'Debe introducir un nombre.';
        }
        
        $email = trim($datos['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $email || empty($email) ) {
            $this->errores['email'] = 'Debe introducir un correo electrónico.';
        }

        if (!isset($datos['checkbox'])) {
            $this->errores['checkbox'] = 'Debe aceptar los términos y condiciones.'
        }
        
        if (count($this->errores) === 0) {
            return __DIR__ . '/../../index.php';
        }
    }
}