/*
  Core schema for SQL Server.
  Execute against semujeres_db.
*/

IF OBJECT_ID('dbo.valor_indicadores', 'U') IS NOT NULL DROP TABLE dbo.valor_indicadores;
IF OBJECT_ID('dbo.base_meta_indicador', 'U') IS NOT NULL DROP TABLE dbo.base_meta_indicador;
IF OBJECT_ID('dbo.var_indicador', 'U') IS NOT NULL DROP TABLE dbo.var_indicador;
IF OBJECT_ID('dbo.indicadores', 'U') IS NOT NULL DROP TABLE dbo.indicadores;
IF OBJECT_ID('dbo.ficha', 'U') IS NOT NULL DROP TABLE dbo.ficha;
IF OBJECT_ID('dbo.indicador', 'U') IS NOT NULL DROP TABLE dbo.indicador;
IF OBJECT_ID('dbo.programa_presupuestario', 'U') IS NOT NULL DROP TABLE dbo.programa_presupuestario;
IF OBJECT_ID('dbo.informe', 'U') IS NOT NULL DROP TABLE dbo.informe;
IF OBJECT_ID('dbo.avance', 'U') IS NOT NULL DROP TABLE dbo.avance;
IF OBJECT_ID('dbo.finanzas', 'U') IS NOT NULL DROP TABLE dbo.finanzas;
IF OBJECT_ID('dbo.p_especial', 'U') IS NOT NULL DROP TABLE dbo.p_especial;
IF OBJECT_ID('dbo.ods', 'U') IS NOT NULL DROP TABLE dbo.ods;
IF OBJECT_ID('dbo.municipios', 'U') IS NOT NULL DROP TABLE dbo.municipios;
IF OBJECT_ID('dbo.compromisos', 'U') IS NOT NULL DROP TABLE dbo.compromisos;
IF OBJECT_ID('dbo.linea_accion_pmp', 'U') IS NOT NULL DROP TABLE dbo.linea_accion_pmp;
IF OBJECT_ID('dbo.estrategia_pmp', 'U') IS NOT NULL DROP TABLE dbo.estrategia_pmp;
IF OBJECT_ID('dbo.objetivo_estrategias', 'U') IS NOT NULL DROP TABLE dbo.objetivo_estrategias;
IF OBJECT_ID('dbo.pmp', 'U') IS NOT NULL DROP TABLE dbo.pmp;
IF OBJECT_ID('dbo.entregable', 'U') IS NOT NULL DROP TABLE dbo.entregable;
IF OBJECT_ID('dbo.alineacion_ped', 'U') IS NOT NULL DROP TABLE dbo.alineacion_ped;
IF OBJECT_ID('dbo.lineas_accion_ped', 'U') IS NOT NULL DROP TABLE dbo.lineas_accion_ped;
IF OBJECT_ID('dbo.estrategia_ped', 'U') IS NOT NULL DROP TABLE dbo.estrategia_ped;
IF OBJECT_ID('dbo.objetivo_ped', 'U') IS NOT NULL DROP TABLE dbo.objetivo_ped;
IF OBJECT_ID('dbo.politica_publica', 'U') IS NOT NULL DROP TABLE dbo.politica_publica;
IF OBJECT_ID('dbo.eje', 'U') IS NOT NULL DROP TABLE dbo.eje;
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
	[descripcion] NVARCHAR(MAX) NULL,
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

CREATE TABLE dbo.p_especial (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	programa NVARCHAR(250) NOT NULL,
	objetivo NVARCHAR(250) NOT NULL,
	estrategia NVARCHAR(250) NOT NULL,
	linea_accion NVARCHAR(250) NOT NULL,
	fk_id_entregable INT NOT NULL,
	CONSTRAINT FK_p_especial_entregable
		FOREIGN KEY (fk_id_entregable) REFERENCES dbo.entregable(id_entregable)
		ON DELETE CASCADE
);

