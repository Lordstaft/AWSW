SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. USUARIOS
INSERT INTO `usuarios` (`id`, `nombreUsuario`, `email`, `nombre`, `apellidos`, `contraseña`, `rol`, `avatar`, `fechaRegistro`) VALUES
(25, 'cliente1', 'cliente1@bistro.com', 'Carlos', 'Martínez López', '$2y$10$A5JBKx2qdzd3ATu16rUctue9xd2Z6eNfC6FCmyKpJpE9jqbgLUBtC', 'cliente', 'cliente1.jpg', '2026-03-04 17:02:42'),
(26, 'cliente2', 'cliente2@bistro.com', 'Laura', 'Gómez Pérez', '$2y$10$7sTRo1QJwIoGRESOZ7vSaeYzLUmYyy2vfM8hlUgM.roPY2FGeaGW2', 'cliente', 'cliente2.jpg', '2026-03-04 17:02:42'),
(27, 'cocinero1', 'cocinero1@bistro.com', 'Miguel', 'Ruiz Sánchez', '$2y$10$Ko4S6ao8M4dxDgP126WR2O5lNSGmSB/yDezahGerjwm.rT.W3U43.', 'cocinero', 'cocinero1.jpg', '2026-03-04 17:02:42'),
(28, 'cocinero2', 'cocinero2@bistro.com', 'Ana', 'Fernández Díaz', '$2y$10$hsi76NuuOWcIPW8wU64rjeRRBhEctTD.wfx2aqrplvBtmst2swqTW', 'cocinero', 'cocinero2.jpg', '2026-03-04 17:02:42'),
(29, 'camarero1', 'camarero1@bistro.com', 'David', 'Moreno Torres', '$2y$10$viDBA2whUR.u4KhUEpD3o..Q5qpgK72PLwkW0pGyYwyU5eOZk4iC6', 'camarero', 'camarero1.jpg', '2026-03-04 17:02:42'),
(30, 'camarero2', 'camarero2@bistro.com', 'Lucía', 'Navarro Gil', '$2y$10$B2SAtPyh4mttcL4wVX/DEurmSyLGGwlNU80LjKxtzfXi4wOs1pkx.', 'camarero', 'camarero2.jpg', '2026-03-04 17:02:42'),
(31, 'gerente1', 'gerente@bistro.com', 'Alberto', 'Ramírez Castillo', '$2y$10$mMfgIVytGukpoAK3hPiasOGoJmve0KQbDLPcPEFb5KUDNgyrSB81i', 'gerente', 'gerente.jpg', '2026-03-04 17:02:42'),
(32, 'admin', 'admin@bistro.com', 'Administrador', 'Sistema', '$2y$10$FB5SXf1y2bizX6SavPR8X.N0iH478EMjIlPyWWklRSIfJYuvNn1oG', 'admin', 'admin.jpg', '2026-03-04 17:02:42');

-- 2. CATEGORIAS
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imgCategoriaProd`) VALUES
(1, 'Bebidas', 'Refrescos, zumos, agua, cafés y otras bebidas frías o calientes.', 'bebidas.jpg'),
(2, 'Bocadillos', 'Bocadillos variados preparados con pan fresco e ingredientes de calidad.', 'bocadillos.jpg'),
(3, 'Ensaladas', 'Ensaladas frescas y saludables con ingredientes variados.', 'ensaladas.jpg'),
(4, 'Platos Calientes', 'Platos principales servidos calientes, recién preparados en cocina.', 'platos_calientes.jpg'),
(5, 'Postres', 'Postres caseros y dulces para finalizar la comida.', 'postres.jpg'),
(6, 'Snacks', 'Aperitivos y pequeños tentempiés para cualquier momento.', 'snacks.jpg');

-- 3. PRODUCTOS (Corregido: sin puntos y coma internos)
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

-- 4. IMAGENES PRODUCTO
INSERT INTO `producto_imagenes` (`id`, `producto_id`, `rutaImagen`) VALUES
(1, 1, 'cocacola.jpg'),
(3, 2, 'agua_mineral.jpg'),
(4, 3, 'cafe_espresso.jpg'),
(5, 4, 'bocadillo_jamon.jpg'),
(6, 5, 'bocadillo_vegetal.jpg'),
(8, 6, 'bocadillo_pollo.jpg'),
(9, 7, 'ensalada_cesar.jpg'),
(10, 8, 'ensalada_mixta.jpg'),
(11, 9, 'hamburguesa_completa.jpg'),
(13, 10, 'lasana_casera.jpg'),
(14, 11, 'pechuga_plancha.jpg'),
(15, 12, 'tarta_queso.jpg'),
(16, 13, 'brownie_chocolate.jpg'),
(17, 14, 'patatas_fritas.jpg'),
(18, 15, 'nachos_queso.jpg');

-- 5. PEDIDOS
INSERT INTO `pedidos` (`id`, `usuario_id`, `estado`, `fechaPedido`, `tipo`, `total`, `cocinero_id`) VALUES
(1, 25, 'cocinando', '2026-03-05 13:00:00', 'recogida', 6.30, 27),
(2, 26, 'en_preparacion', '2026-03-05 13:05:00', 'domicilio', 10.00, 28),
(3, 25, 'entregado', '2026-03-05 13:10:00', 'domicilio', 12.50, 27),
(4, 26, 'entregado', '2026-03-05 13:20:00', 'recogida', 8.50, 28),
(5, 25, 'nuevo', '2026-03-05 13:25:00', 'domicilio', 9.00, NULL),
(6, 26, 'cancelado', '2026-03-05 13:30:00', 'recogida', 5.50, NULL);

-- 6. PRODUCTOS DEL PEDIDO
INSERT INTO `pedido_productos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precioUnitario`, `ivaAplicado`) VALUES
(1, 1, 3, 1, 1.80, '10'),
(2, 1, 4, 1, 4.50, '10'),
(3, 2, 9, 1, 8.50, '10'),
(4, 2, 2, 1, 1.50, '10'),
(5, 3, 7, 1, 6.50, '10'),
(6, 3, 14, 2, 3.00, '10'),
(7, 4, 9, 1, 8.50, '10'),
(8, 5, 10, 1, 9.00, '10'),
(9, 6, 8, 1, 5.50, '10');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;