---- datos pruebas
INSERT INTO dbo.dependencias (nombre_dep)
SELECT N'SEMUJERES'
WHERE NOT EXISTS (SELECT 1 FROM dbo.dependencias WHERE nombre_dep = N'SEMUJERES');


INSERT INTO dbo.usuario (
	correo, clave_acceso, nombre_usuario, apellido_usuario, dependencia, unidad_admin, rol, foto_perfil
)
SELECT
	N'admin@semujeres.local',
	N'$2y$10$94y9eB7qDqrKa4yEkwYBrupXyXTKgB.xBpIaYNauf45kb7yM9PcCG',
	N'Fernanda',
	N'Canche',
	N'SEMUJERES',
	N'TI',
	N'Administrador',
	NULL
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.usuario WHERE correo = N'admin@semujeres.local'
);

DECLARE @admin_user_id INT = (
	SELECT TOP 1 usuario_id
	FROM dbo.usuario
	WHERE correo = N'admin@semujeres.local'
);

DECLARE @analista_user_id INT = (
	SELECT TOP 1 usuario_id
	FROM dbo.usuario
	WHERE correo = N'admin@semujeres.local'
);

INSERT INTO dbo.programa (
	nombre_programa, [año], objetivo, [descripcion], nombre_responsable, cargo_responsable,
	correo_responsable, tel_responsable, brecha_genero, ejeped, politicaped, objetivoped,
	estrategiaped, lineaped, fk_user, rol_usuario
)
SELECT
	N'Programa de Prevencion de Violencia', 2026,
	N'Reducir la violencia de genero en municipios prioritarios.',
	N'Intervenciones comunitarias, talleres y acompanamiento institucional.',
	N'Fernanda Canche', N'Jefa de Departamento', N'analista@semujeres.local', N'9991234567',
	N'Persistencia de violencia familiar en zonas urbanas y rurales.',
	N'Eje 2', N'Politica 2.3', N'Objetivo 2.3.1', N'Estrategia 2.3.1.a', N'Linea 2.3.1.a.1',
	COALESCE(@admin_user_id, @analista_user_id), N'Administrador'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.programa WHERE nombre_programa = N'Programa de Prevencion de Violencia' AND [año] = 2026
);

INSERT INTO dbo.programa (
	nombre_programa, [año], objetivo, [descripcion], nombre_responsable, cargo_responsable,
	correo_responsable, tel_responsable, brecha_genero, ejeped, politicaped, objetivoped,
	estrategiaped, lineaped, fk_user, rol_usuario
)
SELECT
	N'Autonomia Economica para Mujeres', 2026,
	N'Fortalecer capacidades economicas de mujeres en municipios con mayor rezago.',
	N'Capacitacion tecnica, vinculacion a empleo y apoyos para emprendimiento.',
	N'Fernanda Canche', N'Jefa de Departamento', N'analista@semujeres.local', N'9991234567',
	N'Baja participacion economica de mujeres en cadenas productivas locales.',
	N'Eje 3', N'Politica 3.2', N'Objetivo 3.2.2', N'Estrategia 3.2.2.b', N'Linea 3.2.2.b.2',
	@analista_user_id, N'Analista'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.programa WHERE nombre_programa = N'Autonomia Economica para Mujeres' AND [año] = 2026
);

DECLARE @programa_prevencion_id INT = (
	SELECT TOP 1 id_programa FROM dbo.programa
	WHERE nombre_programa = N'Programa de Prevencion de Violencia' AND [año] = 2026
);

DECLARE @programa_autonomia_id INT = (
	SELECT TOP 1 id_programa FROM dbo.programa
	WHERE nombre_programa = N'Autonomia Economica para Mujeres' AND [año] = 2026
);

INSERT INTO dbo.ods (conclusion)
SELECT N'ODS 5 - Lograr la igualdad entre los generos y empoderar a todas las mujeres y las ninas'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.ods
	WHERE conclusion = N'ODS 5 - Lograr la igualdad entre los generos y empoderar a todas las mujeres y las ninas'
);

