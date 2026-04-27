-- Table: vendedores
CREATE TABLE vendedores (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(45) NULL,
    apellido VARCHAR(45) NULL,
    telefono VARCHAR(10) NULL
);

-- Table: propiedades
CREATE TABLE propiedades (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(60) NULL,
    precio NUMERIC(10,2) NULL,
    imagen VARCHAR(200) NULL,
    descripcion TEXT NULL,
    habitaciones INTEGER NULL,
    wc INTEGER NULL,
    estacionamiento INTEGER NULL,
    "vendedorId" INTEGER NULL, -- Quoted because it's mixed case, or rename to vendedor_id
    creado DATE NULL,
    CONSTRAINT fk_vendedor
        FOREIGN KEY ("vendedorId")
        REFERENCES vendedores (id)
        ON DELETE SET NULL -- Assuming ON DELETE SET NULL is desired, as MySQL default is RESTRICT
);

-- Index for vendedorId
CREATE INDEX idx_vendedorId ON propiedades ("vendedorId");

-- Table: usuarios
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    email VARCHAR(60) NULL,
    password CHAR(60) NULL
);

-- Data for propiedades
INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, "vendedorId", creado) VALUES
('Cabaña', 1331.00, 'anuncio1.jpg', 'dio consectetur at. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 1, 2, 3, 1, '2021-02-05'),
('Casa Moderna', 13001091.00, 'anuncio2.jpg', 'dio consectetur at. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 3, 2, 1, 1, '2021-02-05'),
('Casa con Piscina', 130100.00, 'anuncio3.jpg', 'dio consectetur at. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 3, 1, 2, 1, '2021-02-05'),
('Casa en Promoción', 1313.00, 'anuncio4.jpg', 'dio consectetur at. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 3, 2, 1, 1, '2021-02-05'),
('Casa en el Lago', 1313.00, 'anuncio6.jpg', 'dio consectetur at. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 3, 2, 1, 1, '2021-02-05'),
(' Nueva Propiedad (Actualizado)', 918399.00, 'b6d263cf62caa0f8a056975f1422a9bb.jpg', 'Probando Demo para Video Probando Demo para Video Probando Demo para Video Probando Demo para Video', 3, 3, 3, 1, '2022-07-20');

-- Data for usuarios
INSERT INTO usuarios (email, password) VALUES
('correo@correo.com', '$2y$10$qb.EdDL1jR/Jc6JGFy9fT.t054KASVYqSWeqJHknF9ETutIb1AI4W');

-- Data for vendedores
INSERT INTO vendedores (id, nombre, apellido, telefono) VALUES
(1, 'Juan', 'De la torre', '091390109'),
(2, 'KAREN ACT', 'Perez', '0123456789');

-- To ensure SERIAL sequence is updated after manual inserts for tables with existing data
SELECT setval('vendedores_id_seq', (SELECT MAX(id) FROM vendedores));
SELECT setval('propiedades_id_seq', (SELECT MAX(id) FROM propiedades));
SELECT setval('usuarios_id_seq', (SELECT MAX(id) FROM usuarios));