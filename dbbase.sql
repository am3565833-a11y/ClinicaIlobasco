-- =====================================================
-- BASE DE DATOS SIMPLIFICADA: ClinicaInternaSimple
-- =====================================================

-- Crear base de datos si no existe
IF DB_ID('ClinicaInternaSimple') IS NULL
BEGIN
    CREATE DATABASE ClinicaInternaSimple;
END
GO

USE ClinicaInternaSimple;
GO

-- Crear esquema principal
IF NOT EXISTS (SELECT 1 FROM sys.schemas WHERE name = 'clinic')
BEGIN
    EXEC('CREATE SCHEMA clinic');
END
GO

-- ================= TABLAS MAESTRAS =================

CREATE TABLE clinic.genero (
    genero_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);
GO

CREATE TABLE clinic.estado_cita (
    estado_cita_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);
GO

CREATE TABLE clinic.estado_pago (
    estado_pago_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);
GO

CREATE TABLE clinic.roles (
    rol_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre_rol VARCHAR(100) NOT NULL UNIQUE,
    descripcion NVARCHAR(MAX) NULL
);
GO

-- ================= USUARIOS =================

CREATE TABLE clinic.usuarios (
    usuario_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre_usuario VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARBINARY(64) NOT NULL,
    password_salt VARBINARY(32) NOT NULL,
    nombre_completo VARCHAR(250) NULL,
    email VARCHAR(255) NULL UNIQUE,
    rol_id INT NOT NULL,
    estado VARCHAR(10) NOT NULL DEFAULT 'Activo',
    fecha_creacion DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT chk_usuarios_estado CHECK (estado IN ('Activo','Inactivo')),
    CONSTRAINT fk_usuarios_roles FOREIGN KEY (rol_id) REFERENCES clinic.roles(rol_id)
);
GO

-- ================= PACIENTES =================

