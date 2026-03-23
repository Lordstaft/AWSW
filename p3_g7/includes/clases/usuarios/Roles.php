<?php
namespace es\ucm\fdi\aw\usuarios;

enum Roles: string {
    case ADMIN = 'admin';
    case CLIENTE = 'cliente';
    case GERENTE = 'gerente';
    case CAMARERO = 'camarero';
    case COCINERO = 'cocinero';
}