CREATE TABLE dbo.finanzas (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	fuente NVARCHAR(250) NOT NULL,
	monto DECIMAL(18,2) NOT NULL,
	porcentaje_ubp DECIMAL(10,2) NOT NULL DEFAULT(0),
	fk_id_entregable INT NOT NULL,
	CONSTRAINT FK_finanzas_entregable
		FOREIGN KEY (fk_id_entregable) REFERENCES dbo.entregable(id_entregable)
		ON DELETE CASCADE
);

CREATE TABLE dbo.ods (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	conclusion NVARCHAR(MAX) NOT NULL
);

CREATE TABLE dbo.compromisos (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	descripcion NVARCHAR(MAX) NOT NULL
);

CREATE TABLE dbo.municipios (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	nombre NVARCHAR(180) NOT NULL UNIQUE
);

CREATE TABLE dbo.pmp (
	id_pmp INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	tema NVARCHAR(250) NOT NULL
);

CREATE TABLE dbo.objetivo_estrategias (
	id_obj INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	obj_estrategia NVARCHAR(250) NOT NULL,
	fk_pmp INT NOT NULL,
	CONSTRAINT FK_objetivo_estrategias_pmp
		FOREIGN KEY (fk_pmp) REFERENCES dbo.pmp(id_pmp)
		ON DELETE CASCADE
);

CREATE TABLE dbo.estrategia_pmp (
	id_estrategia INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	estrategia_pmp NVARCHAR(250) NOT NULL,
	fk_obj INT NOT NULL,
	CONSTRAINT FK_estrategia_pmp_objetivo
		FOREIGN KEY (fk_obj) REFERENCES dbo.objetivo_estrategias(id_obj)
		ON DELETE CASCADE
);

CREATE TABLE dbo.linea_accion_pmp (
	id_linea_pmp INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	linea_pmp NVARCHAR(250) NOT NULL,
	fk_estrategia_pmp INT NOT NULL,
	CONSTRAINT FK_linea_accion_pmp_estrategia
		FOREIGN KEY (fk_estrategia_pmp) REFERENCES dbo.estrategia_pmp(id_estrategia)
		ON DELETE CASCADE
);

CREATE TABLE dbo.eje (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	eje NVARCHAR(180) NOT NULL
);

CREATE TABLE dbo.politica_publica (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	politica_publica NVARCHAR(250) NOT NULL,
	fk_eje INT NOT NULL,
	CONSTRAINT FK_politica_publica_eje
		FOREIGN KEY (fk_eje) REFERENCES dbo.eje(id)
		ON DELETE CASCADE
);

CREATE TABLE dbo.objetivo_ped (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	objetivo NVARCHAR(250) NOT NULL,
	fk_politica INT NOT NULL,
	CONSTRAINT FK_objetivo_ped_politica
		FOREIGN KEY (fk_politica) REFERENCES dbo.politica_publica(id)
		ON DELETE CASCADE
);

CREATE TABLE dbo.estrategia_ped (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	estrategia NVARCHAR(250) NOT NULL,
	fk_objetivo INT NOT NULL,
	CONSTRAINT FK_estrategia_ped_objetivo
		FOREIGN KEY (fk_objetivo) REFERENCES dbo.objetivo_ped(id)
		ON DELETE CASCADE
);

CREATE TABLE dbo.lineas_accion_ped (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	linea_accion NVARCHAR(250) NOT NULL,
	fk_estrategia INT NOT NULL,
	CONSTRAINT FK_lineas_accion_ped_estrategia
		FOREIGN KEY (fk_estrategia) REFERENCES dbo.estrategia_ped(id)
		ON DELETE CASCADE
);

