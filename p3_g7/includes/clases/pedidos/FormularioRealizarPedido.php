<?php
namespace es\ucm\fdi\aw\pedidos;
use es\ucm\fdi\aw\Formulario;

class FormularioRealizarPedido extends Formulario{

    public function __construct() {
        parent::__construct('formRealizarPedido');
    }

        protected function generaCamposFormulario(&$datos)
    {
        $productos = $datos['productos'] ?? ;
        
    }

        protected function procesaFormulario(&$datos)
    {

    }

}
