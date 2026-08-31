-- ============================================================
-- ESQUEMA NORMALIZADO - GIMVAL
-- Rediseño con separación de roles y adecuación a la
-- Ley 21.719 sobre Protección de Datos Personales (Chile)
-- ============================================================
 
SET FOREIGN_KEY_CHECKS = 0;
 
-- ------------------------------------------------------------
-- 1. CATÁLOGOS (evitan "números mágicos" sueltos en las tablas)
-- ------------------------------------------------------------
 
CREATE TABLE rol (
    id_rol      INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol  VARCHAR(30) NOT NULL UNIQUE   -- 'alumna', 'profesor', 'administrador'
);
 
INSERT INTO rol (nombre_rol) VALUES ('alumna'), ('profesor'), ('administrador');
 
CREATE TABLE estado_plan (
    id_estado_plan  INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado   VARCHAR(30) NOT NULL UNIQUE   -- 'activo', 'vencido', 'suspendido'
);
 
INSERT INTO estado_plan (nombre_estado) VALUES ('activo'), ('vencido'), ('suspendido');
 
-- ------------------------------------------------------------
-- 2. AUTENTICACIÓN
-- Solo credenciales de acceso. NO contiene nombre, RUT, ni
-- ningún dato personal identificable adicional
-- (principio de minimización de datos, art. 6 Ley 21.719).
-- ------------------------------------------------------------
 
CREATE TABLE usuario (
    id_usuario        INT AUTO_INCREMENT PRIMARY KEY,
    email             VARCHAR(120) NOT NULL UNIQUE,
    contrasena_hash   VARCHAR(255) NOT NULL,   -- SIEMPRE hash (bcrypt/argon2), nunca texto plano
    id_rol            INT NOT NULL,
    activo            TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso     DATETIME NULL,
    CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);
 
-- ------------------------------------------------------------
-- 3. SUBTIPOS DE PERSONA (uno por rol)
-- Cada uno referencia 1:1 a usuario. El "activo" permite
-- anonimizar en vez de borrar físicamente (derecho de
-- eliminación / "al olvido", art. 5 y 18 Ley 21.719), sin
-- destruir la integridad referencial del historial operativo.
-- ------------------------------------------------------------
 
CREATE TABLE alumna (
    rut                 VARCHAR(12) PRIMARY KEY,
    id_usuario          INT NOT NULL UNIQUE,
    nombre              VARCHAR(60) NOT NULL,
    apellido            VARCHAR(60) NOT NULL,
    fecha_nacimiento    DATE NOT NULL,          -- reemplaza "edad" (dato que caducaba)
    telefono            VARCHAR(20) NULL,
    fecha_registro      DATE NOT NULL DEFAULT (CURRENT_DATE),
    activo              TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_alumna_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);
 
CREATE TABLE profesor (
    rut                 VARCHAR(12) PRIMARY KEY,
    id_usuario          INT NOT NULL UNIQUE,
    nombre              VARCHAR(60) NOT NULL,
    apellido            VARCHAR(60) NOT NULL,
    especialidad        VARCHAR(100) NULL,
    fecha_contratacion  DATE NULL,
    activo              TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_profesor_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);
 
CREATE TABLE administrador (
    rut                 VARCHAR(12) PRIMARY KEY,
    id_usuario          INT NOT NULL UNIQUE,
    nombre              VARCHAR(60) NOT NULL,
    apellido            VARCHAR(60) NOT NULL,
    cargo               VARCHAR(60) NULL,
    activo              TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_admin_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);
 
-- ------------------------------------------------------------
-- 4. CONSENTIMIENTO SOBRE DATOS SENSIBLES
-- La ley exige base de licitud (consentimiento) para tratar
-- datos sensibles (ej. datos relacionados con salud/condición
-- física) y permitir su revocación en cualquier momento.
-- ------------------------------------------------------------
 
CREATE TABLE consentimiento (
    id_consentimiento   INT AUTO_INCREMENT PRIMARY KEY,
    rut_alumna          VARCHAR(12) NOT NULL,
    tipo_dato           VARCHAR(60) NOT NULL,     -- ej. 'datos_fisicos_salud'
    fecha_otorgamiento  DATETIME NOT NULL,
    fecha_revocacion    DATETIME NULL,
    vigente             TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_consentimiento_alumna FOREIGN KEY (rut_alumna) REFERENCES alumna(rut)
);
 
-- ------------------------------------------------------------
-- 5. DATOS FÍSICOS / DE SALUD (separados y con acceso restringido)
-- No se mezclan con la identidad de la alumna. El acceso a esta
-- tabla debería limitarse a nivel de aplicación (solo la propia
-- alumna, su profesor asignado y administración).
-- ------------------------------------------------------------
 
CREATE TABLE datos_fisicos_alumna (
    id_dato_fisico  INT AUTO_INCREMENT PRIMARY KEY,
    rut_alumna      VARCHAR(12) NOT NULL,
    peso_kg         DECIMAL(5,2) NULL,   -- antes float: impreciso para datos sensibles
    altura_cm       DECIMAL(5,2) NULL,
    fecha_registro  DATE NOT NULL,
    CONSTRAINT fk_datosfisicos_alumna FOREIGN KEY (rut_alumna) REFERENCES alumna(rut)
);
 
