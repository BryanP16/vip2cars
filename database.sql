-- =============================================================
-- VIP2CARS + Encuestas Anónimas | Script SQL Completo
-- Compatible: MySQL 8.0+ / MariaDB 10.6+
-- Todos los nombres de tablas y columnas en español
-- =============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

-- ---------------------------------------------------------------
-- BASE DE DATOS
-- ---------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS vip2cars
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE vip2cars;

-- =============================================================
-- MÓDULO 1 : ENCUESTAS ANÓNIMAS
-- =============================================================

-- -----------------------------------------------------------
-- 1.1  encuestas
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS encuestas (
    id          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    titulo      VARCHAR(200)     NOT NULL,
    descripcion TEXT             NULL,
    activa      TINYINT(1)       NOT NULL DEFAULT 1,
    inicio_en   TIMESTAMP        NULL     DEFAULT NULL,
    fin_en      TIMESTAMP        NULL     DEFAULT NULL,
    created_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_encuestas PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Encuestas disponibles en el sistema';

-- -----------------------------------------------------------
-- 1.2  preguntas
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS preguntas (
    id              BIGINT UNSIGNED                                      NOT NULL AUTO_INCREMENT,
    encuesta_id     BIGINT UNSIGNED                                      NOT NULL,
    texto_pregunta  TEXT                                                 NOT NULL,
    tipo            ENUM('unica','multiple','texto','escala')            NOT NULL DEFAULT 'unica',
    orden           INT UNSIGNED                                         NOT NULL DEFAULT 0,
    obligatoria     TINYINT(1)                                           NOT NULL DEFAULT 1,
    created_at      TIMESTAMP                                            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP                                            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_preguntas       PRIMARY KEY (id),
    CONSTRAINT fk_preguntas_encuesta
        FOREIGN KEY (encuesta_id) REFERENCES encuestas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_preguntas_encuesta (encuesta_id, orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Preguntas pertenecientes a una encuesta';

-- -----------------------------------------------------------
-- 1.3  opciones
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS opciones (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    pregunta_id  BIGINT UNSIGNED NOT NULL,
    texto_opcion VARCHAR(300)    NOT NULL,
    orden        INT UNSIGNED    NOT NULL DEFAULT 0,
    created_at   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_opciones        PRIMARY KEY (id),
    CONSTRAINT fk_opciones_pregunta
        FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_opciones_pregunta (pregunta_id, orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Opciones de respuesta para preguntas cerradas';

-- -----------------------------------------------------------
-- 1.4  sesiones_encuesta
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS sesiones_encuesta (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    encuesta_id   BIGINT UNSIGNED NOT NULL,
    token_sesion  VARCHAR(64)     NOT NULL,
    hash_ip       VARCHAR(64)     NULL COMMENT 'Hash SHA-256 de la IP — nunca la IP real',
    hash_agente   VARCHAR(64)     NULL COMMENT 'Hash del User-Agent',
    enviada_en    TIMESTAMP       NULL DEFAULT NULL,
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_sesiones_encuesta PRIMARY KEY (id),
    CONSTRAINT fk_sesiones_encuesta
        FOREIGN KEY (encuesta_id) REFERENCES encuestas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE INDEX uq_token_sesion (token_sesion),
    INDEX idx_sesiones_encuesta (encuesta_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sesiones anónimas — garantizan anonimato del encuestado';

-- -----------------------------------------------------------
-- 1.5  respuestas
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS respuestas (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    sesion_id        BIGINT UNSIGNED NOT NULL,
    pregunta_id      BIGINT UNSIGNED NOT NULL,
    opcion_id        BIGINT UNSIGNED NULL     DEFAULT NULL,
    respuesta_texto  TEXT            NULL,
    valor_escala     TINYINT         NULL     DEFAULT NULL COMMENT 'Valor del 1 al 10 para preguntas de escala',
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_respuestas         PRIMARY KEY (id),
    CONSTRAINT fk_respuestas_sesion
        FOREIGN KEY (sesion_id)    REFERENCES sesiones_encuesta(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_respuestas_pregunta
        FOREIGN KEY (pregunta_id)  REFERENCES preguntas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_respuestas_opcion
        FOREIGN KEY (opcion_id)    REFERENCES opciones(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_respuestas_sesion   (sesion_id),
    INDEX idx_respuestas_pregunta (pregunta_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Respuestas de cada participante a cada pregunta';

-- =============================================================
-- MÓDULO 2 : VIP2CARS (Vehículos y Clientes)
-- =============================================================

-- -----------------------------------------------------------
-- 2.1  clientes
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id              BIGINT UNSIGNED                                NOT NULL AUTO_INCREMENT,
    Nombres         VARCHAR(100)                                   NOT NULL,
    Apellidos       VARCHAR(100)                                   NOT NULL,
    TipoDocumento   ENUM('DNI','CE','RUC','PASSPORT')             NOT NULL DEFAULT 'DNI',
    NroDocumento    VARCHAR(20)                                    NOT NULL,
    Correo          VARCHAR(150)                                   NOT NULL,
    Telefono        VARCHAR(20)                                    NOT NULL,
    created_at      TIMESTAMP                                      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP                                      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP                                      NULL     DEFAULT NULL COMMENT 'Soft delete',
    CONSTRAINT pk_clientes          PRIMARY KEY (id),
    UNIQUE INDEX uq_clientes_doc    (TipoDocumento, NroDocumento),
    UNIQUE INDEX uq_clientes_correo (Correo),
    INDEX idx_clientes_nombre       (Apellidos, Nombres),
    INDEX idx_clientes_deleted      (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Clientes registrados en VIP2CARS';

-- -----------------------------------------------------------
-- 2.2  vehiculos
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS vehiculos (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_cliente       BIGINT UNSIGNED NOT NULL,
    placa            VARCHAR(10)     NOT NULL,
    marca            VARCHAR(80)     NOT NULL,
    modelo           VARCHAR(80)     NOT NULL,
    anio_fabricacion YEAR            NOT NULL,
    color            VARCHAR(40)     NULL,
    vin              VARCHAR(17)     NULL COMMENT 'Número de identificación del vehículo',
    observaciones    TEXT            NULL,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at       TIMESTAMP       NULL     DEFAULT NULL COMMENT 'Soft delete',
    CONSTRAINT pk_vehiculos          PRIMARY KEY (id),
    CONSTRAINT fk_vehiculos_cliente
        FOREIGN KEY (id_cliente) REFERENCES clientes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE INDEX uq_vehiculos_placa  (placa),
    INDEX idx_vehiculos_cliente      (id_cliente),
    INDEX idx_vehiculos_marca        (marca, modelo),
    INDEX idx_vehiculos_deleted      (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Vehículos registrados en VIP2CARS';

-- =============================================================
-- DATOS DE DEMOSTRACIÓN
-- =============================================================

-- Encuestas demo
INSERT INTO encuestas (titulo, descripcion, activa) VALUES
('Satisfacción de Servicio VIP2CARS', 'Evalúa la calidad de atención recibida en nuestro taller.', 1),
('Preferencias de Vehículos 2025',    'Conoce qué marcas prefieren nuestros clientes.',            1);

-- Preguntas demo
INSERT INTO preguntas (encuesta_id, texto_pregunta, tipo, orden, obligatoria) VALUES
(1, '¿Cómo calificarías la atención al cliente?', 'escala',    1, 1),
(1, '¿Recomendarías nuestros servicios?',          'unica',     2, 1),
(1, '¿Qué aspectos mejorarías?',                  'texto',     3, 0),
(2, '¿Qué marca de vehículo prefieres?',           'unica',     1, 1),
(2, '¿Qué características valoras más?',           'multiple',  2, 1);

-- Opciones demo
INSERT INTO opciones (pregunta_id, texto_opcion, orden) VALUES
(2, 'Sí, definitivamente', 1),
(2, 'Probablemente sí',    2),
(2, 'Probablemente no',    3),
(2, 'No',                  4),
(4, 'Toyota',              1),
(4, 'Hyundai',             2),
(4, 'Ford',                3),
(4, 'BMW',                 4),
(4, 'Mercedes-Benz',       5),
(5, 'Seguridad',           1),
(5, 'Consumo de combustible', 2),
(5, 'Diseño',              3),
(5, 'Precio',              4),
(5, 'Tecnología',          5);

-- Clientes demo
INSERT INTO clientes (Nombres, Apellidos, TipoDocumento, NroDocumento, Correo, Telefono) VALUES
('Carlos', 'Mendoza Ríos',    'DNI', '12345678',    'carlos.mendoza@email.com', '+51 987 654 321'),
('Ana',    'Torres Vega',     'DNI', '87654321',    'ana.torres@email.com',     '+51 976 543 210'),
('Luis',   'García Paredes',  'CE',  'CE-001234',   'luis.garcia@email.com',    '+51 965 432 109'),
('María',  'López Castillo',  'DNI', '11223344',    'maria.lopez@email.com',    '+51 954 321 098'),
('Pedro',  'Ruiz Flores',     'RUC', '20123456789', 'pedro.ruiz@empresa.com',   '+51 943 210 987');

-- Vehículos demo
INSERT INTO vehiculos (id_cliente, placa, marca, modelo, anio_fabricacion, color) VALUES
(1, 'ABC-123', 'Toyota',        'Corolla',  2020, 'Blanco'),
(2, 'DEF-789', 'Hyundai',       'Tucson',   2021, 'Gris'),
(3, 'GHI-012', 'Ford',          'Explorer', 2019, 'Azul'),
(4, 'JKL-345', 'BMW',           '320i',     2023, 'Blanco'),
(5, 'MNO-678', 'Mercedes-Benz', 'GLE 350',  2022, 'Negro');

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================
-- FIN DEL SCRIPT
-- =============================================================