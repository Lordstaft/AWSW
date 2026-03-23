-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-03-2026 a las 17:06:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `awp2`
--
CREATE DATABASE IF NOT EXISTS `awp2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `awp2`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imgCategoriaProd` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imgCategoriaProd`) VALUES
(1, 'Bebidas', 'Refrescos, zumos, agua, cafés y otras bebidas frías o calientes.', 'bebidas.jpg'),
(2, 'Bocadillos', 'Bocadillos variados preparados con pan fresco e ingredientes de calidad.', 'bocadillos.jpg'),
(3, 'Ensaladas', 'Ensaladas frescas y saludables con ingredientes variados.', 'ensaladas.jpg'),
(4, 'Platos Calientes', 'Platos principales servidos calientes, recién preparados en cocina.', 'platos_calientes.jpg'),
(5, 'Postres', 'Postres caseros y dulces para finalizar la comida.', 'postres.jpg'),
(6, 'Snacks', 'Aperitivos y pequeños tentempiés para cualquier momento.', 'snacks.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `numPedido` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estadoPedido` enum('nuevo','recibido','en_preparacion','cocinando','listo_cocina','terminado','entregado','cancelado') DEFAULT 'nuevo',
  `fechaPedido` datetime DEFAULT current_timestamp(),
  `tipo` enum('domicilio','recogida') NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_productos`
--

DROP TABLE IF EXISTS `pedido_productos`;
CREATE TABLE `pedido_productos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `ivaAplicado` enum('4','10','21') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombreProd` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `iva` enum('4','10','21') NOT NULL,
  `stock` int(11) DEFAULT 0,
  `disponible` tinyint(1) DEFAULT 1,
  `ofertado` tinyint(1) DEFAULT 0,
  `fechaCreacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombreProd`, `descripcion`, `categoria_id`, `precio`, `iva`, `stock`, `disponible`, `ofertado`, `fechaCreacion`) VALUES
(1, 'Coca-Cola 33cl', 'Refresco de cola servido frío.', 1, 2.00, '10', 100, 1, 0, '2026-03-04 16:26:45'),
(2, 'Agua Mineral 50cl', 'Botella de agua mineral natural.', 1, 1.50, '10', 150, 1, 0, '2026-03-04 16:26:45'),
(3, 'Café Espresso', 'Café espresso recién molido.', 1, 1.80, '10', 80, 1, 0, '2026-03-04 16:26:45'),
(4, 'Bocadillo de Jamón', 'Pan crujiente con jamón serrano.', 2, 4.50, '10', 40, 1, 0, '2026-03-04 16:26:45'),
(5, 'Bocadillo Vegetal', 'Lechuga, tomate, atún y mayonesa.', 2, 4.00, '10', 35, 1, 1, '2026-03-04 16:26:45'),
(6, 'Bocadillo de Pollo', 'Pollo a la plancha con salsa especial.', 2, 4.80, '10', 30, 1, 0, '2026-03-04 16:26:45'),
(7, 'Ensalada César', 'Lechuga, pollo, queso parmesano y salsa César.', 3, 6.50, '10', 25, 1, 0, '2026-03-04 16:26:45'),
(8, 'Ensalada Mixta', 'Lechuga, tomate, cebolla, atún y aceitunas.', 3, 5.50, '10', 30, 1, 0, '2026-03-04 16:26:45'),
(9, 'Hamburguesa Completa', 'Hamburguesa con queso, lechuga, tomate y bacon.', 4, 8.50, '10', 20, 1, 1, '2026-03-04 16:26:45'),
(10, 'Lasaña Casera', 'Lasaña de carne gratinada al horno.', 4, 9.00, '10', 15, 1, 0, '2026-03-04 16:26:45'),
(11, 'Pechuga a la Plancha', 'Pechuga de pollo con guarnición.', 4, 7.50, '10', 18, 1, 0, '2026-03-04 16:26:45'),
(12, 'Tarta de Queso', 'Tarta de queso casera con mermelada.', 5, 4.00, '10', 20, 1, 1, '2026-03-04 16:26:45'),
(13, 'Brownie de Chocolate', 'Brownie caliente con nueces.', 5, 3.80, '10', 25, 1, 0, '2026-03-04 16:26:45'),
(14, 'Patatas Fritas', 'Ración de patatas fritas crujientes.', 6, 3.00, '10', 50, 1, 0, '2026-03-04 16:26:45'),
(15, 'Nachos con Queso', 'Nachos acompañados con salsa de queso.', 6, 4.50, '10', 35, 1, 0, '2026-03-04 16:26:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_imagenes`
--

