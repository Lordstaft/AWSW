SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

-- ============================================================
-- DATOS DE PRUEBA
-- ============================================================

-- 1. Usuarios
INSERT INTO `usuarios` (`id`, `nombreUsuario`, `email`, `nombre`, `apellidos`, `contraseña`, `rol`, `avatar`, `fechaRegistro`, `activo`) VALUES
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
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imgCategoriaProd`, `activo`) VALUES
(1, 'Bebidas', 'Refrescos, zumos, agua, cafés y otras bebidas frías o calientes.', 'bebidas.jpg', 1),
(2, 'Bocadillos', 'Bocadillos variados preparados con pan fresco e ingredientes de calidad.', 'bocadillos.jpg', 1),
(3, 'Ensaladas', 'Ensaladas frescas y saludables con ingredientes variados.', 'ensaladas.jpg', 1),
(4, 'Platos Calientes', 'Platos principales servidos calientes, recién preparados en cocina.', 'platos_calientes.jpg', 1),
(5, 'Postres', 'Postres caseros y dulces para finalizar la comida.', 'postres.jpg', 1),
(6, 'Snacks', 'Aperitivos y pequeños tentempiés para cualquier momento.', 'snacks.jpg', 1);

-- 3. Productos
INSERT INTO `productos` (`id`, `nombreProd`, `descripcion`, `categoria_id`, `precio`, `iva`, `stock`, `disponible`, `ofertado`, `activo`, `rutaImg`, `fechaCreacion`) VALUES
(1, 'Coca-Cola 33cl', 'Refresco de cola servido frío.', 1, 2.00, '10', 100, 1, 0, 1, 'cocacola.jpg', '2026-03-04 16:26:45'),
(2, 'Agua Mineral 50cl', 'Botella de agua mineral natural.', 1, 1.50, '10', 150, 1, 0, 1, 'agua_mineral.jpg', '2026-03-04 16:26:45'),
(3, 'Café Espresso', 'Café espresso recién molido.', 1, 1.80, '10', 80, 1, 0, 1, 'cafe_espresso.jpg', '2026-03-04 16:26:45'),
(4, 'Bocadillo de Jamón', 'Pan crujiente con jamón serrano.', 2, 4.50, '10', 40, 1, 0, 1, 'bocadillo_jamon.jpg', '2026-03-04 16:26:45'),
(5, 'Bocadillo Vegetal', 'Lechuga, tomate, atún y mayonesa.', 2, 4.00, '10', 35, 1, 1, 1, 'bocadillo_vegetal.jpg', '2026-03-04 16:26:45'),
(6, 'Bocadillo de Pollo', 'Pollo a la plancha con salsa especial.', 2, 4.80, '10', 30, 1, 0, 1, 'bocadillo_pollo.jpg', '2026-03-04 16:26:45'),
(7, 'Ensalada César', 'Lechuga, pollo, queso parmesano y salsa César.', 3, 6.50, '10', 25, 1, 0, 1, 'ensalada_cesar.jpg', '2026-03-04 16:26:45'),
(8, 'Ensalada Mixta', 'Lechuga, tomate, cebolla, atún y aceitunas.', 3, 5.50, '10', 30, 1, 0, 1, 'ensalada_mixta.jpg', '2026-03-04 16:26:45'),
(9, 'Hamburguesa Completa', 'Hamburguesa con queso, lechuga, tomate y bacon.', 4, 8.50, '10', 20, 1, 1, 1, 'hamburguesa_completa.jpg', '2026-03-04 16:26:45'),
(10, 'Lasaña Casera', 'Lasaña de carne gratinada al horno.', 4, 9.00, '10', 15, 1, 0, 1, 'lasana_casera.jpg', '2026-03-04 16:26:45'),
(11, 'Pechuga a la Plancha', 'Pechuga de pollo con guarnición.', 4, 7.50, '10', 18, 1, 0, 1, 'pechuga_plancha.jpg', '2026-03-04 16:26:45'),
(12, 'Tarta de Queso', 'Tarta de queso casera con mermelada.', 5, 4.00, '10', 20, 1, 1, 1, 'tarta_queso.jpg', '2026-03-04 16:26:45'),
(13, 'Brownie de Chocolate', 'Brownie caliente con nueces.', 5, 3.80, '10', 25, 1, 0, 1, 'brownie_chocolate.jpg', '2026-03-04 16:26:45'),
(14, 'Patatas Fritas', 'Ración de patatas fritas crujientes.', 6, 3.00, '10', 50, 1, 0, 1, 'patatas_fritas.jpg', '2026-03-04 16:26:45'),
(15, 'Nachos con Queso', 'Nachos acompañados con salsa de queso.', 6, 4.50, '10', 35, 1, 0, 1, 'nachos_queso.jpg', '2026-03-04 16:26:45');

-- 4. Pedidos
INSERT INTO `pedidos` (`id`, `usuario_id`, `estado`, `tipo`, `fechaPedido`, `total`, `subtotalSinDescuento`, `descuentoAplicado`, `cocinero_id`, `activo`) VALUES
(2, 25, 'listo_cocina', 'recogida', '2026-03-25 17:04:35', 10.50, 10.50, 0.00, 27, 1);

-- 5. Productos del pedido
INSERT INTO `pedido_productos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precioUnitario`, `ivaAplicado`, `preparado`) VALUES
(2, 2, 1, 2, 3.50, '21', 0);

-- 6. Ofertas
INSERT INTO `ofertas` (`id`, `nombre`, `descripcion`, `fechaInicio`, `fechaFin`, `descuento`, `activa`, `activo`) VALUES
(1, 'Desayuno Bistro', 'Incluye 1 Café Espresso y 1 Tarta de Queso', '2026-04-15', '2026-05-31', 20.00, 1, 1),
(2, 'Menú Hamburguesa', 'Hamburguesa completa con patatas fritas y bebida', '2026-04-15', '2026-06-30', 15.00, 1, 1),
(3, 'Pack Snack', 'Nachos con queso y bebida', '2026-04-15', '2026-05-31', 10.00, 1, 1);

-- 7. Productos de cada oferta
INSERT INTO `oferta_productos` (`id`, `oferta_id`, `producto_id`, `cantidad`) VALUES
(1, 1, 3, 1),
(2, 1, 12, 1),
(3, 2, 9, 1),
(4, 2, 14, 1),
(5, 2, 1, 1),
(6, 3, 15, 1),
(7, 3, 1, 1);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;