INSERT INTO dbo.ods (conclusion)
SELECT N'ODS 8 - Promover el crecimiento economico sostenido, inclusivo y sostenible, el empleo pleno y productivo y el trabajo decente para todos'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.ods
	WHERE conclusion = N'ODS 8 - Promover el crecimiento economico sostenido, inclusivo y sostenible, el empleo pleno y productivo y el trabajo decente para todos'
);

INSERT INTO dbo.ods (conclusion)
SELECT N'ODS 10 - Reducir la desigualdad en y entre los paises'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.ods
	WHERE conclusion = N'ODS 10 - Reducir la desigualdad en y entre los paises'
);

INSERT INTO dbo.compromisos (descripcion)
SELECT N'Cumplir metas institucionales de atencion con enfoque de genero'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.compromisos
	WHERE descripcion = N'Cumplir metas institucionales de atencion con enfoque de genero'
);

INSERT INTO dbo.compromisos (descripcion)
SELECT N'Fortalecer capacidades de mujeres en situacion de vulnerabilidad'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.compromisos
	WHERE descripcion = N'Fortalecer capacidades de mujeres en situacion de vulnerabilidad'
);

INSERT INTO dbo.compromisos (descripcion)
SELECT N'Promover acciones interinstitucionales para prevenir violencias'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.compromisos
	WHERE descripcion = N'Promover acciones interinstitucionales para prevenir violencias'
);

INSERT INTO dbo.municipios (nombre)
SELECT N'Merida'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Merida');

INSERT INTO dbo.municipios (nombre)
SELECT N'Kanasin'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Kanasin');

INSERT INTO dbo.municipios (nombre)
SELECT N'Valladolid'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Valladolid');

INSERT INTO dbo.municipios (nombre)
SELECT N'Tizimin'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Tizimin');

INSERT INTO dbo.municipios (nombre)
SELECT N'Progreso'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Progreso');

INSERT INTO dbo.municipios (nombre)
SELECT N'Izamal'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Izamal');

INSERT INTO dbo.municipios (nombre)
SELECT N'Uman'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Uman');

INSERT INTO dbo.municipios (nombre)
SELECT N'Tekax'
WHERE NOT EXISTS (SELECT 1 FROM dbo.municipios WHERE nombre = N'Tekax');

INSERT INTO dbo.pmp (tema)
SELECT N'Programa Especial para Igualdad de Genero, Oportunidades y no Discriminacion'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.pmp
	WHERE tema = N'Programa Especial para Igualdad de Genero, Oportunidades y no Discriminacion'
);

INSERT INTO dbo.pmp (tema)
SELECT N'Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.pmp
	WHERE tema = N'Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres'
);

INSERT INTO dbo.pmp (tema)
SELECT N'Programa Especial para Prevencion del Embarazo en Adolescentes'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.pmp
	WHERE tema = N'Programa Especial para Prevencion del Embarazo en Adolescentes'
);

DECLARE @pmp_igualdad_id INT = (
	SELECT TOP 1 id_pmp FROM dbo.pmp
	WHERE tema = N'Programa Especial para Igualdad de Genero, Oportunidades y no Discriminacion'
);

DECLARE @pmp_violencia_id INT = (
	SELECT TOP 1 id_pmp FROM dbo.pmp
	WHERE tema = N'Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres'
);

DECLARE @pmp_embarazo_id INT = (
	SELECT TOP 1 id_pmp FROM dbo.pmp
	WHERE tema = N'Programa Especial para Prevencion del Embarazo en Adolescentes'
);

INSERT INTO dbo.objetivo_estrategias (obj_estrategia, fk_pmp)
SELECT N'Fortalecer la autonomia economica y social de las mujeres', @pmp_igualdad_id
WHERE @pmp_igualdad_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.objetivo_estrategias
	WHERE obj_estrategia = N'Fortalecer la autonomia economica y social de las mujeres'
	AND fk_pmp = @pmp_igualdad_id
);

INSERT INTO dbo.objetivo_estrategias (obj_estrategia, fk_pmp)
SELECT N'Consolidar la prevencion y atencion integral de la violencia de genero', @pmp_violencia_id
WHERE @pmp_violencia_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.objetivo_estrategias
	WHERE obj_estrategia = N'Consolidar la prevencion y atencion integral de la violencia de genero'
	AND fk_pmp = @pmp_violencia_id
);

