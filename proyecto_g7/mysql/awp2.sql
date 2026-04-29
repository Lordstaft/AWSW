SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;
 
-- ============================================================
-- FUNCIONALIDAD 0: Gestión de Usuarios
-- ============================================================
 
CREATE TABLE `usuarios` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `nombreUsuario`  VARCHAR(50)   NOT NULL UNIQUE,
  `email`          VARCHAR(100)  NOT NULL UNIQUE,
  `nombre`         VARCHAR(100)  NOT NULL,
  `apellidos`      VARCHAR(150)  NOT NULL,
  `contraseña`     VARCHAR(255)  NOT NULL,
  `rol`            ENUM('cliente','camarero','cocinero','gerente','admin') NOT NULL DEFAULT 'cliente',
  `avatar`         VARCHAR(255)  DEFAULT NULL,
  `fechaRegistro`  DATETIME      DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 
-- ============================================================
-- FUNCIONALIDAD 1: Gestión de Productos
-- ============================================================
 
CREATE TABLE `categorias` (
  `id`                INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`            VARCHAR(100) NOT NULL UNIQUE,
  `descripcion`       TEXT         NOT NULL,
  `imgCategoriaProd`  VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
CREATE TABLE `productos` (
  `id`            INT(11)        NOT NULL AUTO_INCREMENT,
  `nombreProd`    VARCHAR(150)   NOT NULL,
  `descripcion`   TEXT           NOT NULL,
  `categoria_id`  INT(11)        NOT NULL,
  `precio`        DECIMAL(10,2)  NOT NULL,
  `iva`           ENUM('4','10','21') NOT NULL,
  `stock`         INT(11)        DEFAULT 0,
  `disponible`    TINYINT(1)     DEFAULT 1,
  `ofertado`      TINYINT(1)     DEFAULT 1,            -- borrado lógico: 0 = retirado de carta
  `fechaCreacion` DATETIME       DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
CREATE TABLE `producto_imagenes` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `producto_id` INT(11)      NOT NULL,
  `rutaImagen`  VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 
-- ============================================================
-- FUNCIONALIDAD 2: Gestión de Pedidos
-- ============================================================
 
CREATE TABLE `pedidos` (
  `id`                    INT(11)        NOT NULL AUTO_INCREMENT,
  `usuario_id`            INT(11)        NOT NULL,
  `estado`                ENUM('nuevo','recibido','en_preparacion','cocinando','listo_cocina','terminado','entregado','cancelado') DEFAULT 'nuevo',
  `tipo`                  ENUM('domicilio','recogida') NOT NULL,
  `fechaPedido`           DATETIME       DEFAULT current_timestamp(),
  `total`                 DECIMAL(10,2)  NOT NULL DEFAULT 0,
  `subtotalSinDescuento`  DECIMAL(10,2)  NOT NULL DEFAULT 0,
  `descuentoAplicado`     DECIMAL(10,2)  NOT NULL DEFAULT 0,
  `cocinero_id`           INT(11)        DEFAULT NULL,       -- asignado en F3
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_id`)  REFERENCES `usuarios`(`id`),
  FOREIGN KEY (`cocinero_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
CREATE TABLE `pedido_productos` (
  `id`             INT(11)        NOT NULL AUTO_INCREMENT,
  `pedido_id`      INT(11)        NOT NULL,
  `producto_id`    INT(11)        NOT NULL,
  `cantidad`       INT(11)        NOT NULL,
  `precioUnitario` DECIMAL(10,2)  NOT NULL,
  `ivaAplicado`    ENUM('4','10','21') NOT NULL,
  `preparado`      TINYINT(1)     NOT NULL DEFAULT 0,        -- confirmado por cocinero en F3
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pedido_id`)   REFERENCES `pedidos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 
-- ============================================================
-- FUNCIONALIDAD 4: Gestión de Ofertas
-- ============================================================
 
CREATE TABLE `ofertas` (
  `id`          INT(11)       NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(100)  NOT NULL,
  `descripcion` TEXT,
  `fechaInicio` DATE          NOT NULL,
  `fechaFin`    DATE          NOT NULL,
  `descuento`   DECIMAL(5,2)  NOT NULL,
  `activa`      TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
CREATE TABLE `oferta_productos` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `oferta_id`   INT(11) NOT NULL,
  `producto_id` INT(11) NOT NULL,
  `cantidad`    INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`oferta_id`)   REFERENCES `ofertas`(`id`)   ON DELETE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
CREATE TABLE `pedido_ofertas` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `pedido_id`      INT(11)       NOT NULL,
  `oferta_id`      INT(11)       NOT NULL,
  `vecesAplicada`  INT(11)       NOT NULL,
  `descuentoTotal` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`oferta_id`) REFERENCES `ofertas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 
-- ============================================================
-- DATOS DE PRUEBA
-- ============================================================
 
