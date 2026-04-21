/*
  Core schema for SQL Server.
  Execute against semujeres_db.
*/

IF OBJECT_ID('dbo.valor_indicadores', 'U') IS NOT NULL DROP TABLE dbo.valor_indicadores;
IF OBJECT_ID('dbo.base_meta_indicador', 'U') IS NOT NULL DROP TABLE dbo.base_meta_indicador;
IF OBJECT_ID('dbo.var_indicador', 'U') IS NOT NULL DROP TABLE dbo.var_indicador;
IF OBJECT_ID('dbo.indicador', 'U') IS NOT NULL DROP TABLE dbo.indicador;
IF OBJECT_ID('dbo.programa_presupuestario', 'U') IS NOT NULL DROP TABLE dbo.programa_presupuestario;
IF OBJECT_ID('dbo.indicadores', 'U') IS NOT NULL DROP TABLE dbo.indicadores;
IF OBJECT_ID('dbo.informe', 'U') IS NOT NULL DROP TABLE dbo.informe;
IF OBJECT_ID('dbo.avance', 'U') IS NOT NULL DROP TABLE dbo.avance;
IF OBJECT_ID('dbo.entregable', 'U') IS NOT NULL DROP TABLE dbo.entregable;
IF OBJECT_ID('dbo.programa', 'U') IS NOT NULL DROP TABLE dbo.programa;
IF OBJECT_ID('dbo.pass_recovery', 'U') IS NOT NULL DROP TABLE dbo.pass_recovery;
IF OBJECT_ID('dbo.usuario', 'U') IS NOT NULL DROP TABLE dbo.usuario;
IF OBJECT_ID('dbo.dependencias', 'U') IS NOT NULL DROP TABLE dbo.dependencias;

CREATE TABLE dbo.dependencias (
	id_dep INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	nombre_dep NVARCHAR(180) NOT NULL UNIQUE
);

CREATE TABLE dbo.usuario (
	usuario_id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	correo NVARCHAR(180) NOT NULL UNIQUE,
	clave_acceso NVARCHAR(255) NOT NULL,
	nombre_usuario NVARCHAR(120) NOT NULL,
	apellido_usuario NVARCHAR(120) NOT NULL,
	dependencia NVARCHAR(180) NOT NULL,
	unidad_admin NVARCHAR(180) NULL,
	rol NVARCHAR(80) NOT NULL,
	foto_perfil NVARCHAR(255) NULL
);

CREATE TABLE dbo.pass_recovery (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	correo NVARCHAR(180) NOT NULL,
	token NVARCHAR(120) NOT NULL UNIQUE,
	CONSTRAINT FK_pass_recovery_usuario
		FOREIGN KEY (correo) REFERENCES dbo.usuario(correo)
		ON DELETE CASCADE
);

CREATE TABLE dbo.programa (
	id_programa INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	nombre_programa NVARCHAR(250) NOT NULL,
	[año] INT NULL,
	objetivo NVARCHAR(MAX) NULL,
	[descripción] NVARCHAR(MAX) NULL,
	nombre_responsable NVARCHAR(180) NULL,
	cargo_responsable NVARCHAR(180) NULL,
	correo_responsable NVARCHAR(180) NULL,
	tel_responsable NVARCHAR(60) NULL,
	brecha_genero NVARCHAR(MAX) NULL,
	ejeped NVARCHAR(180) NULL,
	politicaped NVARCHAR(180) NULL,
	objetivoped NVARCHAR(180) NULL,
	estrategiaped NVARCHAR(180) NULL,
	lineaped NVARCHAR(180) NULL,
	fk_user INT NOT NULL,
	rol_usuario NVARCHAR(80) NULL,
	CONSTRAINT FK_programa_usuario
		FOREIGN KEY (fk_user) REFERENCES dbo.usuario(usuario_id)
		ON DELETE CASCADE
);

CREATE TABLE dbo.entregable (
	id_entregable INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	nombre_entregable NVARCHAR(250) NOT NULL,
	periodicidad NVARCHAR(100) NULL,
	unidad_medida NVARCHAR(120) NULL,
	meta DECIMAL(18,2) NULL,
	municipalizable NVARCHAR(30) NULL,
	compromiso NVARCHAR(MAX) NULL,
	ods NVARCHAR(MAX) NULL,
	actividad_sigo NVARCHAR(MAX) NULL,
	entregable_sigo NVARCHAR(MAX) NULL,
	avg NVARCHAR(MAX) NULL,
	monto_total DECIMAL(18,2) NULL,
	porcentaje_ubp_total DECIMAL(10,2) NULL,
	fk_id_programa INT NOT NULL,
	CONSTRAINT FK_entregable_programa
		FOREIGN KEY (fk_id_programa) REFERENCES dbo.programa(id_programa)
		ON DELETE CASCADE
);