INSERT INTO dbo.objetivo_estrategias (obj_estrategia, fk_pmp)
SELECT N'Disminuir factores de riesgo asociados al embarazo adolescente', @pmp_embarazo_id
WHERE @pmp_embarazo_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.objetivo_estrategias
	WHERE obj_estrategia = N'Disminuir factores de riesgo asociados al embarazo adolescente'
	AND fk_pmp = @pmp_embarazo_id
);

DECLARE @obj_igualdad_id INT = (
	SELECT TOP 1 id_obj FROM dbo.objetivo_estrategias
	WHERE obj_estrategia = N'Fortalecer la autonomia economica y social de las mujeres'
	AND fk_pmp = @pmp_igualdad_id
);

DECLARE @obj_violencia_id INT = (
	SELECT TOP 1 id_obj FROM dbo.objetivo_estrategias
	WHERE obj_estrategia = N'Consolidar la prevencion y atencion integral de la violencia de genero'
	AND fk_pmp = @pmp_violencia_id
);

DECLARE @obj_embarazo_id INT = (
	SELECT TOP 1 id_obj FROM dbo.objetivo_estrategias
	WHERE obj_estrategia = N'Disminuir factores de riesgo asociados al embarazo adolescente'
	AND fk_pmp = @pmp_embarazo_id
);

INSERT INTO dbo.estrategia_pmp (estrategia_pmp, fk_obj)
SELECT N'Vinculacion con programas de empleo y emprendimiento para mujeres', @obj_igualdad_id
WHERE @obj_igualdad_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.estrategia_pmp
	WHERE estrategia_pmp = N'Vinculacion con programas de empleo y emprendimiento para mujeres'
	AND fk_obj = @obj_igualdad_id
);

INSERT INTO dbo.estrategia_pmp (estrategia_pmp, fk_obj)
SELECT N'Atencion territorial e interinstitucional para prevenir violencias', @obj_violencia_id
WHERE @obj_violencia_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.estrategia_pmp
	WHERE estrategia_pmp = N'Atencion territorial e interinstitucional para prevenir violencias'
	AND fk_obj = @obj_violencia_id
);

INSERT INTO dbo.estrategia_pmp (estrategia_pmp, fk_obj)
SELECT N'Prevencion comunitaria del embarazo adolescente con enfoque de derechos', @obj_embarazo_id
WHERE @obj_embarazo_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.estrategia_pmp
	WHERE estrategia_pmp = N'Prevencion comunitaria del embarazo adolescente con enfoque de derechos'
	AND fk_obj = @obj_embarazo_id
);

DECLARE @estrategia_igualdad_id INT = (
	SELECT TOP 1 id_estrategia FROM dbo.estrategia_pmp
	WHERE estrategia_pmp = N'Vinculacion con programas de empleo y emprendimiento para mujeres'
	AND fk_obj = @obj_igualdad_id
);

DECLARE @estrategia_violencia_id INT = (
	SELECT TOP 1 id_estrategia FROM dbo.estrategia_pmp
	WHERE estrategia_pmp = N'Atencion territorial e interinstitucional para prevenir violencias'
	AND fk_obj = @obj_violencia_id
);

DECLARE @estrategia_embarazo_id INT = (
	SELECT TOP 1 id_estrategia FROM dbo.estrategia_pmp
	WHERE estrategia_pmp = N'Prevencion comunitaria del embarazo adolescente con enfoque de derechos'
	AND fk_obj = @obj_embarazo_id
);

INSERT INTO dbo.linea_accion_pmp (linea_pmp, fk_estrategia_pmp)
SELECT N'LA-PMP-01: Ferias de empleo y acompanamiento a emprendimientos liderados por mujeres', @estrategia_igualdad_id
WHERE @estrategia_igualdad_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.linea_accion_pmp
	WHERE linea_pmp = N'LA-PMP-01: Ferias de empleo y acompanamiento a emprendimientos liderados por mujeres'
	AND fk_estrategia_pmp = @estrategia_igualdad_id
);

