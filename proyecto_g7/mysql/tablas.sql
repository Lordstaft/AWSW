SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Eliminar tablas existentes para recrearlas limpiamente
DROP TABLE IF EXISTS `pedido_ofertas`;
DROP TABLE IF EXISTS `oferta_productos`;
DROP TABLE IF EXISTS `pedido_productos`;
DROP TABLE IF EXISTS `pedidos`;
DROP TABLE IF EXISTS `producto_imagenes`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `ofertas`;
DROP TABLE IF EXISTS `usuarios`;

-- ============================================================
-- FUNCIONALIDAD 0: Gestión de Usuarios
-- ============================================================

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('cliente','camarero','cocinero','gerente','admin') NOT NULL DEFAULT 'cliente',
  `avatar` varchar(255) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreUsuario` (`nombreUsuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- FUNCIONALIDAD 1: Gestión de Productos
-- ============================================================

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imgCategoriaProd` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Modificación: 'categoria_id' permite NULL para no romper el producto si se elimina la categoría físicamente
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreProd` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `iva` enum('4','10','21') NOT NULL,
  `stock` int(11) DEFAULT 0,
  `disponible` tinyint(1) DEFAULT 1,
  `ofertado` tinyint(1) DEFAULT 1,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `producto_imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `rutaImagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `fk_imagenes_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- FUNCIONALIDAD 2: Gestión de Pedidos
-- ============================================================

-- Modificación: 'usuario_id' permite NULL para preservar facturación si un cliente se da de baja
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `estado` enum('nuevo','recibido','en_preparacion','cocinando','listo_cocina','terminado','entregado','cancelado') DEFAULT 'nuevo',
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
  CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_cocinero` FOREIGN KEY (`cocinero_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Modificación: ON DELETE RESTRICT en pedido y producto para evitar pérdida de registros históricos de venta
CREATE TABLE `pedido_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `ivaAplicado` enum('4','10','21') NOT NULL,
  `preparado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `fk_pp_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- FUNCIONALIDAD 4: Gestión de Ofertas
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Modificación: Impedir el borrado accidental de productos que forman parte de la configuración de una oferta activa
CREATE TABLE `oferta_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oferta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `oferta_id` (`oferta_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `fk_op_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_op_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Modificación: Proteger con RESTRICT para no alterar informes financieros
CREATE TABLE `pedido_ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `oferta_id` int(11) NOT NULL,
  `vecesAplicada` int(11) NOT NULL,
  `descuentoTotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `oferta_id` (`oferta_id`),
  CONSTRAINT `fk_po_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_po_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;