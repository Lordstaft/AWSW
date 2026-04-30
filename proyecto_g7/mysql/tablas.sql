-- phpMyAdmin SQL Dump

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Base de datos: `awp2`

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('cliente','gerente','camarero','cocinero','admin') NOT NULL DEFAULT 'cliente',
  `avatar` varchar(255) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreUsuario` (`nombreUsuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imgCategoriaProd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreProd` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `iva` enum('4','10','21') NOT NULL,
  `stock` int(11) DEFAULT 0,
  `disponible` tinyint(1) DEFAULT 1,
  `ofertado` tinyint(1) DEFAULT 0,
  `rutaImg` varchar(255) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `estado` enum('nuevo','enviado','preparando','cocinando','listo','entregado','cancelado','pendiente') DEFAULT 'nuevo',
  `fechaPedido` datetime DEFAULT current_timestamp(),
  `tipo` enum('domicilio','recogida') NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0,
  `subtotalSinDescuento` decimal(10,2) NOT NULL DEFAULT 0,
  `descuentoAplicado` decimal(10,2) NOT NULL DEFAULT 0,
  `cocinero_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fk_pedidos_cocinero` (`cocinero_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pedido_productos`;
CREATE TABLE IF NOT EXISTS `pedido_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `ivaAplicado` enum('4','10','21') NOT NULL,
  `preparado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ofertas`;
CREATE TABLE IF NOT EXISTS `ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `oferta_productos`;
CREATE TABLE IF NOT EXISTS `oferta_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oferta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `oferta_id` (`oferta_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pedido_ofertas`;
CREATE TABLE IF NOT EXISTS `pedido_ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `oferta_id` int(11) NOT NULL,
  `vecesAplicada` int(11) NOT NULL,
  `descuentoTotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `oferta_id` (`oferta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pedido_producto_estado`;
CREATE TABLE IF NOT EXISTS `pedido_producto_estado` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` INT(11) NOT NULL,
  `producto_id` INT(11) NOT NULL,
  `preparado` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pedido_producto` (`pedido_id`, `producto_id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FOREIGN KEYS

ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1`
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_cocinero`
  FOREIGN KEY (`cocinero_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_1`
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

ALTER TABLE `pedido_productos`
  ADD CONSTRAINT `pedido_productos_ibfk_1`
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_productos_ibfk_2`
  FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

ALTER TABLE `oferta_productos`
  ADD CONSTRAINT `oferta_productos_ibfk_1`
  FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `oferta_productos_ibfk_2`
  FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

ALTER TABLE `pedido_ofertas`
  ADD CONSTRAINT `pedido_ofertas_ibfk_1`
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_ofertas_ibfk_2`
  FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE;

ALTER TABLE `pedido_producto_estado`
  ADD CONSTRAINT `fk_ppe_pedido`
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ppe_producto`
  FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

COMMIT;