INSERT INTO dbo.linea_accion_pmp (linea_pmp, fk_estrategia_pmp)
SELECT N'LA-PMP-02: Brigadas interinstitucionales para deteccion y canalizacion de casos', @estrategia_violencia_id
WHERE @estrategia_violencia_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.linea_accion_pmp
	WHERE linea_pmp = N'LA-PMP-02: Brigadas interinstitucionales para deteccion y canalizacion de casos'
	AND fk_estrategia_pmp = @estrategia_violencia_id
);

INSERT INTO dbo.linea_accion_pmp (linea_pmp, fk_estrategia_pmp)
SELECT N'LA-PMP-03: Talleres preventivos en escuelas y comunidades', @estrategia_embarazo_id
WHERE @estrategia_embarazo_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.linea_accion_pmp
	WHERE linea_pmp = N'LA-PMP-03: Talleres preventivos en escuelas y comunidades'
	AND fk_estrategia_pmp = @estrategia_embarazo_id
);

INSERT INTO dbo.eje (eje)
SELECT N'Eje 2'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.eje WHERE eje = N'Eje 2'
);

INSERT INTO dbo.eje (eje)
SELECT N'Eje 3'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.eje WHERE eje = N'Eje 3'
);

DECLARE @eje2_id INT = (
	SELECT TOP 1 id FROM dbo.eje WHERE eje = N'Eje 2'
);

DECLARE @eje3_id INT = (
	SELECT TOP 1 id FROM dbo.eje WHERE eje = N'Eje 3'
);

INSERT INTO dbo.politica_publica (politica_publica, fk_eje)
SELECT N'Politica 2.3', @eje2_id
WHERE @eje2_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.politica_publica WHERE politica_publica = N'Politica 2.3' AND fk_eje = @eje2_id
);

INSERT INTO dbo.politica_publica (politica_publica, fk_eje)
SELECT N'Politica 3.2', @eje3_id
WHERE @eje3_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.politica_publica WHERE politica_publica = N'Politica 3.2' AND fk_eje = @eje3_id
);

DECLARE @politica23_id INT = (
	SELECT TOP 1 id FROM dbo.politica_publica WHERE politica_publica = N'Politica 2.3' AND fk_eje = @eje2_id
);

DECLARE @politica32_id INT = (
	SELECT TOP 1 id FROM dbo.politica_publica WHERE politica_publica = N'Politica 3.2' AND fk_eje = @eje3_id
);

INSERT INTO dbo.objetivo_ped (objetivo, fk_politica)
SELECT N'Objetivo 2.3.1', @politica23_id
WHERE @politica23_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.objetivo_ped WHERE objetivo = N'Objetivo 2.3.1' AND fk_politica = @politica23_id
);

INSERT INTO dbo.objetivo_ped (objetivo, fk_politica)
SELECT N'Objetivo 3.2.2', @politica32_id
WHERE @politica32_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.objetivo_ped WHERE objetivo = N'Objetivo 3.2.2' AND fk_politica = @politica32_id
);

DECLARE @objetivo231_id INT = (
	SELECT TOP 1 id FROM dbo.objetivo_ped WHERE objetivo = N'Objetivo 2.3.1' AND fk_politica = @politica23_id
);

DECLARE @objetivo322_id INT = (
	SELECT TOP 1 id FROM dbo.objetivo_ped WHERE objetivo = N'Objetivo 3.2.2' AND fk_politica = @politica32_id
);

INSERT INTO dbo.estrategia_ped (estrategia, fk_objetivo)
SELECT N'Estrategia 2.3.1.a', @objetivo231_id
WHERE @objetivo231_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.estrategia_ped WHERE estrategia = N'Estrategia 2.3.1.a' AND fk_objetivo = @objetivo231_id
);

INSERT INTO dbo.estrategia_ped (estrategia, fk_objetivo)
SELECT N'Estrategia 3.2.2.b', @objetivo322_id
WHERE @objetivo322_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.estrategia_ped WHERE estrategia = N'Estrategia 3.2.2.b' AND fk_objetivo = @objetivo322_id
);

