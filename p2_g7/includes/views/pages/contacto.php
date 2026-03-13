<?php
require __DIR__ . '/../../config.php';

$contenidoPrincipal = <<<EOS
    <div id="mailTo">
        <fieldset>
            <legend>Formulario de contacto</legend>
            <form action="mailto:pablsa18@ucm.es" method="post" id="contacto">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre">


                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email">

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
                <textarea id="mensaje" name="mensaje" rows="4" cols="50"></textarea>
                <p>
                    <input type="checkbox" name="checkbox" id="checkbox">
                    <label for="checkbox">Marque esta casilla para verificar que ha leído nuestros términos y
                        condiciones
                        del servicio</label>
                </p>
                <button type="submit">Enviar</button>
            </form>
        </fieldset>
    </div>
EOS;

require __DIR__ . '/plantilla.php';