CREATE TABLE clinic.pacientes (
    paciente_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    apellido VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE NULL,
    genero_id INT NULL,
    telefono VARCHAR(50) NULL,
    email VARCHAR(255) NULL,
    direccion NVARCHAR(MAX) NULL,
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT fk_pacientes_genero FOREIGN KEY (genero_id) REFERENCES clinic.genero(genero_id),
    CONSTRAINT fk_pacientes_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_pacientes_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO

-- ================= ESPECIALIDADES =================

CREATE TABLE clinic.especialidades (
    especialidad_id INT IDENTITY(1,1) PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    descripcion NVARCHAR(MAX) NULL,
    area_medica VARCHAR(100) NULL,
    nivel_atencion VARCHAR(50) NULL,
    costo_consulta DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado VARCHAR(10) NOT NULL DEFAULT 'Activo',
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT chk_especialidad_estado CHECK (estado IN ('Activo','Inactivo')),
    CONSTRAINT fk_especialidades_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_especialidades_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO

-- ================= MEDICOS =================

CREATE TABLE clinic.medicos (
    medico_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    apellido VARCHAR(150) NOT NULL,
    especialidad_id INT NULL,
    telefono VARCHAR(50) NULL,
    email VARCHAR(255) NULL,
    fecha_contrato DATE NULL,
    estado VARCHAR(10) NOT NULL DEFAULT 'Activo',
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT chk_medicos_estado CHECK (estado IN ('Activo','Inactivo')),
    CONSTRAINT fk_medicos_especialidad FOREIGN KEY (especialidad_id) REFERENCES clinic.especialidades(especialidad_id),
    CONSTRAINT fk_medicos_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_medicos_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO

-- ================= CITAS =================

CREATE TABLE clinic.citas (
    cita_id INT IDENTITY(1,1) PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    fecha_hora DATETIME2 NOT NULL,
    estado_cita_id INT NOT NULL,
    motivo NVARCHAR(MAX) NULL,
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT fk_citas_paciente FOREIGN KEY (paciente_id) REFERENCES clinic.pacientes(paciente_id),
    CONSTRAINT fk_citas_medico FOREIGN KEY (medico_id) REFERENCES clinic.medicos(medico_id),
    CONSTRAINT fk_citas_estado FOREIGN KEY (estado_cita_id) REFERENCES clinic.estado_cita(estado_cita_id),
    CONSTRAINT fk_citas_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_citas_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO

-- ================= CONSULTAS =================

CREATE TABLE clinic.consultas (
    consulta_id INT IDENTITY(1,1) PRIMARY KEY,
    cita_id INT NOT NULL UNIQUE,
    diagnostico NVARCHAR(MAX) NULL,
    tratamiento NVARCHAR(MAX) NULL,
    prescripcion NVARCHAR(MAX) NULL,
    fecha_consulta DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT fk_consultas_cita FOREIGN KEY (cita_id) REFERENCES clinic.citas(cita_id),
    CONSTRAINT fk_consultas_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_consultas_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO

-- ================= FACTURAS =================

CREATE TABLE clinic.facturas (
    factura_id INT IDENTITY(1,1) PRIMARY KEY,
    cita_id INT NOT NULL UNIQUE,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00 CHECK (total >= 0),
    estado_pago_id INT NOT NULL,
    fecha_emision DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT fk_facturas_cita FOREIGN KEY (cita_id) REFERENCES clinic.citas(cita_id),
    CONSTRAINT fk_facturas_estado_pago FOREIGN KEY (estado_pago_id) REFERENCES clinic.estado_pago(estado_pago_id),
    CONSTRAINT fk_facturas_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_facturas_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO

-- ================= PAGOS =================

CREATE TABLE clinic.pagos (
    pago_id INT IDENTITY(1,1) PRIMARY KEY,
    factura_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL CHECK (monto > 0),
    metodo_pago VARCHAR(20) NOT NULL,
    fecha_pago DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    created_by INT NULL,
    updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
    updated_by INT NULL,
    CONSTRAINT chk_pagos_metodo CHECK (metodo_pago IN ('Efectivo','Tarjeta','Transferencia')),
    CONSTRAINT fk_pagos_factura FOREIGN KEY (factura_id) REFERENCES clinic.facturas(factura_id),
    CONSTRAINT fk_pagos_created_by FOREIGN KEY (created_by) REFERENCES clinic.usuarios(usuario_id),
    CONSTRAINT fk_pagos_updated_by FOREIGN KEY (updated_by) REFERENCES clinic.usuarios(usuario_id)
);
GO


USE ClinicaInternaSimple;
GO

-- ================= INSERTAR ROLES =================
INSERT INTO clinic.roles (nombre_rol, descripcion)
VALUES 
('Administrador', 'Acceso completo al sistema'),
('Medico', 'Acceso a módulos médicos'),
('Recepcionista', 'Acceso a citas y pacientes'),
('Contador', 'Acceso a facturación y pagos');
GO

-- ================= INSERTAR USUARIO ADMIN =================
-- Creamos un salt aleatorio y la contraseña hasheada
DECLARE @password NVARCHAR(50) = 'Adm1n$2025!'; -- contraseña segura
DECLARE @salt VARBINARY(32) = CRYPT_GEN_RANDOM(32); -- salt aleatorio
DECLARE @hash VARBINARY(64);

-- Crear hash SHA2_512(salt + password)
SET @hash = HASHBYTES('SHA2_512', @salt + CAST(@password AS VARBINARY(MAX)));

-- Insertar usuario admin
INSERT INTO clinic.usuarios (nombre_usuario, password_hash, password_salt, nombre_completo, email, rol_id, estado)
VALUES ('admin', @hash, @salt, 'Administrador Principal', 'admin@clinica.com', 
        (SELECT rol_id FROM clinic.roles WHERE nombre_rol = 'Administrador'), 'Activo');
GO


SELECT nombre_usuario, password_hash, password_salt, DATALENGTH(password_hash) AS hash_len
FROM clinic.usuarios
WHERE nombre_usuario = 'admin';



-- Reemplaza 'Adm1n$2025!' por la contraseña que crees que usaste
SELECT u.usuario_id
FROM clinic.usuarios u
WHERE u.nombre_usuario = 'admin'
  AND u.password_hash = HASHBYTES('SHA2_512', u.password_salt + CONVERT(VARBINARY(MAX), N'Adm1n$2025!'));