CREATE TABLE dbo.alineacion_ped (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	eje NVARCHAR(180) NOT NULL,
	politica NVARCHAR(180) NOT NULL,
	objetivo NVARCHAR(180) NOT NULL,
	estrategia NVARCHAR(180) NOT NULL,
	linea NVARCHAR(180) NOT NULL,
	fk_id_programa INT NOT NULL,
	CONSTRAINT FK_alineacion_ped_programa
		FOREIGN KEY (fk_id_programa) REFERENCES dbo.programa(id_programa)
		ON DELETE CASCADE
);

CREATE TABLE dbo.avance (
	id_avance INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	mes NVARCHAR(20) NULL,
	municipio NVARCHAR(180) NULL,
	avance_entregable INT NOT NULL,
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

CREATE TABLE dbo.programa_presupuestario (
	id_pp INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	pp NVARCHAR(250) NOT NULL
);

CREATE TABLE dbo.indicador (
	id_indicador INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	numero NVARCHAR(60) NULL,
	definicion NVARCHAR(MAX) NULL,
	formula NVARCHAR(100) NULL,
	fk_pp INT NULL,
	CONSTRAINT FK_indicador_pp
		FOREIGN KEY (fk_pp) REFERENCES dbo.programa_presupuestario(id_pp)
);

CREATE TABLE dbo.ficha (
	id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	numero_indicador NVARCHAR(60) NOT NULL,
	[año] INT NOT NULL,
	ficha NVARCHAR(255) NOT NULL
);

CREATE TABLE dbo.indicadores (
	id_indicadores INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
	responsable NVARCHAR(180) NULL,
	corresponsable NVARCHAR(180) NULL,
	indicador INT NULL,
	[año] INT NULL,
	en_a DECIMAL(18,4) NULL,
	en_b DECIMAL(18,2) NULL,
	en_c DECIMAL(18,2) NULL,
	feb_a DECIMAL(18,4) NULL,
	feb_b DECIMAL(18,2) NULL,
	feb_c DECIMAL(18,2) NULL,
	mar_a DECIMAL(18,4) NULL,
	mar_b DECIMAL(18,2) NULL,
	mar_c DECIMAL(18,2) NULL,
	ab_a DECIMAL(18,4) NULL,
	ab_b DECIMAL(18,2) NULL,
	ab_c DECIMAL(18,2) NULL,
	may_a DECIMAL(18,4) NULL,
	may_b DECIMAL(18,2) NULL,
	may_c DECIMAL(18,2) NULL,
	jun_a DECIMAL(18,4) NULL,
	jun_b DECIMAL(18,2) NULL,
	jun_c DECIMAL(18,2) NULL,
	jul_a DECIMAL(18,4) NULL,
	jul_b DECIMAL(18,2) NULL,
	jul_c DECIMAL(18,2) NULL,
	ago_a DECIMAL(18,4) NULL,
	ago_b DECIMAL(18,2) NULL,
	ago_c DECIMAL(18,2) NULL,
	sep_a DECIMAL(18,4) NULL,
	sep_b DECIMAL(18,2) NULL,
	sep_c DECIMAL(18,2) NULL,
	oct_a DECIMAL(18,4) NULL,
	oct_b DECIMAL(18,2) NULL,
	oct_c DECIMAL(18,2) NULL,
	nov_a DECIMAL(18,4) NULL,
	nov_b DECIMAL(18,2) NULL,
	nov_c DECIMAL(18,2) NULL,
	dic_a DECIMAL(18,4) NULL,
	dic_b DECIMAL(18,2) NULL,
	dic_c DECIMAL(18,2) NULL,
	anual_a DECIMAL(18,4) NULL,
	anual_b DECIMAL(18,2) NULL,
	anual_c DECIMAL(18,2) NULL,
	fk_user INT NOT NULL,
	CONSTRAINT FK_indicadores_usuario
		FOREIGN KEY (fk_user) REFERENCES dbo.usuario(usuario_id)
		ON DELETE CASCADE,
	CONSTRAINT FK_indicadores_indicador
		FOREIGN KEY (indicador) REFERENCES dbo.indicador(id_indicador)
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
