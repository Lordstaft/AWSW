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

-- 1. Usuarios
INSERT INTO `usuarios` (`id`, `nombreUsuario`, `email`, `nombre`, `apellidos`, `password`, `rol`, `avatar`, `fechaRegistro`, `activo`) VALUES
(25, 'cliente1', 'cliente1@bistro.com', 'Carlos', 'Martínez López', '$2y$10$17AZXm5IaUsYCzTAsXud3.F0cuxqhKeYjE41AZ8sCRXYBlAFr9uFG', 'cliente', 'cliente1.jpg', '2026-03-04 17:02:42', 1),
(26, 'cliente2', 'cliente2@bistro.com', 'Laura', 'Gómez Pérez', '$2y$10$jYlTJleiD5WPhyqCZssrGujPopxnO6Ve7ZQrBsWOEVjCc2b3v0npG', 'cliente', 'cliente2.jpg', '2026-03-04 17:02:42', 1),
(27, 'cocinero1', 'cocinero1@bistro.com', 'Miguel', 'Ruiz Sánchez', '$2y$10$ng.fNkXfA1IfTO0ZnCoHcO3ClPc0ADsJNJ222cBl0zne1xqOlXRLu', 'cocinero', 'cocinero1.jpg', '2026-03-04 17:02:42', 1),
(28, 'cocinero2', 'cocinero2@bistro.com', 'Ana', 'Fernández Díaz', '$2y$10$l0CSXrTqGcLDI47M7jtDdu36DULiKZ7PGUlzJtVeIElReDZGl29nC', 'cocinero', 'cocinero2.jpg', '2026-03-04 17:02:42', 1),
(29, 'camarero1', 'camarero1@bistro.com', 'David', 'Moreno Torres', '$2y$10$xpMOFhCSl4L/BHu2q.cbnuBu5xa0tZZqCn2FUw.S0NC9YVVg12nMy', 'camarero', 'camarero1.jpg', '2026-03-04 17:02:42', 1),
(30, 'camarero2', 'camarero2@bistro.com', 'Lucía', 'Navarro Gil', '$2y$10$hGXm174gvCHdYgzO.WdOr.2KWLabMB2P9ymXu7ltw5tSZDS5kTcje', 'camarero', 'camarero2.jpg', '2026-03-04 17:02:42', 1),
(31, 'gerente1', 'gerente@bistro.com', 'Alberto', 'Ramírez Castillo', '$2y$10$CxOAkawkO61HSP6OTgw/fuaUU9VsdIwD3gUtEDoYcDTT9KP/NgG9m', 'gerente', 'gerente.jpg', '2026-03-04 17:02:42', 1),
(32, 'admin', 'admin@bistro.com', 'Administrador', 'Sistema', '$2y$10$M6dlz8/4fq37pq9ATYhp3exQceC4c9riGhEu25N/m981wH1N9t0Gq', 'admin', 'admin.jpg', '2026-03-04 17:02:42', 1),
(41, 'Prince02', 'prince@gmail.com', 'Prince', 'William', '$2y$10$yiGXMxNEDBS7So3XIYwtZ.hbcbpTg1tYQ8ICaQZyBBK1NH56eBUM2', 'cliente', NULL, '2026-03-11 20:58:02', 1),
(43, 'Aleksandra02', 'alisicka@ucm.es', 'ALEKSANDRA', 'LISICKA', '$2y$10$mpWDx2sJxWs43M1DU8ofnuoaaw5vYEfoRTKM3zHVjJAY8IlEfbqm2', 'cliente', NULL, '2026-03-12 09:44:50', 1);

-- 2. Categorías
INSERT INTO `categorias` VALUES
(1,'Bebidas','Refrescos, zumos, agua, cafés y otras bebidas frías o calientes.','bebidas.jpg',1),
(2,'Bocadillos','Bocadillos variados preparados con pan fresco e ingredientes de calidad.','bocadillos.jpg',1),
(3,'Ensaladas','Ensaladas frescas y saludables con ingredientes variados.','ensaladas.jpg',1),
(4,'Platos Calientes','Platos principales servidos calientes, recién preparados en cocina.','platos_calientes.jpg',1),
(5,'Postres','Postres caseros y dulces para finalizar la comida.','postres.jpg',1),
(6,'Snacks','Aperitivos y pequeños tentempiés para cualquier momento.','snacks.jpg',1);

