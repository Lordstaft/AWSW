SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Eliminar tablas existentes (orden correcto por dependencias)
DROP TABLE IF EXISTS `pedido_producto_estado`;
DROP TABLE IF EXISTS `pedido_ofertas`;
DROP TABLE IF EXISTS `oferta_productos`;
DROP TABLE IF EXISTS `pedido_productos`;
DROP TABLE IF EXISTS `pedidos`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `ofertas`;
DROP TABLE IF EXISTS `usuarios`;

-- ============================================================
-- USUARIOS
-- ============================================================

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','camarero','cocinero','gerente','admin') NOT NULL DEFAULT 'cliente',
  `avatar` varchar(255) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreUsuario` (`nombreUsuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- CATEGORIAS Y PRODUCTOS
-- ============================================================

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imgCategoriaProd` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreProd` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `iva` TINYINT NOT NULL,
  `stock` int(11) DEFAULT 0,
  `disponible` tinyint(1) DEFAULT 1,
  `ofertado` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `rutaImg` varchar(255) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `fk_productos_categoria`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `categorias` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- PEDIDOS
-- ============================================================

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `estado` enum('nuevo','pendiente','preparando','cocinando','listo','entregado','cancelado') DEFAULT 'nuevo',
  `tipo` enum('domicilio','recogida') NOT NULL,
  `fechaPedido` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0,
  `subtotalSinDescuento` decimal(10,2) NOT NULL DEFAULT 0,
  `descuentoAplicado` decimal(10,2) NOT NULL DEFAULT 0,
  `cocinero_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `cocinero_id` (`cocinero_id`),
  CONSTRAINT `fk_pedidos_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pedidos_cocinero`
    FOREIGN KEY (`cocinero_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pedido_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `ivaAplicado` TINYINT NOT NULL,
  `preparado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pedido_producto_unique` (`pedido_id`, `producto_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `fk_pp_pedido`
    FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pp_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pedido_producto_estado` (
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `preparado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pedido_id`, `producto_id`),
  CONSTRAINT `fk_ppe_pedido`
    FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ppe_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- OFERTAS
-- ============================================================

CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `oferta_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oferta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_op_oferta`
    FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_op_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pedido_ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `oferta_id` int(11) NOT NULL,
  `vecesAplicada` int(11) NOT NULL,
  `descuentoTotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_po_pedido`
    FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_po_oferta`
    FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
SET FOREIGN_KEY_CHECKS=1;