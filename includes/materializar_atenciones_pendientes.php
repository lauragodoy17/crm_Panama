<?php
/**
 * /includes/materializar_atenciones_pendientes.php
 * Distribución en años de una atención (ver php/solicitud_recurso.php): al distribuir una
 * solicitud en varios años, los años siguientes al actual NO crean un período académico nuevo —
 * eso habilitaría presupuestos/adopciones/tasas/etc. para un año que todavía no existe de verdad
 * en el negocio. En vez de eso, cada cuota futura queda "pendiente" en
 * atenciones_pendientes_distribucion (sin tocar la tabla periodos para nada).
 *
 * La primera vez que alguien abre el reporte o la pestaña de atenciones de un período que
 * coincide en año con una pendiente (es decir: el período YA existe, por sus propias
 * razones normales del negocio, no por esta función), materializar_atenciones_pendientes() la
 * convierte en una solicitud real — igual que si el promotor la hubiera creado ese día — y la
 * marca para no volver a procesarla.
 *
 * Panamá: la tabla periodos no tiene id_calendario (un único calendario por año), así que la
 * coincidencia es solo por año. La columna id_calendario de la tabla de pendientes se guarda en 0.
 */

/**
 * Panamá: el módulo de atenciones estuvo oculto y la base nunca recibió las columnas/tablas que
 * usa (en InkPulse ya existen). Se crean aquí de forma idempotente: solo agrega lo que falte,
 * una vez por request. Se llama desde el tab del colegio, la lista, la vista y el Excel.
 */