-- Usuarios
INSERT INTO `usuarios` (`id`, `nombreUsuario`, `email`, `nombre`, `apellidos`, `contraseña`, `rol`, `avatar`, `fechaRegistro`) VALUES
(25, 'cliente1',    'cliente1@bistro.com',  'Carlos',        'Martínez López',   '$2y$10$17AZXm5IaUsYCzTAsXud3.F0cuxqhKeYjE41AZ8sCRXYBlAFr9uFG', 'cliente',  'cliente1.jpg',  '2026-03-04 17:02:42'),
(26, 'cliente2',    'cliente2@bistro.com',  'Laura',         'Gómez Pérez',      '$2y$10$jYlTJleiD5WPhyqCZssrGujPopxnO6Ve7ZQrBsWOEVjCc2b3v0npG', 'cliente',  'cliente2.jpg',  '2026-03-04 17:02:42'),
(27, 'cocinero1',   'cocinero1@bistro.com', 'Miguel',        'Ruiz Sánchez',     '$2y$10$ng.fNkXfA1IfTO0ZnCoHcO3ClPc0ADsJNJ222cBl0zne1xqOlXRLu', 'cocinero', 'cocinero1.jpg', '2026-03-04 17:02:42'),
(28, 'cocinero2',   'cocinero2@bistro.com', 'Ana',           'Fernández Díaz',   '$2y$10$l0CSXrTqGcLDI47M7jtDdu36DULiKZ7PGUlzJtVeIElReDZGl29nC', 'cocinero', 'cocinero2.jpg', '2026-03-04 17:02:42'),
(29, 'camarero1',   'camarero1@bistro.com', 'David',         'Moreno Torres',    '$2y$10$xpMOFhCSl4L/BHu2q.cbnuBu5xa0tZZqCn2FUw.S0NC9YVVg12nMy', 'camarero', 'camarero1.jpg', '2026-03-04 17:02:42'),
(30, 'camarero2',   'camarero2@bistro.com', 'Lucía',         'Navarro Gil',      '$2y$10$hGXm174gvCHdYgzO.WdOr.2KWLabMB2P9ymXu7ltw5tSZDS5kTcje', 'camarero', 'camarero2.jpg', '2026-03-04 17:02:42'),
(31, 'gerente1',    'gerente@bistro.com',   'Alberto',       'Ramírez Castillo', '$2y$10$CxOAkawkO61HSP6OTgw/fuaUU9VsdIwD3gUtEDoYcDTT9KP/NgG9m', 'gerente',  'gerente.jpg',   '2026-03-04 17:02:42'),
(32, 'admin',       'admin@bistro.com',     'Administrador', 'Sistema',          '$2y$10$M6dlz8/4fq37pq9ATYhp3exQceC4c9riGhEu25N/m981wH1N9t0Gq', 'admin',    'admin.jpg',     '2026-03-04 17:02:42'),
(41, 'Prince02',    'prince@gmail.com',     'Prince',        'William',          '$2y$10$yiGXMxNEDBS7So3XIYwtZ.hbcbpTg1tYQ8ICaQZyBBK1NH56eBUM2', 'cliente',  NULL,            '2026-03-11 20:58:02'),
(43, 'Aleksandra02','alisicka@ucm.es',      'ALEKSANDRA',    'LISICKA',          '$2y$10$mpWDx2sJxWs43M1DU8ofnuoaaw5vYEfoRTKM3zHVjJAY8IlEfbqm2', 'cliente',  NULL,            '2026-03-12 09:44:50');
 
