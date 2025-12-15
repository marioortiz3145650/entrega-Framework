-- laying_hens_schema.sql

-- 1. Roles
CREATE TABLE roles (
    id_rol SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255)
);

-- 2. Usuarios
CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    fecha_registro DATE DEFAULT CURRENT_DATE,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (rol_id) REFERENCES roles(id_rol)
);

-- 3. Galpones
CREATE TABLE galpones (
    id_galpon SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    direccion VARCHAR(255)
);

-- 4. Raza
CREATE TABLE raza (
    id_raza SERIAL PRIMARY KEY,
    nombre_raza VARCHAR(100) NOT NULL
);

-- 5. Lotes
CREATE TABLE lotes (
    id_lote SERIAL PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL,
    galpon_id INT NOT NULL,
    raza_id INT NOT NULL,
    edad_semanas INT NOT NULL,
    produccion_pct DECIMAL(5,2),
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    FOREIGN KEY (galpon_id) REFERENCES galpones(id_galpon),
    FOREIGN KEY (raza_id) REFERENCES raza(id_raza)
);

-- 6. Estados
CREATE TABLE estados (
    id_estado SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- 7. Tratamientos
CREATE TABLE tratamientos (
    id_tratamiento SERIAL PRIMARY KEY,
    fecha DATE NOT NULL,
    lote_id INT NOT NULL,
    tratamiento VARCHAR(255) NOT NULL,
    estado_id INT NOT NULL,
    creado_por INT NOT NULL,
    FOREIGN KEY (lote_id) REFERENCES lotes(id_lote),
    FOREIGN KEY (estado_id) REFERENCES estados(id_estado),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id_usuario)
);

-- 8. Muertes
CREATE TABLE muertes (
    id_muerte SERIAL PRIMARY KEY,
    fecha DATE NOT NULL,
    lote_id INT NOT NULL,
    cantidad INT NOT NULL CHECK (cantidad >= 0),
    causa VARCHAR(255),
    creado_por INT NOT NULL,
    FOREIGN KEY (lote_id) REFERENCES lotes(id_lote),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id_usuario)
);

-- 9. Unidades de medida
CREATE TABLE unidades_de_medida (
    id_unidad SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    abreviatura VARCHAR(10) NOT NULL
);

-- 10. Tipos de alimento
CREATE TABLE tipos_de_alimento (
    id_tipo_insumo SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- 11. Alimento
CREATE TABLE alimento (
    id_insumo SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo_alimento_id INT NOT NULL,
    unidad_medida_id INT NOT NULL,
    stock_actual DECIMAL(10,2) DEFAULT 0,
    stock_minimo DECIMAL(10,2) DEFAULT 0,
    precio_unitario DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (tipo_alimento_id) REFERENCES tipos_de_alimento(id_tipo_insumo),
    FOREIGN KEY (unidad_medida_id) REFERENCES unidades_de_medida(id_unidad)
);

-- 12. Movimientos insumo
CREATE TABLE movimientos_insumo (
    id_movimiento SERIAL PRIMARY KEY,
    insumo_id INT NOT NULL,
    lote_id INT,
    fecha DATE NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    tipo_movimiento VARCHAR(10) NOT NULL CHECK (tipo_movimiento IN ('Entrada', 'Salida')),
    observaciones TEXT,
    creado_por INT NOT NULL,
    FOREIGN KEY (insumo_id) REFERENCES alimento(id_insumo),
    FOREIGN KEY (lote_id) REFERENCES lotes(id_lote),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id_usuario)
);

-- 13. Produccion
CREATE TABLE produccion (
    id_produccion SERIAL PRIMARY KEY,
    fecha DATE NOT NULL,
    lote_id INT NOT NULL,
    jumbo INT DEFAULT 0,
    aaa INT DEFAULT 0,
    aa INT DEFAULT 0,
    a INT DEFAULT 0,
    b INT DEFAULT 0,
    c INT DEFAULT 0,
    total INT DEFAULT 0,
    creado_por INT NOT NULL,
    FOREIGN KEY (lote_id) REFERENCES lotes(id_lote),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id_usuario)
);

-- 14. Factura
CREATE TABLE factura (
    id_factura SERIAL PRIMARY KEY,
    numero_factura VARCHAR(20) NOT NULL UNIQUE,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL DEFAULT CURRENT_TIME,
    total DECIMAL(12,2) NOT NULL,
    observaciones TEXT,
    estado_id INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (estado_id) REFERENCES estados(id_estado)
);

-- 15. Factura Items
CREATE TABLE factura_items (
    id_item SERIAL PRIMARY KEY,
    factura_id INT NOT NULL,
    descripcion VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    unidad VARCHAR(20) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES factura(id_factura) ON DELETE CASCADE
);

INSERT INTO roles (nombre, descripcion) VALUES
('Admin', 'Acceso total'),
('Aprendiz', 'Puede registrar datos'),
('Visitante', 'Solo lectura');

INSERT INTO estados (nombre) VALUES
('Pendiente'),
('Aplicado'),
('Emitida'),
('Anulada');

INSERT INTO raza (nombre_raza) VALUES
('Isa Brown'),
('Lohmann Brown');

INSERT INTO galpones (nombre, capacidad, direccion) VALUES
('Galpón 1', 500, 'Zona rural A'),
('Galpón 2', 400, 'Zona rural B');

INSERT INTO tipos_de_alimento (nombre) VALUES
('Alimento Starter'),
('Alimento Crecimiento');

INSERT INTO unidades_de_medida (nombre, abreviatura) VALUES
('Kilogramo', 'Kg'),
('Docena', 'Doc');