DROP TABLE IF EXISTS `producto_imagenes`;
CREATE TABLE `producto_imagenes` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `rutaImagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_imagenes`
--

INSERT INTO `producto_imagenes` (`id`, `producto_id`, `rutaImagen`) VALUES
(1, 1, 'cocacola.jpg'),
(2, 1, 'cocacola_lata.jpg'),
(3, 2, 'agua_mineral.jpg'),
(4, 3, 'cafe_espresso.jpg'),
(5, 4, 'bocadillo_jamon.jpg'),
(6, 5, 'bocadillo_vegetal.jpg'),
(7, 5, 'bocadillo_vegetal_abierto.jpg'),
(8, 6, 'bocadillo_pollo.jpg'),
(9, 7, 'ensalada_cesar.jpg'),
(10, 8, 'ensalada_mixta.jpg'),
(11, 9, 'hamburguesa_completa.jpg'),
(12, 9, 'hamburguesa_interior.jpg'),
(13, 10, 'lasana_casera.jpg'),
(14, 11, 'pechuga_plancha.jpg'),
(15, 12, 'tarta_queso.jpg'),
(16, 13, 'brownie_chocolate.jpg'),
(17, 14, 'patatas_fritas.jpg'),
(18, 15, 'nachos_queso.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombreUsuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('cliente','gerente','camarero','cocinero','admin') NOT NULL DEFAULT 'cliente',
  `avatar` varchar(255) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `email`, `nombre`, `apellidos`, `contraseña`, `rol`, `avatar`, `fechaRegistro`) VALUES
(25, 'cliente1', 'cliente1@bistro.com', 'Carlos', 'Martínez López', '$2y$10$17AZXm5IaUsYCzTAsXud3.F0cuxqhKeYjE41AZ8sCRXYBlAFr9uFG', 'cliente', 'cliente1.jpg', '2026-03-04 17:02:42'),
(26, 'cliente2', 'cliente2@bistro.com', 'Laura', 'Gómez Pérez', '$2y$10$jYlTJleiD5WPhyqCZssrGujPopxnO6Ve7ZQrBsWOEVjCc2b3v0npG', 'cliente', 'cliente2.jpg', '2026-03-04 17:02:42'),
(27, 'cocinero1', 'cocinero1@bistro.com', 'Miguel', 'Ruiz Sánchez', '$2y$10$ng.fNkXfA1IfTO0ZnCoHcO3ClPc0ADsJNJ222cBl0zne1xqOlXRLu', 'cocinero', 'cocinero1.jpg', '2026-03-04 17:02:42'),
(28, 'cocinero2', 'cocinero2@bistro.com', 'Ana', 'Fernández Díaz', '$2y$10$l0CSXrTqGcLDI47M7jtDdu36DULiKZ7PGUlzJtVeIElReDZGl29nC', 'cocinero', 'cocinero2.jpg', '2026-03-04 17:02:42'),
(29, 'camarero1', 'camarero1@bistro.com', 'David', 'Moreno Torres', '$2y$10$xpMOFhCSl4L/BHu2q.cbnuBu5xa0tZZqCn2FUw.S0NC9YVVg12nMy', 'camarero', 'camarero1.jpg', '2026-03-04 17:02:42'),
(30, 'camarero2', 'camarero2@bistro.com', 'Lucía', 'Navarro Gil', '$2y$10$hGXm174gvCHdYgzO.WdOr.2KWLabMB2P9ymXu7ltw5tSZDS5kTcje', 'camarero', 'camarero2.jpg', '2026-03-04 17:02:42'),
(31, 'gerente1', 'gerente@bistro.com', 'Alberto', 'Ramírez Castillo', '$2y$10$CxOAkawkO61HSP6OTgw/fuaUU9VsdIwD3gUtEDoYcDTT9KP/NgG9m', 'gerente', 'gerente.jpg', '2026-03-04 17:02:42'),
(32, 'admin', 'admin@bistro.com', 'Administrador', 'Sistema', '$2y$10$M6dlz8/4fq37pq9ATYhp3exQceC4c9riGhEu25N/m981wH1N9t0Gq', 'admin', 'admin.jpg', '2026-03-04 17:02:42');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombreUsuario` (`nombreUsuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD CONSTRAINT `pedido_productos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`idPedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD CONSTRAINT `producto_imagenes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