DECLARE @estrategia231a_id INT = (
	SELECT TOP 1 id FROM dbo.estrategia_ped WHERE estrategia = N'Estrategia 2.3.1.a' AND fk_objetivo = @objetivo231_id
);

DECLARE @estrategia322b_id INT = (
	SELECT TOP 1 id FROM dbo.estrategia_ped WHERE estrategia = N'Estrategia 3.2.2.b' AND fk_objetivo = @objetivo322_id
);

INSERT INTO dbo.lineas_accion_ped (linea_accion, fk_estrategia)
SELECT N'Linea 2.3.1.a.1', @estrategia231a_id
WHERE @estrategia231a_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.lineas_accion_ped WHERE linea_accion = N'Linea 2.3.1.a.1' AND fk_estrategia = @estrategia231a_id
);

INSERT INTO dbo.lineas_accion_ped (linea_accion, fk_estrategia)
SELECT N'Linea 3.2.2.b.2', @estrategia322b_id
WHERE @estrategia322b_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.lineas_accion_ped WHERE linea_accion = N'Linea 3.2.2.b.2' AND fk_estrategia = @estrategia322b_id
);

INSERT INTO dbo.alineacion_ped (eje, politica, objetivo, estrategia, linea, fk_id_programa)
SELECT N'Eje 2', N'Politica 2.3', N'Objetivo 2.3.1', N'Estrategia 2.3.1.a', N'Linea 2.3.1.a.1', @programa_prevencion_id
WHERE @programa_prevencion_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.alineacion_ped
	WHERE fk_id_programa = @programa_prevencion_id
	AND eje = N'Eje 2'
	AND politica = N'Politica 2.3'
	AND objetivo = N'Objetivo 2.3.1'
	AND estrategia = N'Estrategia 2.3.1.a'
	AND linea = N'Linea 2.3.1.a.1'
);

INSERT INTO dbo.alineacion_ped (eje, politica, objetivo, estrategia, linea, fk_id_programa)
SELECT N'Eje 3', N'Politica 3.2', N'Objetivo 3.2.2', N'Estrategia 3.2.2.b', N'Linea 3.2.2.b.2', @programa_autonomia_id
WHERE @programa_autonomia_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.alineacion_ped
	WHERE fk_id_programa = @programa_autonomia_id
	AND eje = N'Eje 3'
	AND politica = N'Politica 3.2'
	AND objetivo = N'Objetivo 3.2.2'
	AND estrategia = N'Estrategia 3.2.2.b'
	AND linea = N'Linea 3.2.2.b.2'
);



INSERT INTO dbo.entregable (
	nombre_entregable, periodicidad, unidad_medida, meta, municipalizable, compromiso, ods,
	actividad_sigo, entregable_sigo, avg, monto_total, porcentaje_ubp_total, fk_id_programa
)
SELECT
	N'Talleres de prevencion impartidos', N'Mensual', N'Taller', 48.00, N'Si',
	N'Atender colonias con mayor incidencia reportada.', N'ODS 5',
	N'Implementar talleres comunitarios', N'Reportes de taller', N'AVG-PREV-01',
	1200000.00, 35.50, @programa_prevencion_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.entregable WHERE nombre_entregable = N'Talleres de prevencion impartidos' AND fk_id_programa = @programa_prevencion_id
);

INSERT INTO dbo.entregable (
	nombre_entregable, periodicidad, unidad_medida, meta, municipalizable, compromiso, ods,
	actividad_sigo, entregable_sigo, avg, monto_total, porcentaje_ubp_total, fk_id_programa
)
SELECT
	N'Mujeres capacitadas para empleo', N'Trimestral', N'Persona', 600.00, N'Si',
	N'Priorizar mujeres jefas de hogar y en situacion de violencia.', N'ODS 8',
	N'Capacitacion tecnico-laboral', N'Listas de asistencia y constancias', N'AVG-AUT-02',
	1800000.00, 42.00, @programa_autonomia_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.entregable WHERE nombre_entregable = N'Mujeres capacitadas para empleo' AND fk_id_programa = @programa_autonomia_id
);