-- 3. Productos
INSERT INTO `productos` VALUES
(1,'Coca-Cola 33cl','Refresco de cola servido frío.',1,2.00,10,100,1,0,1,'cocacola.jpg','2026-03-04 16:26:45'),
(2,'Agua Mineral 50cl','Botella de agua mineral natural.',1,1.50,10,150,1,0,1,'agua_mineral.jpg','2026-03-04 16:26:45'),
(3,'Café Espresso','Café espresso recién molido.',1,1.80,10,80,1,0,1,'cafe_espresso.jpg','2026-03-04 16:26:45'),
(4,'Bocadillo de Jamón','Pan crujiente con jamón serrano.',2,4.50,10,40,1,0,1,'bocadillo_jamon.jpg','2026-03-04 16:26:45'),
(5,'Bocadillo Vegetal','Lechuga, tomate, atún y mayonesa.',2,4.00,10,35,1,1,1,'bocadillo_vegetal.jpg','2026-03-04 16:26:45'),
(6,'Bocadillo de Pollo','Pollo a la plancha con salsa especial.',2,4.80,10,30,1,0,1,'bocadillo_pollo.jpg','2026-03-04 16:26:45'),
(7,'Ensalada César','Lechuga, pollo, queso parmesano y salsa César.',3,6.50,10,25,1,0,1,'ensalada_cesar.jpg','2026-03-04 16:26:45'),
(8,'Ensalada Mixta','Lechuga, tomate, cebolla, atún y aceitunas.',3,5.50,10,30,1,0,1,'ensalada_mixta.jpg','2026-03-04 16:26:45'),
(9,'Hamburguesa Completa','Hamburguesa con queso, lechuga, tomate y bacon.',4,8.50,10,20,1,1,1,'hamburguesa_completa.jpg','2026-03-04 16:26:45'),
(10,'Lasaña Casera','Lasaña de carne gratinada al horno.',4,9.00,10,15,1,0,1,'lasana_casera.jpg','2026-03-04 16:26:45'),
(11,'Pechuga a la Plancha','Pechuga de pollo con guarnición.',4,7.50,10,18,1,0,1,'pechuga_plancha.jpg','2026-03-04 16:26:45'),
(12,'Tarta de Queso','Tarta de queso casera con mermelada.',5,4.00,10,20,1,1,1,'tarta_queso.jpg','2026-03-04 16:26:45'),
(13,'Brownie de Chocolate','Brownie caliente con nueces.',5,3.80,10,25,1,0,1,'brownie_chocolate.jpg','2026-03-04 16:26:45'),
(14,'Patatas Fritas','Ración de patatas fritas crujientes.',6,3.00,10,50,1,0,1,'patatas_fritas.jpg','2026-03-04 16:26:45'),
(15,'Nachos con Queso','Nachos acompañados con salsa de queso.',6,4.50,10,35,1,0,1,'nachos_queso.jpg','2026-03-04 16:26:45');

-- 4. Pedido
INSERT INTO `pedidos` VALUES
(2,25,'listo','recogida','2026-03-25 17:04:35',4.00,4.00,0.00,27,1);

-- 5. Pedido productos
INSERT INTO `pedido_productos` VALUES
(2,2,1,2,2.00,21,0);

-- 6. Estado productos
INSERT INTO `pedido_producto_estado` (`pedido_id`, `producto_id`, `preparado`)
SELECT pedido_id, producto_id, 0 FROM pedido_productos;

-- 7. Ofertas
INSERT INTO `ofertas` VALUES
(1,'Desayuno Bistro','Incluye 1 Café Espresso y 1 Tarta de Queso','2026-04-15','2026-05-31',20.00,1,1),
(2,'Menú Hamburguesa','Hamburguesa completa con patatas fritas y bebida','2026-04-15','2026-06-30',15.00,1,1),
(3,'Pack Snack','Nachos con queso y bebida','2026-04-15','2026-05-31',10.00,1,1);

-- 8. Oferta productos
INSERT INTO `oferta_productos` VALUES
(1,1,3,1),
(2,1,12,1),
(3,2,9,1),
(4,2,14,1),
(5,2,1,1),
(6,3,15,1),
(7,3,1,1);

COMMIT;
SET FOREIGN_KEY_CHECKS=1;