CREATE TABLE dbo.avance (
	id_avance INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	mes NVARCHAR(20) NULL,
	municipio NVARCHAR(180) NULL,
	avance_entregable NVARCHAR(MAX) NULL,
	monto DECIMAL(18,2) NULL,
	proyecto NVARCHAR(250) NULL,
	descripcion NVARCHAR(MAX) NULL,
	institucion NVARCHAR(250) NULL,
	avance_finalizado BIT NOT NULL DEFAULT(0),
	fk_id_entregable INT NOT NULL,
	poblacion NVARCHAR(250) NULL,
	m_t1 INT NULL,
	m_d1 INT NULL,
	m_i1 INT NULL,
	h_t1 INT NULL,
	h_d1 INT NULL,
	h_i1 INT NULL,
	m_ts INT NULL,
	m_ds INT NULL,
	m_is INT NULL,
	h_ts INT NULL,
	h_ds INT NULL,
	h_is INT NULL,
	m_10 INT NULL,
	h_10 INT NULL,
	m_15 INT NULL,
	h_15 INT NULL,
	m_ser INT NULL,
	h_ser INT NULL,
	m_padres INT NULL,
	h_padres INT NULL,
	ms_10 INT NULL,
	hs_10 INT NULL,
	ms_15 INT NULL,
	hs_15 INT NULL,
	ms_ser INT NULL,
	hs_ser INT NULL,
	ms_padres INT NULL,
	hs_padres INT NULL,
	CONSTRAINT FK_avance_entregable
		FOREIGN KEY (fk_id_entregable) REFERENCES dbo.entregable(id_entregable)
		ON DELETE CASCADE
);

CREATE TABLE dbo.informe (
	id_informe INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	trimestre NVARCHAR(50) NULL,
	periodo NVARCHAR(50) NULL,
	accion NVARCHAR(MAX) NULL,
	personas NVARCHAR(120) NULL,
	municipios NVARCHAR(250) NULL,
	objetivo NVARCHAR(MAX) NULL,
	descripcion NVARCHAR(MAX) NULL,
	fk_id_entregable INT NOT NULL,
	informe_finalizado BIT NOT NULL DEFAULT(0),
	CONSTRAINT FK_informe_entregable
		FOREIGN KEY (fk_id_entregable) REFERENCES dbo.entregable(id_entregable)
		ON DELETE CASCADE
);

CREATE TABLE dbo.indicadores (
	id_indicadores INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	responsable NVARCHAR(180) NULL,
	corresponsable NVARCHAR(180) NULL,
	indicador NVARCHAR(MAX) NULL,
	[año] INT NULL,
	en_b DECIMAL(18,2) NULL,
	en_c DECIMAL(18,2) NULL,
	feb_b DECIMAL(18,2) NULL,
	feb_c DECIMAL(18,2) NULL,
	fk_user INT NOT NULL,
	CONSTRAINT FK_indicadores_usuario
		FOREIGN KEY (fk_user) REFERENCES dbo.usuario(usuario_id)
		ON DELETE CASCADE
);

CREATE TABLE dbo.programa_presupuestario (
	id_pp INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	pp NVARCHAR(250) NOT NULL
);

CREATE TABLE dbo.indicador (
	id_indicador INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	numero NVARCHAR(60) NULL,
	definicion NVARCHAR(MAX) NULL,
	fk_pp INT NULL,
	CONSTRAINT FK_indicador_pp
		FOREIGN KEY (fk_pp) REFERENCES dbo.programa_presupuestario(id_pp)
);

CREATE TABLE dbo.var_indicador (
	id_variable INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	variable NVARCHAR(120) NULL,
	nombre NVARCHAR(250) NULL,
	fk_indicador INT NOT NULL,
	CONSTRAINT FK_var_indicador_indicador
		FOREIGN KEY (fk_indicador) REFERENCES dbo.indicador(id_indicador)
		ON DELETE CASCADE
);

CREATE TABLE dbo.base_meta_indicador (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	[año] INT NULL,
	linea_base DECIMAL(18,2) NULL,
	meta DECIMAL(18,2) NULL,
	fk_indicador INT NOT NULL,
	CONSTRAINT FK_base_meta_indicador_indicador
		FOREIGN KEY (fk_indicador) REFERENCES dbo.indicador(id_indicador)
		ON DELETE CASCADE
);

CREATE TABLE dbo.valor_indicadores (
	id_valor_indicadores INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	mes NVARCHAR(20) NULL,
	[año] INT NULL,
	valora DECIMAL(18,2) NULL,
	valorb DECIMAL(18,2) NULL,
	valorc DECIMAL(18,2) NULL,
	fk_indicadores INT NOT NULL,
	CONSTRAINT FK_valor_indicadores_indicadores
		FOREIGN KEY (fk_indicadores) REFERENCES dbo.indicadores(id_indicadores)
		ON DELETE CASCADE
);

INSERT INTO dbo.dependencias (nombre_dep)
SELECT N'SEMUJERES'
WHERE NOT EXISTS (SELECT 1 FROM dbo.dependencias WHERE nombre_dep = N'SEMUJERES');

INSERT INTO dbo.usuario (
	correo, clave_acceso, nombre_usuario, apellido_usuario, dependencia, unidad_admin, rol, foto_perfil
)
SELECT
	N'admin@semujeres.local',
	N'$2y$10$0w6UjW6J6XxW7s2m3cA9qezbDq6q8jJ4k8pQ4lNQ5F7Qf9H9r0xQG',
	N'Admin',
	N'Sistema',
	N'SEMUJERES',
	N'TI',
	N'Administrador',
	NULL
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.usuario WHERE correo = N'admin@semujeres.local'
);