DECLARE @entregable_talleres_id INT = (
	SELECT TOP 1 id_entregable FROM dbo.entregable
	WHERE nombre_entregable = N'Talleres de prevencion impartidos' AND fk_id_programa = @programa_prevencion_id
);

DECLARE @entregable_capacitacion_id INT = (
	SELECT TOP 1 id_entregable FROM dbo.entregable
	WHERE nombre_entregable = N'Mujeres capacitadas para empleo' AND fk_id_programa = @programa_autonomia_id
);

INSERT INTO dbo.p_especial (programa, objetivo, estrategia, linea_accion, fk_id_entregable)
SELECT
	N'Programa Especial para Igualdad de Genero, Oportunidades y no Discriminacion',
	N'Fortalecer prevencion comunitaria con enfoque de genero',
	N'Coordinacion interinstitucional para atencion territorial',
	N'Linea PEIGOND-01',
	@entregable_talleres_id
WHERE @entregable_talleres_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.p_especial
	WHERE fk_id_entregable = @entregable_talleres_id
	AND programa = N'Programa Especial para Igualdad de Genero, Oportunidades y no Discriminacion'
	AND objetivo = N'Fortalecer prevencion comunitaria con enfoque de genero'
	AND estrategia = N'Coordinacion interinstitucional para atencion territorial'
	AND linea_accion = N'Linea PEIGOND-01'
);

INSERT INTO dbo.p_especial (programa, objetivo, estrategia, linea_accion, fk_id_entregable)
SELECT
	N'Programa Especial para Prevencion del Embarazo en Adolescentes',
	N'Incrementar autonomia economica y social de mujeres jovenes',
	N'Capacitacion y vinculacion laboral con enfoque comunitario',
	N'Linea PEPEA-02',
	@entregable_capacitacion_id
WHERE @entregable_capacitacion_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.p_especial
	WHERE fk_id_entregable = @entregable_capacitacion_id
	AND programa = N'Programa Especial para Prevencion del Embarazo en Adolescentes'
	AND objetivo = N'Incrementar autonomia economica y social de mujeres jovenes'
	AND estrategia = N'Capacitacion y vinculacion laboral con enfoque comunitario'
	AND linea_accion = N'Linea PEPEA-02'
);

INSERT INTO dbo.finanzas (fuente, monto, porcentaje_ubp, fk_id_entregable)
SELECT
	N'FAIS',
	450000.00,
	0.00,
	@entregable_talleres_id
WHERE @entregable_talleres_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.finanzas
	WHERE fk_id_entregable = @entregable_talleres_id
	AND fuente = N'FAIS'
	AND monto = 450000.00
	AND porcentaje_ubp = 0.00
);

INSERT INTO dbo.finanzas (fuente, monto, porcentaje_ubp, fk_id_entregable)
SELECT
	N'No Aplica',
	130000.00,
	18.50,
	@entregable_capacitacion_id
WHERE @entregable_capacitacion_id IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.finanzas
	WHERE fk_id_entregable = @entregable_capacitacion_id
	AND fuente = N'No Aplica'
	AND monto = 130000.00
	AND porcentaje_ubp = 18.50
);

INSERT INTO dbo.avance (
	mes, municipio, avance_entregable, monto, proyecto, descripcion, institucion, avance_finalizado,
	fk_id_entregable, poblacion, m_t1, m_d1, m_i1, h_t1, h_d1, h_i1, m_ts, m_ds, m_is, h_ts, h_ds, h_is,
	m_10, h_10, m_15, h_15, m_ser, h_ser, m_padres, h_padres,
	ms_10, hs_10, ms_15, hs_15, ms_ser, hs_ser, ms_padres, hs_padres
)
SELECT
	N'Enero', N'Merida',
	10,
	95000.00, N'Prevencion Territorial 2026',
	N'Se fortalecieron rutas de canalizacion con DIF y seguridad publica.',
	N'SEMUJERES', 1,
	@entregable_talleres_id, N'Adolescentes y mujeres adultas',
	120, 40, 10, 30, 12, 3, 60, 22, 8, 18, 9, 2,
	22, 10, 14, 6, 8, 4, 5, 2,
	11, 5, 7, 3, 4, 2, 2, 1
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.avance
	WHERE fk_id_entregable = @entregable_talleres_id AND mes = N'Enero' AND municipio = N'Merida'
);

