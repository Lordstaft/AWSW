SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

START TRANSACTION;

-- ============================================================
-- DROP TABLES
-- ============================================================

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
-- TABLAS
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
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

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
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`cocinero_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `pedido_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `ivaAplicado` TINYINT NOT NULL,
  `preparado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`pedido_id`,`producto_id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `pedido_producto_estado` (
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `preparado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pedido_id`,`producto_id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `activa` tinyint(1) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `oferta_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oferta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `pedido_ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `oferta_id` int(11) NOT NULL,
  `vecesAplicada` int(11) NOT NULL,
  `descuentoTotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- DATOS COMPLETOS
-- ============================================================

-- Usuarios
INSERT INTO usuarios VALUES
(25,'cliente1','cliente1@bistro.com','Carlos','Martínez López','hash','cliente','cliente1.jpg','2026-03-04 17:02:42',1),
(26,'cliente2','cliente2@bistro.com','Laura','Gómez Pérez','hash','cliente','cliente2.jpg','2026-03-04 17:02:42',1),
(27,'cocinero1','cocinero1@bistro.com','Miguel','Ruiz Sánchez','hash','cocinero','cocinero1.jpg','2026-03-04 17:02:42',1),
(29,'camarero1','camarero1@bistro.com','David','Moreno Torres','hash','camarero','camarero1.jpg','2026-03-04 17:02:42',1);

-- Categorías
INSERT INTO categorias VALUES
(1,'Bebidas','Refrescos','bebidas.jpg',1),
(2,'Bocadillos','Bocadillos','bocadillos.jpg',1),
(3,'Postres','Postres','postres.jpg',1);

-- Productos
INSERT INTO productos VALUES
(1,'Coca-Cola 33cl','Refresco',1,2.00,10,100,1,0,1,'coca.jpg','2026-03-04'),
(4,'Bocadillo de Jamón','Pan con jamón',2,4.50,10,40,1,0,1,'jamon.jpg','2026-03-04'),
(12,'Tarta de Queso','Tarta',3,4.00,10,20,1,1,1,'tarta.jpg','2026-03-04');

-- Pedido
INSERT INTO pedidos VALUES
(2,25,'listo','recogida','2026-03-25 17:04:35',6.00,6.00,0.00,27,1);

-- Productos del pedido
INSERT INTO pedido_productos VALUES
(2,2,1,2,2.00,21,0),
(3,2,4,1,4.50,21,0);

-- Estado productos
INSERT INTO pedido_producto_estado
SELECT pedido_id, producto_id, 0 FROM pedido_productos;

-- Ofertas
INSERT INTO ofertas VALUES
(1,'Desayuno Bistro','Café + Tarta','2026-04-15','2026-05-31',20.00,1,1);

-- Oferta productos
INSERT INTO oferta_productos VALUES
(1,1,12,1);

COMMIT;
SET FOREIGN_KEY_CHECKS=1;