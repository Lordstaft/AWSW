# 🍽️ Bistro FDI

Aplicación web desarrollada para la asignatura **Aplicaciones Web** (curso 2025-26) de la **Universidad Complutense de Madrid**.

**Grupo 7:** Aleksandra Michalina Lisicka · Carlos Mauricio Rondón Arévalo · Pablo Sánchez Redondo

---

## ¿Qué es Bistro FDI?

Bistro FDI es una aplicación web que simula la gestión digital completa de una cafetería universitaria. Integra la experiencia del cliente (realizar pedidos, consultar estado, aplicar ofertas) con la operativa interna del personal (cocina, sala y gerencia), todo en una única plataforma estructurada por roles.

---

## Funcionalidades principales

- **Gestión de usuarios** — registro, autenticación y administración de cuentas para todos los roles.
- **Gestión de productos y categorías** — alta, edición, búsqueda y retirada lógica de productos del menú.
- **Gestión de pedidos** — flujo completo desde que el cliente realiza el pedido hasta su entrega, con trazabilidad en cada estado.
- **Preparación en cocina** — los cocineros se autoasignan pedidos, marcan productos como preparados y finalizan la preparación.
- **Gestión de ofertas** — packs de productos con descuento que el gerente crea y administra, y que los clientes pueden aplicar al confirmar su pedido.

---

## Roles de usuario

| Rol | Descripción |
|---|---|
| **Cliente** | Realiza pedidos, consulta su estado y aplica ofertas |
| **Cocinero** | Gestiona la preparación de los pedidos en cocina |
| **Camarero** | Gestiona la entrega y el cobro de los pedidos |
| **Gerente** | Administra productos, ofertas y supervisa pedidos |
| **Administrador** | Acceso completo, incluyendo gestión de usuarios |

---

## Tecnologías utilizadas

- **Backend:** PHP 8 (orientado a objetos, arquitectura MVC propia)
- **Base de datos:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 (responsive, adaptado a móvil y tablet)
- **Servidor:** Apache (desplegado en VPS UCM)
- **Control de versiones:** Git + GitHub

---

## Estructura del repositorio

```
/
├── p1_g7/          → Práctica 1: descripción y diseño inicial
├── p2_g7/          → Práctica 2: autenticación, usuarios, productos
├── proyecto_g7/    → Entrega final: aplicación completa
│   ├── includes/   → Clases del modelo y vistas comunes
│   ├── usuarios/   → Vistas por rol (admin, gerente, cocinero, camarero)
│   ├── pedidos/    → Flujo de pedido y carrito
│   ├── ofertas/    → Creación y edición de ofertas
│   ├── mysql/      → Scripts SQL de la base de datos
│   ├── css/        → Hoja de estilos principal
│   └── img/        → Imágenes de productos, categorías y avatares
└── awp2.pdf        → Documentación adicional del proyecto
```

---

## Base de datos

La base de datos se llama `awp2` y contiene las siguientes tablas:

| Tabla | Descripción |
|---|---|
| `usuarios` | Cuentas de usuario con rol, avatar y fecha de registro |
| `categorias` | Categorías de productos con nombre e imagen |
| `productos` | Productos del menú con precio, IVA, stock y disponibilidad |
| `pedidos` | Pedidos con estado, tipo, total y cocinero asignado |
| `pedido_productos` | Líneas de pedido con cantidad, precio unitario e IVA |
| `ofertas` | Packs con descuento, fechas de validez y descripción |
| `oferta_productos` | Productos incluidos en cada oferta |
| `pedido_ofertas` | Ofertas aplicadas a cada pedido |
| `pedido_producto_estado` | Estado de preparación por producto dentro de un pedido |

### Scripts disponibles en `mysql/`

- `tablas.sql` — crea la estructura completa de la base de datos.
- `datos.sql` — inserta datos de ejemplo para probar la aplicación.
- `awp2.sql` — volcado completo con tablas y datos.

---

## Instalación y despliegue (VPS UCM)

> Requiere conectividad UCM (o VPN si se accede desde fuera de la red universitaria).

1. Conectarse a [https://guacamole.containers.fdi.ucm.es](https://guacamole.containers.fdi.ucm.es) con el usuario `vm006`.
2. Comprimir el proyecto en `.zip` y arrastrarlo a la webshell (queda en `/root`).
3. Ejecutar los siguientes comandos:

```bash
rm -fr /var/www/produccion/*
unzip -d /var/www/produccion /root/proyecto.zip
fix-www-acl
```

4. Importar la base de datos desde [https://phpmyadmin.containers.fdi.ucm.es](https://phpmyadmin.containers.fdi.ucm.es) (servidor `vm006.db.swarm.test`):
   - Importar `mysql/awp2.sql`, o bien `mysql/tablas.sql` seguido de `mysql/datos.sql`.

5. La aplicación queda disponible en: **[https://vm006.containers.fdi.ucm.es/](https://vm006.containers.fdi.ucm.es/)**

> ⚠️ Ejecutar `fix-www-acl` tras cada despliegue. En Linux los nombres de tabla son sensibles a mayúsculas/minúsculas; deben coincidir exactamente con el DDL.

---

## Usuarios de prueba

| Usuario | Contraseña | Rol |
|---|---|---|
| cliente1 | cliente1 | Cliente |
| cliente2 | cliente2 | Cliente |
| cocinero1 | cocinero1 | Cocinero |
| cocinero2 | cocinero2 | Cocinero |
| camarero1 | camarero1 | Camarero |
| camarero2 | camarero2 | Camarero |
| gerente1 | gerente1 | Gerente |
| admin | admin | Administrador |

---

## Resolución de errores

```bash
tail -f /var/log/php/errors.log       # Errores de PHP
tail -f /var/log/apache2/error.log    # Errores de Apache
```

---

## Repositorio

[https://github.com/Lordstaft/AWSW](https://github.com/Lordstaft/AWSW)