INSERT INTO dbo.avance (
	mes, municipio, avance_entregable, monto, proyecto, descripcion, institucion, avance_finalizado,
	fk_id_entregable, poblacion, m_t1, m_d1, m_i1, h_t1, h_d1, h_i1, m_ts, m_ds, m_is, h_ts, h_ds, h_is,
	m_10, h_10, m_15, h_15, m_ser, h_ser, m_padres, h_padres,
	ms_10, hs_10, ms_15, hs_15, ms_ser, hs_ser, ms_padres, hs_padres
)
SELECT
	N'Febrero', N'Valladolid',
	10,
	130000.00, N'Empleo con Igualdad 2026',
	N'Incluye modulo de habilidades digitales y vinculacion con empresas locales.',
	N'SEMUJERES', 0,
	@entregable_capacitacion_id, N'Mujeres de 18 a 45 anos',
	180, 70, 18, 25, 10, 2, 85, 36, 9, 12, 5, 1,
	35, 8, 22, 6, 10, 3, 6, 1,
	17, 4, 10, 3, 5, 2, 3, 1
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.avance
	WHERE fk_id_entregable = @entregable_capacitacion_id AND mes = N'Febrero' AND municipio = N'Valladolid'
);

INSERT INTO dbo.informe (
	trimestre, periodo, accion, personas, municipios, objetivo, descripcion, fk_id_entregable, informe_finalizado
)
SELECT
	N'Primer Trimestre', N'2026',
	N'Implementacion de talleres y seguimiento institucional.',
	N'210', N'Merida, Kanasin',
	N'Reducir factores de riesgo en violencia de genero.',
	N'Se instalaron mesas de trabajo y rutas de atencion interinstitucional.',
	@entregable_talleres_id, 1
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.informe
	WHERE fk_id_entregable = @entregable_talleres_id AND trimestre = N'Primer Trimestre' AND periodo = N'2026'
);

INSERT INTO dbo.informe (
	trimestre, periodo, accion, personas, municipios, objetivo, descripcion, fk_id_entregable, informe_finalizado
)
SELECT
	N'Primer Trimestre', N'2026',
	N'Capacitacion, certificacion y vinculacion laboral.',
	N'160', N'Valladolid, Tizimin',
	N'Incrementar autonomia economica de mujeres beneficiarias.',
	N'Se coordinaron ferias de empleo y seguimiento post-capacitacion.',
	@entregable_capacitacion_id, 0
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.informe
	WHERE fk_id_entregable = @entregable_capacitacion_id AND trimestre = N'Primer Trimestre' AND periodo = N'2026'
);

INSERT INTO dbo.pass_recovery (correo, token)
SELECT N'admin@semujeres.local', N'DEMO-TOKEN-ADMIN-2026'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.pass_recovery WHERE correo = N'admin@semujeres.local' AND token = N'DEMO-TOKEN-ADMIN-2026'
);

INSERT INTO dbo.programa_presupuestario (pp)
SELECT N'E013 - Prevencion y Atencion a la Violencia'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.programa_presupuestario WHERE pp = N'E013 - Prevencion y Atencion a la Violencia'
);

INSERT INTO dbo.programa_presupuestario (pp)
SELECT N'E021 - Autonomia Economica de las Mujeres'
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.programa_presupuestario WHERE pp = N'E021 - Autonomia Economica de las Mujeres'
);

DECLARE @pp_prevencion_id INT = (
	SELECT TOP 1 id_pp FROM dbo.programa_presupuestario WHERE pp = N'E013 - Prevencion y Atencion a la Violencia'
);

INSERT INTO dbo.indicador (numero, definicion, formula, fk_pp)
SELECT
	N'I-01',
	N'Proporcion de mujeres atendidas que concluyen su plan de seguimiento.',
	N'(B/C)*100',
	@pp_prevencion_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.indicador WHERE numero = N'I-01' AND fk_pp = @pp_prevencion_id
);