function asegurar_esquema_atenciones(PDO $bdd) {
    static $hecho = false;
    if ($hecho) return;
    $hecho = true;

    try {
        $bdd->exec("CREATE TABLE IF NOT EXISTS trazabilidad_entregas (
            id             INT AUTO_INCREMENT PRIMARY KEY,
            id_solicitud   INT NOT NULL,
            id_recurso     INT NOT NULL,
            tipo           VARCHAR(20) NOT NULL,
            valor          DECIMAL(15,2) NOT NULL DEFAULT 0,
            fecha          DATE,
            fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
            id_usuario     INT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } catch (\Exception $e) {}

    $columnas = [
        'archivo'                  => "ALTER TABLE solicitudes_recursos ADD COLUMN archivo LONGTEXT NULL",
        'contab'                   => "ALTER TABLE solicitudes_recursos ADD COLUMN contab INT NOT NULL DEFAULT 0",
        'distribucion_grupo_id'    => "ALTER TABLE solicitudes_recursos ADD COLUMN distribucion_grupo_id INT NULL AFTER conse",
        'distribucion_anio_num'    => "ALTER TABLE solicitudes_recursos ADD COLUMN distribucion_anio_num INT NULL AFTER distribucion_grupo_id",
        'distribucion_total_anios' => "ALTER TABLE solicitudes_recursos ADD COLUMN distribucion_total_anios INT NULL AFTER distribucion_anio_num",
        'legal_cerrado'            => "ALTER TABLE solicitudes_recursos ADD COLUMN legal_cerrado TINYINT(1) NOT NULL DEFAULT 0",
    ];
    try {
        $existentes = $bdd->query("SELECT column_name FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = 'solicitudes_recursos'")->fetchAll(PDO::FETCH_COLUMN);
        $existentes = array_map('strtolower', $existentes);
        foreach ($columnas as $col => $ddl) {
            if (!in_array($col, $existentes, true)) {
                try { $bdd->exec($ddl); } catch (\Exception $e) {}
            }
        }
    } catch (\Exception $e) {}

    crear_tabla_pendientes_distribucion($bdd);
    asegurar_montos_con_centavos($bdd);
}

/**
 * Atenciones se maneja en dólares (USD), así que los montos necesitan centavos.
 * Las columnas se crearon originalmente como DECIMAL(x,0); aquí se amplían a 2 decimales
 * (solo si todavía tienen escala 0, para no ejecutar el ALTER en cada petición).
 */
function asegurar_montos_con_centavos(PDO $bdd) {
    $columnas = [
        ['recursos_solicitados',              'presupuesto', 'DECIMAL(12,2) NOT NULL'],
        ['recursos_solicitados',              'valor_e',     'DECIMAL(12,2) NOT NULL'],
        ['recursos_solicitados',              'legaliza',    'DECIMAL(12,2) NOT NULL'],
        ['solicitudes_recursos',              'reintegro',   'DECIMAL(12,2) NOT NULL'],
        ['trazabilidad_entregas',             'valor',       'DECIMAL(15,2) NOT NULL DEFAULT 0'],
        ['atenciones_pendientes_distribucion','monto',       'DECIMAL(12,2) NOT NULL DEFAULT 0'],
    ];
    try {
        $stmt = $bdd->prepare("SELECT data_type, numeric_scale, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");
        foreach ($columnas as [$tabla, $col, $def]) {
            $stmt->execute([$tabla, $col]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$info || strtolower($info['data_type']) !== 'decimal' || (int)$info['numeric_scale'] >= 2) continue;
            try { $bdd->exec("ALTER TABLE `$tabla` MODIFY `$col` $def"); } catch (\Exception $e) {}
        }
    } catch (\Exception $e) {}
}

function crear_tabla_pendientes_distribucion(PDO $bdd) {
    try {
        $bdd->exec("CREATE TABLE IF NOT EXISTS atenciones_pendientes_distribucion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_solicitud_origen INT NOT NULL,
            id_recurso_origen INT NULL,
            id_colegio INT NOT NULL,
            id_calendario INT NOT NULL DEFAULT 0,
            anio_objetivo INT NOT NULL,
            id_usuario INT NOT NULL,
            solicitante INT NOT NULL,
            fecha_entrega DATE NOT NULL,
            reintegro VARCHAR(255) NULL,
            recurso TEXT NOT NULL,
            tipo INT NOT NULL,
            categoria INT NOT NULL,
            monto DECIMAL(12,2) NOT NULL DEFAULT 0,
            distribucion_anio_num INT NOT NULL,
            distribucion_total_anios INT NOT NULL,
            materializado TINYINT(1) NOT NULL DEFAULT 0,
            id_solicitud_generada INT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY idx_pendientes (materializado, anio_objetivo)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } catch (\Exception $e) {}
}

/**
 * Año calendario de la etiqueta de un período: "2026" -> 2026, "2026B" -> 2026.
 */
function anio_de_periodo($etiqueta_periodo) {
    return (int) substr((string)$etiqueta_periodo, 0, 4);
}

/**
 * Materializa (si hay algo pendiente) las cuotas de distribución en años que le correspondan al
 * período $id_periodo. Segura para llamar en cada carga del reporte/tab de atenciones: si no hay
 * filas pendientes para ese año, no hace ningún INSERT.
 */
function materializar_atenciones_pendientes(PDO $bdd, $id_periodo) {
    asegurar_esquema_atenciones($bdd);

    $id_periodo = (int)$id_periodo;
    if ($id_periodo <= 0) return;

    $req_p = $bdd->prepare("SELECT periodo FROM periodos WHERE id = ?");
    $req_p->execute([$id_periodo]);
    $periodo_row = $req_p->fetch(PDO::FETCH_ASSOC);
    if (!$periodo_row) return;

    $anio = anio_de_periodo($periodo_row['periodo']);

    $req_pend = $bdd->prepare(
        "SELECT DISTINCT id_solicitud_origen FROM atenciones_pendientes_distribucion
         WHERE materializado = 0 AND anio_objetivo = ?"
    );
    $req_pend->execute([$anio]);
    $origenes = $req_pend->fetchAll(PDO::FETCH_COLUMN);
    if (empty($origenes)) return;

    $stmt_fragmentos = $bdd->prepare(
        "SELECT * FROM atenciones_pendientes_distribucion
         WHERE materializado = 0 AND anio_objetivo = ? AND id_solicitud_origen = ?"
    );
    $stmt_conse    = $bdd->prepare("SELECT MAX(conse) as conse FROM solicitudes_recursos WHERE id_periodo = ?");
    $stmt_sol_ins  = $bdd->prepare(
        "INSERT INTO solicitudes_recursos
            (id_periodo, usuario, id_colegio, estado, solicitante, fecha_entrega, reintegro, conse, distribucion_grupo_id, distribucion_anio_num, distribucion_total_anios)
         VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt_rec_ins  = $bdd->prepare("INSERT INTO recursos_solicitados (id_solicitud, tipo, categoria, recurso, presupuesto) VALUES (?, ?, ?, ?, ?)");
    $stmt_marcar   = $bdd->prepare("UPDATE atenciones_pendientes_distribucion SET materializado = 1, id_solicitud_generada = ? WHERE id = ?");
    $stmt_area_sel = $bdd->prepare("SELECT materia, preescolar, primaria, bachillerato FROM areas_recursos WHERE id_solicitud = ?");
    $stmt_area_ins = $bdd->prepare("INSERT INTO areas_recursos (id_solicitud, materia, preescolar, primaria, bachillerato) VALUES (?, ?, ?, ?, ?)");
    $stmt_notif    = $bdd->prepare("INSERT INTO notificaciones (id_periodo,id_colegio,id_usuario,id_solicitud,id_tipo_notifi,usuario_respuesta,visible) VALUES (?,?,0,?,7,0,1)");

    foreach ($origenes as $id_origen) {
        $stmt_fragmentos->execute([$anio, $id_origen]);
        $fragmentos = $stmt_fragmentos->fetchAll(PDO::FETCH_ASSOC);
        if (empty($fragmentos)) continue;

        $f0 = $fragmentos[0];

        $stmt_conse->execute([$id_periodo]);
        $conse = ((int)$stmt_conse->fetchColumn()) + 1;

        $stmt_sol_ins->execute([
            $id_periodo, $f0['id_usuario'], $f0['id_colegio'], $f0['solicitante'],
            $f0['fecha_entrega'], $f0['reintegro'], $conse,
            $id_origen, $f0['distribucion_anio_num'], $f0['distribucion_total_anios'],
        ]);
        $id_solicitud_nueva = (int)$bdd->lastInsertId();

        $stmt_area_sel->execute([$id_origen]);
        foreach ($stmt_area_sel->fetchAll(PDO::FETCH_ASSOC) as $a) {
            $stmt_area_ins->execute([$id_solicitud_nueva, $a['materia'], $a['preescolar'], $a['primaria'], $a['bachillerato']]);
        }

        foreach ($fragmentos as $frag) {
            $stmt_rec_ins->execute([$id_solicitud_nueva, $frag['tipo'], $frag['categoria'], $frag['recurso'], $frag['monto']]);
            $stmt_marcar->execute([$id_solicitud_nueva, $frag['id']]);
        }

        // Notificación en la campanita del CRM, igual que una solicitud nueva normal. No se
        // reenvía el correo SMTP acá a propósito: esta función corre dentro de la carga de un
        // reporte/tab (potencialmente muchas veces al día), y una llamada SMTP síncrona ahí
        // arriesga hacer lenta o fallar la vista solo por una materialización de fondo.
        try { $stmt_notif->execute([$id_periodo, $f0['id_colegio'], $id_solicitud_nueva]); } catch (\Exception $e) {}
    }
}