-- ------------------------------------------------------------
-- 6. OPERACIÓN DEL GIMNASIO
-- ------------------------------------------------------------
 
CREATE TABLE bloque_horario (
    id_bloque       INT AUTO_INCREMENT PRIMARY KEY,
    rut_profesor    VARCHAR(12) NOT NULL,
    fecha           DATE NOT NULL,
    hora_inicio     TIME NOT NULL,
    hora_termino    TIME NOT NULL,
    cupos_maximos   INT NOT NULL,
    CONSTRAINT fk_bloque_profesor FOREIGN KEY (rut_profesor) REFERENCES profesor(rut)
);
 
CREATE TABLE reserva (
    id_reserva      INT AUTO_INCREMENT PRIMARY KEY,
    id_bloque       INT NOT NULL,
    rut_alumna      VARCHAR(12) NOT NULL,
    fecha_reserva   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    asistencia      TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_reserva_bloque FOREIGN KEY (id_bloque) REFERENCES bloque_horario(id_bloque),
    CONSTRAINT fk_reserva_alumna FOREIGN KEY (rut_alumna) REFERENCES alumna(rut)
);
 
CREATE TABLE rutina (
    id_rutina   INT AUTO_INCREMENT PRIMARY KEY,
    id_bloque   INT NOT NULL,
    fecha       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    -- rut_profesor eliminado: se infiere vía bloque_horario.rut_profesor
    CONSTRAINT fk_rutina_bloque FOREIGN KEY (id_bloque) REFERENCES bloque_horario(id_bloque)
);
 
CREATE TABLE ejercicio (
    id_ejercicio      INT AUTO_INCREMENT PRIMARY KEY,
    nombre_ejercicio  VARCHAR(120) NOT NULL,
    descripcion       TEXT NULL
);
 
CREATE TABLE detalle_rutina (
    id_detalle_rutina  INT AUTO_INCREMENT PRIMARY KEY,
    id_rutina          INT NOT NULL,
    id_ejercicio       INT NOT NULL,
    orden              INT NOT NULL DEFAULT 1,
    series             INT NOT NULL,
    repeticiones       INT NOT NULL,
    CONSTRAINT fk_detalle_rutina FOREIGN KEY (id_rutina) REFERENCES rutina(id_rutina),
    CONSTRAINT fk_detalle_ejercicio FOREIGN KEY (id_ejercicio) REFERENCES ejercicio(id_ejercicio)
);
 
CREATE TABLE progreso_alumna (
    id_progreso   INT AUTO_INCREMENT PRIMARY KEY,
    rut_alumna    VARCHAR(12) NOT NULL,
    id_ejercicio  INT NOT NULL,
    fecha         DATE NOT NULL,
    peso_kg       DECIMAL(5,2) NULL,
    CONSTRAINT fk_progreso_alumna FOREIGN KEY (rut_alumna) REFERENCES alumna(rut),
    CONSTRAINT fk_progreso_ejercicio FOREIGN KEY (id_ejercicio) REFERENCES ejercicio(id_ejercicio)
);
 
-- ------------------------------------------------------------
-- 7. PLANES Y CONVENIOS
-- (sin procesamiento de pagos: plan.precio es solo informativo)
-- ------------------------------------------------------------
 
CREATE TABLE plan (
    id_plan             INT AUTO_INCREMENT PRIMARY KEY,
    nombre_plan         VARCHAR(120) NOT NULL,
    cantidad_clases     INT NOT NULL,
    precio              INT NOT NULL
);
 
CREATE TABLE plan_alumna (
    id_plan_alumna    INT AUTO_INCREMENT PRIMARY KEY,
    rut_alumna        VARCHAR(12) NOT NULL,
    id_plan           INT NOT NULL,
    fecha_inicio      DATE NOT NULL,
    fecha_termino     DATE NOT NULL,
    clases_restantes  INT NOT NULL,
    id_estado_plan    INT NOT NULL,
    CONSTRAINT fk_planalumna_alumna FOREIGN KEY (rut_alumna) REFERENCES alumna(rut),
    CONSTRAINT fk_planalumna_plan FOREIGN KEY (id_plan) REFERENCES plan(id_plan),
    CONSTRAINT fk_planalumna_estado FOREIGN KEY (id_estado_plan) REFERENCES estado_plan(id_estado_plan)
);
 
-- Convenio: códigos de descuento que las alumnas usan fuera del
-- gimnasio (comercios asociados). La plataforma no procesa pagos
-- de ningún tipo, por lo que no existe relación con plan_alumna
-- ni ninguna tabla de transacciones; es un catálogo autónomo.
CREATE TABLE convenio (
    id_convenio          INT AUTO_INCREMENT PRIMARY KEY,
    nombre_comercio      VARCHAR(250) NOT NULL,
    descripcion          TEXT NULL,
    codigo_promocional   VARCHAR(30) NOT NULL UNIQUE,
    estado               TINYINT(1) NOT NULL DEFAULT 1
);
 
SET FOREIGN_KEY_CHECKS = 1;
 