DECLARE @indicador_i01_id INT = (
	SELECT TOP 1 id_indicador FROM dbo.indicador WHERE numero = N'I-01' AND fk_pp = @pp_prevencion_id
);

INSERT INTO dbo.indicadores (
	responsable, corresponsable, indicador, [año],
	en_a, en_b, en_c,
	feb_a, feb_b, feb_c,
	mar_a, mar_b, mar_c,
	ab_a, ab_b, ab_c,
	may_a, may_b, may_c,
	jun_a, jun_b, jun_c,
	jul_a, jul_b, jul_c,
	ago_a, ago_b, ago_c,
	sep_a, sep_b, sep_c,
	oct_a, oct_b, oct_c,
	nov_a, nov_b, nov_c,
	dic_a, dic_b, dic_c,
	anual_a, anual_b, anual_c,
	fk_user
)
SELECT
	N'Fernanda Canche', N'Equipo Territorial', @indicador_i01_id, 2026,
	83.33, 35.00, 42.00,
	84.44, 38.00, 45.00,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	NULL, NULL, NULL,
	83.88, 73.00, 87.00,
	@analista_user_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.indicadores
	WHERE fk_user = @analista_user_id AND indicador = @indicador_i01_id AND [año] = 2026
);

INSERT INTO dbo.var_indicador (variable, nombre, fk_indicador)
SELECT N'A', N'Mujeres con seguimiento concluido', @indicador_i01_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.var_indicador WHERE variable = N'A' AND fk_indicador = @indicador_i01_id
);

INSERT INTO dbo.var_indicador (variable, nombre, fk_indicador)
SELECT N'B', N'Total de mujeres atendidas', @indicador_i01_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.var_indicador WHERE variable = N'B' AND fk_indicador = @indicador_i01_id
);

INSERT INTO dbo.base_meta_indicador ([año], linea_base, meta, fk_indicador)
SELECT 2025, 32.50, 40.00, @indicador_i01_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.base_meta_indicador WHERE [año] = 2025 AND fk_indicador = @indicador_i01_id
);

INSERT INTO dbo.base_meta_indicador ([año], linea_base, meta, fk_indicador)
SELECT 2026, 40.00, 50.00, @indicador_i01_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.base_meta_indicador WHERE [año] = 2026 AND fk_indicador = @indicador_i01_id
);

DECLARE @indicador_i01_numero NVARCHAR(60) = (
	SELECT TOP 1 numero FROM dbo.indicador WHERE id_indicador = @indicador_i01_id
);

INSERT INTO dbo.ficha (numero_indicador, [año], ficha)
SELECT @indicador_i01_numero, 2026, N'ficha_i01_2026.pdf'
WHERE @indicador_i01_numero IS NOT NULL
AND NOT EXISTS (
	SELECT 1 FROM dbo.ficha
	WHERE numero_indicador = @indicador_i01_numero
	AND [año] = 2026
	AND ficha = N'ficha_i01_2026.pdf'
);

DECLARE @indicadores_main_id INT = (
	SELECT TOP 1 id_indicadores
	FROM dbo.indicadores
	WHERE fk_user = @analista_user_id AND indicador = @indicador_i01_id AND [año] = 2026
);

INSERT INTO dbo.valor_indicadores (mes, [año], valora, valorb, valorc, fk_indicadores)
SELECT N'Enero', 2026, 120.00, 300.00, 40.00, @indicadores_main_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.valor_indicadores WHERE fk_indicadores = @indicadores_main_id AND mes = N'Enero' AND [año] = 2026
);

INSERT INTO dbo.valor_indicadores (mes, [año], valora, valorb, valorc, fk_indicadores)
SELECT N'Febrero', 2026, 140.00, 320.00, 43.75, @indicadores_main_id
WHERE NOT EXISTS (
	SELECT 1 FROM dbo.valor_indicadores WHERE fk_indicadores = @indicadores_main_id AND mes = N'Febrero' AND [año] = 2026
);


--Usuario: admin@semujeres.local
--Password: Admin1234