-- Categorías
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imgCategoriaProd`) VALUES
(1, 'Bebidas',         'Refrescos, zumos, agua, cafés y otras bebidas frías o calientes.', 'bebidas.jpg'),
(2, 'Bocadillos',      'Bocadillos variados preparados con pan fresco e ingredientes de calidad.', 'bocadillos.jpg'),
(3, 'Ensaladas',       'Ensaladas frescas y saludables con ingredientes variados.', 'ensaladas.jpg'),
(4, 'Platos Calientes','Platos principales servidos calientes, recién preparados en cocina.', 'platos_calientes.jpg'),
(5, 'Postres',         'Postres caseros y dulces para finalizar la comida.', 'postres.jpg'),
(6, 'Snacks',          'Aperitivos y pequeños tentempiés para cualquier momento.', 'snacks.jpg');
 
-- Productos
INSERT INTO `productos` (`id`, `nombreProd`, `descripcion`, `categoria_id`, `precio`, `iva`, `stock`, `disponible`, `ofertado`, `fechaCreacion`) VALUES
(1,  'Coca-Cola 33cl',       'Refresco de cola servido frío.',                      1, 2.00, '10', 100, 1, 0, '2026-03-04 16:26:45'),
(2,  'Agua Mineral 50cl',    'Botella de agua mineral natural.',                    1, 1.50, '10', 150, 1, 0, '2026-03-04 16:26:45'),
(3,  'Café Espresso',        'Café espresso recién molido.',                        1, 1.80, '10',  80, 1, 0, '2026-03-04 16:26:45'),
(4,  'Bocadillo de Jamón',   'Pan crujiente con jamón serrano.',                    2, 4.50, '10',  40, 1, 0, '2026-03-04 16:26:45'),
(5,  'Bocadillo Vegetal',    'Lechuga, tomate, atún y mayonesa.',                   2, 4.00, '10',  35, 1, 1, '2026-03-04 16:26:45'),
(6,  'Bocadillo de Pollo',   'Pollo a la plancha con salsa especial.',              2, 4.80, '10',  30, 1, 0, '2026-03-04 16:26:45'),
(7,  'Ensalada César',       'Lechuga, pollo, queso parmesano y salsa César.',      3, 6.50, '10',  25, 1, 0, '2026-03-04 16:26:45'),
(8,  'Ensalada Mixta',       'Lechuga, tomate, cebolla, atún y aceitunas.',         3, 5.50, '10',  30, 1, 0, '2026-03-04 16:26:45'),
(9,  'Hamburguesa Completa', 'Hamburguesa con queso, lechuga, tomate y bacon.',     4, 8.50, '10',  20, 1, 1, '2026-03-04 16:26:45'),
(10, 'Lasaña Casera',        'Lasaña de carne gratinada al horno.',                 4, 9.00, '10',  15, 1, 0, '2026-03-04 16:26:45'),
(11, 'Pechuga a la Plancha', 'Pechuga de pollo con guarnición.',                   4, 7.50, '10',  18, 1, 0, '2026-03-04 16:26:45'),
(12, 'Tarta de Queso',       'Tarta de queso casera con mermelada.',                5, 4.00, '10',  20, 1, 1, '2026-03-04 16:26:45'),
(13, 'Brownie de Chocolate', 'Brownie caliente con nueces.',                        5, 3.80, '10',  25, 1, 0, '2026-03-04 16:26:45'),
(14, 'Patatas Fritas',       'Ración de patatas fritas crujientes.',                6, 3.00, '10',  50, 1, 0, '2026-03-04 16:26:45'),
(15, 'Nachos con Queso',     'Nachos acompañados con salsa de queso.',              6, 4.50, '10',  35, 1, 0, '2026-03-04 16:26:45');
 
-- Imágenes de productos
INSERT INTO `producto_imagenes` (`id`, `producto_id`, `rutaImagen`) VALUES
(1,  1,  'cocacola.jpg'),
(2,  1,  'cocacola_lata.jpg'),
(3,  2,  'agua_mineral.jpg'),
(4,  3,  'cafe_espresso.jpg'),
(5,  4,  'bocadillo_jamon.jpg'),
(6,  5,  'bocadillo_vegetal.jpg'),
(7,  5,  'bocadillo_vegetal_abierto.jpg'),
(8,  6,  'bocadillo_pollo.jpg'),
(9,  7,  'ensalada_cesar.jpg'),
(10, 8,  'ensalada_mixta.jpg'),
(11, 9,  'hamburguesa_completa.jpg'),
(12, 9,  'hamburguesa_interior.jpg'),
(13, 10, 'lasana_casera.jpg'),
(14, 11, 'pechuga_plancha.jpg'),
(15, 12, 'tarta_queso.jpg'),
(16, 13, 'brownie_chocolate.jpg'),
(17, 14, 'patatas_fritas.jpg'),
(18, 15, 'nachos_queso.jpg');
 
-- Pedidos
INSERT INTO `pedidos` (`id`, `usuario_id`, `estado`, `tipo`, `fechaPedido`, `total`, `subtotalSinDescuento`, `descuentoAplicado`, `cocinero_id`) VALUES
(2, 25, 'listo_cocina', 'recogida', '2026-03-25 17:04:35', 10.50, 10.50, 0.00, 27);
 
-- Productos del pedido
INSERT INTO `pedido_productos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precioUnitario`, `ivaAplicado`, `preparado`) VALUES
(2, 2, 1, 2, 3.50, '21', 0);
 
-- Ofertas
INSERT INTO `ofertas` (`id`, `nombre`, `descripcion`, `fechaInicio`, `fechaFin`, `descuento`, `activa`) VALUES
(1, 'Desayuno Bistro',   'Incluye 1 Café Espresso y 1 Tarta de Queso',               '2026-04-15', '2026-05-31', 20.00, 1),
(2, 'Menú Hamburguesa',  'Hamburguesa completa con patatas fritas y bebida',          '2026-04-15', '2026-06-30', 15.00, 1),
(3, 'Pack Snack',        'Nachos con queso y bebida',                                 '2026-04-15', '2026-05-31', 10.00, 1);
 
-- Productos de cada oferta
INSERT INTO `oferta_productos` (`oferta_id`, `producto_id`, `cantidad`) VALUES
(1, 3,  1),   -- Desayuno Bistro: 1 Café Espresso
(1, 12, 1),   -- Desayuno Bistro: 1 Tarta de Queso
(2, 9,  1),   -- Menú Hamburguesa: 1 Hamburguesa Completa
(2, 14, 1),   -- Menú Hamburguesa: 1 Patatas Fritas
(2, 1,  1),   -- Menú Hamburguesa: 1 Coca-Cola
(3, 15, 1),   -- Pack Snack: 1 Nachos con Queso
(3, 1,  1);   -- Pack Snack: 1 Coca-Cola
