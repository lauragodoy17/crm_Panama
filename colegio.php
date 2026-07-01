<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Colegio</title>

    <!-- Site favicon -->
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="vendors/images/apple-touch-icon.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="vendors/images/favicon-32x32.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="vendors/images/favicon-16x16.png"
    />

    <!-- Mobile Specific Metas -->
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1"
    />

    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <!-- CSS -->
    <link
      rel="stylesheet"
      type="text/css"
      href="src/plugins/datatables/css/dataTables.bootstrap4.min.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="src/plugins/datatables/css/responsive.bootstrap4.min.css"
    />
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link
      rel="stylesheet"
      type="text/css"
      href="vendors/styles/icon-font.min.css"
    />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

    <style>
      input[type=number] { -moz-appearance:textfield; }
      input[type=number]::-webkit-inner-spin-button,
      input[type=number]::-webkit-outer-spin-button { -webkit-appearance:none; margin:0; }

      /* ── Encabezado del colegio ── */
      .fc-header { background:#fff; border:1px solid #e9ecef; border-radius:10px; padding:18px 22px; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
      .fc-avatar  { width:44px; height:44px; background:#eef0ff; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:15px; color:#4361ee; flex-shrink:0; }
      .fc-nombre  { font-size:17px; font-weight:700; color:#111827; margin:0; }
      .fc-badge   { background:#d1fae5; color:#059669; font-size:11px; font-weight:600; padding:2px 9px; border-radius:20px; margin-left:8px; }

      /* ── Chips de info ── */
      .fc-chips { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
      .fc-chip  { background:#fff; border:1px solid #e9ecef; border-radius:8px; padding:9px 14px; display:flex; align-items:center; gap:8px; flex:1; min-width:140px; }
      .fc-chip-ico { width:30px; height:30px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
      .fc-chip-ico.blue   { background:#eef0ff; color:#4361ee; }
      .fc-chip-ico.green  { background:#d1fae5; color:#059669; }
      .fc-chip-ico.orange { background:#fff7ed; color:#ea580c; }
      .fc-chip-ico.purple { background:#f5f3ff; color:#7c3aed; }
      .fc-chip-lbl { font-size:11px; color:#9ca3af; font-weight:500; line-height:1; }
      .fc-chip-val { font-size:13px; color:#111827; font-weight:600; line-height:1.4; }

      /* ── Chips más grandes ── */
      .fc-chip     { padding:14px 20px; }
      .fc-chip-ico { width:38px; height:38px; font-size:17px; }
      .fc-chip-lbl { font-size:12px; }
      .fc-chip-val { font-size:15px; font-weight:700; }

      /* ── Modo vista / edición ──
         pointer-events:none en el contenedor de campos bloquea todo (incluido Select2).
         El bloque de acciones vive fuera del contenedor, siempre clicable. */
      #fc-campos.fc-readonly { pointer-events:none; user-select:none; }
      #fc-campos.fc-readonly .form-control  { background:#f8f9fa; border-color:#f0f0f0; color:#374151; }
      #fc-campos.fc-readonly .custom-select { background:#f8f9fa; border-color:#f0f0f0; color:#374151; }
      /* Select2: gris visual en modo readonly */
      #fc-campos.fc-readonly .select2-container--default .select2-selection--single {
        background-color:#f8f9fa; border-color:#f0f0f0;
      }
      #fc-campos.fc-readonly .select2-container--default .select2-selection--single .select2-selection__rendered {
        color:#374151;
      }

    </style>

    
  </head>
  <body>
    
    <?php include("template/nav_side.php"); ?>
    <div class="main-container">
      <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
          <nav aria-label="breadcrumb" role="navigation" class="mb-3">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="ver_colegios.php">Zonificación</a></li>
              <li class="breadcrumb-item active">Ficha de colegio</li>
            </ol>
          </nav>

          <?php
            if (isset($_GET["codigo"])) { $codigo_col = $_GET["codigo"]; }
            else { $codigo_col = $_POST["codigo"]; }

            $sql = "SELECT * FROM colegios WHERE codigo='".$codigo_col."'";
            $req = $bdd->prepare($sql);
            $req->execute();
            $colegio = $req->fetch();

            $sql_promo = "SELECT u.id, CONCAT(u.nombres,' ',u.apellidos) as promotor, u.tipo, z.zona FROM usuarios u JOIN zonas z ON u.cod_zona=z.codigo WHERE cod_zona='".$colegio["cod_zona"]."'";
            $req_promo = $bdd->prepare($sql_promo);
            $req_promo->execute();
            $promotor = $req_promo->fetch();

            if (isset($_POST["periodo"])) { $periodo = $_POST["periodo"]; }
            else { $periodo = $_GET["periodo"]; }

            // Empresa / zona
            if ($promotor['tipo']==3 || $promotor['tipo']==1) {
              $partes      = array_pad(array_map('trim', explode("/", $promotor["zona"], 2)), 2, '');
              $emp_nombre  = $partes[0];
              $zona_nombre = $partes[1] ?: $partes[0];
            } else {
              $req_sz = $bdd->prepare("SELECT sub_zona FROM sub_zonas WHERE id='".$colegio["sub_zona"]."'");
              $req_sz->execute();
              $sub_zona    = $req_sz->fetch();
              $emp_nombre  = $promotor['promotor'] ?? '—';
              $zona_nombre = $sub_zona['sub_zona'] ?? '—';
            }
            $resp_txt = ($promotor['tipo']==3 || $promotor['tipo']==1) ? ($promotor['promotor'] ?? '—') : ($colegio['responsable'] ?: '—');

            // Iniciales avatar
            $words_av = array_filter(explode(' ', $colegio['colegio']), fn($w) => strlen($w) > 2);
            $ini_av   = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice($words_av, 0, 2))));
            if (!$ini_av) $ini_av = strtoupper(substr($colegio['colegio'], 0, 2));

            $puede_editar = ($_SESSION["tipo"] != 2 && $_SESSION["tipo"] != 4)
              && ($_SESSION["zona"] == $colegio["cod_zona"] || $_SESSION["tipo"] == 1);

            // Status del colegio para el período actual
            $req_st = $bdd->prepare("
                SELECT sc.status
                FROM colegios_status cs
                JOIN status_cubrimiento sc ON cs.id_status = sc.id
                WHERE cs.id_colegio = ? AND cs.id_periodo = ?
                LIMIT 1
            ");
            $req_st->execute([$colegio['id'], $periodo]);
            $status_actual = strtolower(trim($req_st->fetchColumn() ?: ''));

            if (strpos($status_actual, 'descart') !== false) {
                $badge_txt   = 'Descartado';
                $badge_style = 'background:#fee2e2;color:#dc2626;';
            } elseif (strpos($status_actual, 'inactiv') !== false) {
                $badge_txt   = 'Inactivo';
                $badge_style = 'background:#f3f4f6;color:#6b7280;';
            } else {
                $badge_txt   = 'Activo';
                $badge_style = 'background:#d1fae5;color:#059669;';
            }

            // Pensión mensual del período actual
            $req_pens = $bdd->prepare("SELECT pension FROM pension WHERE cod_colegio=:cod AND id_periodo=:per");
            $req_pens->execute([':cod' => $colegio['codigo'], ':per' => $periodo]);
            $pension_row = $req_pens->fetch();
          ?>

          <!-- Encabezado del colegio -->
          <div class="fc-header">
            <div class="d-flex align-items-center gap-2" style="gap:12px">
              <div class="fc-avatar"><?= $ini_av ?></div>
              <div>
                <div class="d-flex align-items-center flex-wrap">
                  <span class="fc-nombre"><?= htmlspecialchars($colegio['colegio']) ?></span>
                  <span class="fc-badge" style="<?= $badge_style ?>"><?= $badge_txt ?></span>
                </div>
                <small class="text-muted" style="font-size:12px">Código: <?= htmlspecialchars($colegio['codigo']) ?></small>
              </div>
            </div>
            <?php if ($_SESSION['tipo']==1): ?>
            <a href="" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#modal_reasig">
              <i class="bi bi-arrow-left-right"></i> Reasignar
            </a>
            <?php endif; ?>
          </div>

          <!-- Chips de información -->
          <div class="fc-chips">
            <div class="fc-chip">
              <div class="fc-chip-ico blue"><i class="bi bi-building"></i></div>
              <div><div class="fc-chip-lbl">Empresa</div><div class="fc-chip-val"><?= htmlspecialchars($emp_nombre) ?></div></div>
            </div>
            <div class="fc-chip">
              <div class="fc-chip-ico green"><i class="bi bi-geo-alt"></i></div>
              <div><div class="fc-chip-lbl">Zona</div><div class="fc-chip-val"><?= htmlspecialchars($zona_nombre) ?></div></div>
            </div>
            <div class="fc-chip">
              <div class="fc-chip-ico orange"><i class="bi bi-person"></i></div>
              <div><div class="fc-chip-lbl">Responsable</div><div class="fc-chip-val"><?= htmlspecialchars($resp_txt) ?></div></div>
            </div>
          </div>

          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="tab">
                  <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item info_b">
                      <a
                        class="nav-link active"
                        data-toggle="tab"
                        href="#info_basica"
                        role="tab"
                        aria-selected="true"
                        >Información básica</a
                      >
                    </li>
                    <li class="nav-item info_c">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#info_contac"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_info_contac.php"
                        >Información de contacto</a
                      >
                    </li>
                    <li class="nav-item pobla">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#poblacion"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_poblacion.php"
                        >Población</a
                      >
                    </li>
                    <li class="nav-item presupuesto">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#presupuesto"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_presup.php"
                        >Presupuesto</a
                      >
                    </li>
                    <li class="nav-item adop">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#adopciones"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_adopciones.php"
                        >Adopciones</a
                      >
                    </li>
                    <?php /* Atenciones a clientes oculto temporalmente
                    <li class="nav-item atenc">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#atenciones"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_atenciones.php"
                        >Atenciones a clientes</a
                      >
                    </li>
                    */ ?>
                    <li class="nav-item atenc">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#adjuntos"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_adjuntos.php"
                        >Adjuntos</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#visitas"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_visitas.php"
                        >Visitas</a
                      >
                    </li>
                    <?php if ($_SESSION['tipo'] == 1): ?>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#historial"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_historial.php"
                        >Historial</a
                      >
                    </li>
                    <?php endif; ?>
                  </ul>
                  <div class="tab-content">
                    <div
                      class="tab-pane show active"
                      id="info_basica"
                      role="tabpanel"
                    >
                      <div class="pd-20">
                        <form action="php/actualizar_colegio.php" method="POST" enctype="multipart/form-data">

                          <!-- Barra de acciones: vive FUERA de #fc-campos para ser siempre clicable -->
                          <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong style="font-size:14px;color:#374151"><i class="bi bi-info-circle text-primary"></i> Información básica del colegio</strong>
                            <div>
                              <?php if ($puede_editar): ?>
                              <div id="fc-acciones-ver">
                                <button type="button" id="btn-editar" class="btn btn-primary btn-sm">
                                  <i class="bi bi-pencil"></i> Editar
                                </button>
                              </div>
                              <div id="fc-acciones-edit" style="display:none">
                                <button type="button" id="btn-cancelar" class="btn btn-light btn-sm mr-1">Cancelar</button>
                                <button type="submit" class="btn btn-success btn-sm">
                                  <i class="bi bi-check-lg"></i> Guardar cambios
                                </button>
                              </div>
                              <?php endif; ?>
                            </div>
                          </div>

                          <!-- Contenedor de campos — bloqueado en modo vista -->
                          <div id="fc-campos" class="fc-readonly">
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Nombre de la institución <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Nombre de la institución" name="colegio"  value="<?php echo $colegio['colegio']; ?>" required />
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label>Código</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($colegio['codigo']); ?>" readonly />
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label>Provincia <small style="color:red;"> *</small></label>
                                <select class="custom-select2" name="departamento" required>
                                  <option value="">Seleccione...</option>
                                  <?php
                                    $sql = "SELECT * FROM departamentos ORDER BY departamento";
                                    $req = $bdd->prepare($sql);
                                    $req->execute();
                                    $departamentos = $req->fetchAll();
                                    foreach ($departamentos as $departamento) {
                                      $sel = $departamento["id"]==$colegio["departamento"] ? ' SELECTED' : '';
                                      echo '<option value="'.$departamento["id"].'"'.$sel.'>'.$departamento["departamento"].'</option>';
                                    }
                                  ?>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Ciudad <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Ciudad" name="ciudad" value="<?php echo htmlspecialchars($colegio['ciudad']); ?>" required />
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Ubicación <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Ubicación" name="direccion" value="<?php echo $colegio['direccion']; ?>" required/>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Teléfono <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Teléfono" name="telefono_c" value="<?php echo $colegio['telefono']; ?>" required/>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Página Web</label>
                                <input type="text" class="form-control" placeholder="Página Web" name="web"  value="<?php echo $colegio['web']; ?>"/>
                              </div>
                            </div>
                            <?php if ($_SESSION['tipo']==6): ?>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Responsable <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Responsable" name="responsable" value="<?php echo htmlspecialchars($colegio['responsable']); ?>" required/>
                              </div>
                            </div>
                            <?php endif; ?>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Cumpleaños del colegio</label>
                                <input type="date" class="form-control" name="cumpleanos_c" value="<?php echo ($colegio['cumpleaños'] ?? '') !== '0000-00-00' ? ($colegio['cumpleaños'] ?? '') : ''; ?>" />
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Costo mensual de pensión</label>
                                <input type="text" class="form-control" placeholder="Costo mensual de pensión" name="pension" value="<?php echo htmlspecialchars($pension_row['pension'] ?? ''); ?>"/>
                              </div>
                            </div>
                          </div>

                          <?php
                            $sql_cargos_qd = "SELECT * FROM cargos WHERE id != 10";
                            $req_cargos_qd = $bdd->prepare($sql_cargos_qd);
                            $req_cargos_qd->execute();
                            $cargos_qd = $req_cargos_qd->fetchAll();
                            $cargos_qd_map = array_column($cargos_qd, 'cargo', 'id');
                            $qd_val = $colegio['quien_decide'] ?? '';
                            $qd_is_otro = $qd_val !== '' && !isset($cargos_qd_map[$qd_val]);
                            $qd_sel = $qd_is_otro ? 'otro' : $qd_val;
                            $qd_otro_val = $qd_is_otro ? htmlspecialchars($qd_val) : '';
                          ?>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>¿Quién decide?</label>
                                <select class="custom-select" name="quien_decide" id="quien_decide_sel"
                                        onchange="toggleQuienDecideOtro(this)">
                                  <option value="">Seleccione...</option>
                                  <?php foreach ($cargos_qd as $c):
                                    $sel = $c['id'] == $qd_sel ? 'selected' : '';
                                    echo '<option value="'.$c['id'].'" '.$sel.'>'.htmlspecialchars($c['cargo']).'</option>';
                                  endforeach; ?>
                                  <option value="otro" <?= $qd_sel === 'otro' ? 'selected' : '' ?>>Otro</option>
                                </select>
                                <input type="text" class="form-control mt-1<?= $qd_is_otro ? '' : ' d-none' ?>"
                                       name="quien_decide_otro" id="quien_decide_otro"
                                       value="<?= $qd_otro_val ?>" placeholder="Especifique quién decide"
                                       <?= $qd_is_otro ? 'required' : '' ?> />
                              </div>
                            </div>
                          </div>

                          <?php
                            $req_status = $bdd->prepare("SELECT id_status FROM colegios_status WHERE id_colegio=:cole AND id_periodo=:per");
                            $req_status->execute([':cole' => $colegio["id"], ':per' => $periodo]);
                            $cole_status = $req_status->fetch();

                            $status_ops = $bdd->query("SELECT * FROM status_cubrimiento WHERE act=1")->fetchAll();

                            $req_estcli = $bdd->prepare("SELECT id_estado_cliente FROM colegios_estados_clientes WHERE id_colegio=:cole AND id_periodo=:per");
                            $req_estcli->execute([':cole' => $colegio["id"], ':per' => $periodo]);
                            $cole_estcli = $req_estcli->fetch();

                            $estcli_ops = $bdd->query("SELECT * FROM estados_cliente WHERE act=1")->fetchAll();

                            $segmento_ops = $bdd->query("SELECT * FROM segmentos WHERE act=1")->fetchAll();
                          ?>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Segmento <small style="color:red;"> *</small></label>
                                <select class="custom-select" name="segmento" required>
                                  <option value="">Seleccione...</option>
                                  <?php foreach ($segmento_ops as $segmento):
                                    $sel = $segmento["id"]==$colegio["id_segmento"] ? ' selected' : '';
                                    echo '<option value="'.$segmento["id"].'"'.$sel.'>'.htmlspecialchars($segmento["segmento"]).'</option>';
                                  endforeach; ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Status <small style="color:red;"> *</small></label>
                                <select class="custom-select" name="status" required>
                                  <option value="">Seleccione...</option>
                                  <?php foreach ($status_ops as $statu):
                                    $sel = ($cole_status && $statu["id"]==$cole_status["id_status"]) ? ' selected' : '';
                                    echo '<option value="'.$statu["id"].'"'.$sel.'>'.htmlspecialchars($statu["status"]).'</option>';
                                  endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Estado a cliente <small style="color:red;"> *</small></label>
                                <select class="custom-select" name="estado_cliente" required>
                                  <option value="">Seleccione...</option>
                                  <?php foreach ($estcli_ops as $estado):
                                    $sel = ($cole_estcli && $estado["id"]==$cole_estcli["id_estado_cliente"]) ? ' selected' : '';
                                    echo '<option value="'.$estado["id"].'"'.$sel.'>'.htmlspecialchars($estado["estado"]).'</option>';
                                  endforeach; ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          </div><!-- /#fc-campos -->

                          <?php if ($_SESSION['tipo']!=6): ?>
                          <input type="hidden" name="responsable" value="<?php echo htmlspecialchars($colegio['responsable']); ?>">
                          <?php endif; ?>

                          <input type="hidden" name="id_colegio"  value='<?php echo $colegio["id"] ?>'>
                          <input type="hidden" name="periodo"     value="<?php echo $periodo ?>">
                          <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane" id="info_contac" role="tabpanel"></div>
                    <div class="tab-pane" id="poblacion" role="tabpanel"></div>

                    <div class="tab-pane" id="presupuesto" role="tabpanel"></div>

                    <div class="tab-pane" id="adopciones" role="tabpanel"></div>

                    <div class="tab-pane" id="atenciones" role="tabpanel"></div>

                    <div class="tab-pane" id="adjuntos" role="tabpanel"></div>

                    <div class="tab-pane" id="visitas" role="tabpanel"></div>

                    <?php if ($_SESSION['tipo'] == 1): ?>
                    <div class="tab-pane" id="historial" role="tabpanel"></div>
                    <?php endif; ?>

                  </div>
                </div>
                      

                <style>
                  .rs-modal .modal-content  { border:none; border-radius:12px; overflow:hidden; box-shadow:0 10px 40px rgba(0,0,0,.15); }
                  .rs-modal .modal-header   { background:#fff; padding:18px 24px 14px; border-bottom:1px solid #e9ecef; }
                  .rs-modal .modal-title    { font-size:16px; font-weight:700; color:#111827; margin:0 0 3px; }
                  .rs-modal .modal-subtitle { font-size:12.5px; color:#6b7280; margin:0; }
                  .rs-modal .close          { color:#9ca3af; opacity:1; text-shadow:none; font-size:1.3rem; margin-top:-4px; }
                  .rs-modal .close:hover    { color:#374151; }
                  .rs-modal .modal-body     { padding:22px 24px; background:#f9fafb; }
                  .rs-modal .modal-footer   { border-top:1px solid #e9ecef; padding:14px 24px; background:#fff; display:flex; justify-content:flex-end; gap:8px; }
                  .rs-section               { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px 20px; }
                  .rs-modal .form-group     { margin-bottom:0; }
                  .rs-modal .form-group label { font-size:12px; font-weight:600; color:#374151; margin-bottom:5px; display:block; }
                  .rs-modal .form-control   { border-radius:7px; font-size:13px; border:1px solid #d1d5db; padding:7px 10px; background:#fff; color:#111827; transition:border-color .15s, box-shadow .15s; }
                  .rs-modal .form-control:focus { border-color:#4f46e5; background:#fff; box-shadow:0 0 0 3px rgba(79,70,229,.1); outline:none; }
                  .rs-req                   { color:#ef4444; }
                  .rs-modal .btn-primary    { background:#4f46e5; border-color:#4f46e5; border-radius:8px; padding:8px 22px; font-weight:600; font-size:13.5px; }
                  .rs-modal .btn-primary:hover { background:#4338ca; border-color:#4338ca; }
                  .rs-modal .btn-light      { border-radius:8px; font-size:13.5px; }
                </style>

                <div class="modal fade rs-modal" id="modal_reasig" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                      <div class="modal-header">
                        <div>
                          <h5 class="modal-title">Reasignar colegio</h5>
                          <p class="modal-subtitle">Asigna este colegio a otra empresa, zona o responsable</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      </div>

                      <form action="php/reasignar_colegio.php" method="POST">
                      <div class="modal-body">
                        <div class="rs-section">
                          <div class="row g-3">
                            <div class="col-sm-6 col-12">
                              <div class="form-group">
                                <label for="empresa">Empresa <span class="rs-req">*</span></label>
                                <select name="empresa" id="empresa" class="form-control custom-select2" required>
                                  <option value="">Seleccionar empresa...</option>
                                  <option value="1">EUREKA</option>
                                  <?php
                                    $sql = "SELECT * FROM zonas WHERE zona NOT LIKE '%Eureka%' AND zona NOT LIKE '%ALEJANDRO%'";
                                    $req = $bdd->prepare($sql); $req->execute();
                                    foreach ($req->fetchAll() as $zona)
                                      echo '<option value="'.$zona["codigo"].'">'.$zona["zona"].'</option>';
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6 col-12">
                              <div class="form-group">
                                <label for="zona">Zona <span class="rs-req">*</span></label>
                                <select name="zona" id="zona" class="form-control custom-select2" required>
                                  <option value="">Seleccionar zona...</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6 col-12 col-responsable d-none">
                              <div class="form-group">
                                <label for="responsable">Responsable <span class="rs-req">*</span></label>
                                <input type="text" name="responsable" id="responsable" class="form-control" placeholder="Nombre del responsable">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-left-right"></i> Reasignar</button>
                      </div>

                      <input type="hidden" name="id_colegio"  value="<?= $colegio['id'] ?>">
                      <input type="hidden" name="cod_colegio" value="<?= $colegio['codigo'] ?>">
                      <input type="hidden" name="periodo"     value="<?= $periodo ?>">
                      </form>

                    </div>
                  </div>
                </div>
        </div>
        <?php

          $sql = "SELECT f_cierre FROM periodos WHERE id='".$_GET['periodo']."'";

          $req = $bdd->prepare($sql);
          $req->execute();

          $gp_periodo = $req->fetch();

        ?>
        <?php include("template/footer.php"); ?>
      </div>
    </div>
    
    <!-- js -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="src/ink-alerts.js"></script>

    <script>
    function toggleQuienDecideOtro(sel) {
      var $inp = $('#quien_decide_otro');
      if ($(sel).val() === 'otro') {
        $inp.removeClass('d-none').attr('required', 'required');
      } else {
        $inp.addClass('d-none').removeAttr('required').val('');
      }
    }

       $("#zonificacion").addClass("show");
       $("#zonificacion .submenu").css("display","block");
       $("#ver_zonificacion").addClass("active");

      // ── Toggle editar / cancelar ──────────────────────────────────────────
      $('#btn-editar').on('click', function () {
        $('#fc-campos').removeClass('fc-readonly');
        $('#fc-acciones-ver').hide();
        $('#fc-acciones-edit').show();
      });

      $('#btn-cancelar').on('click', function () {
        location.reload();
      });

      $(document).ready(function () {
        $("#modal_reasig .custom-select2").select2({
          dropdownParent: $('#modal_reasig')
        });
        //llamar contenido de tabs dinamicamente
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href");
          var baseUrl = $(e.target).data("url");
          var codigo = <?php echo json_encode($colegio["codigo"]); ?>;
          // Agrega parámetros dinámicos
          if (target !="#presupuesto" && target !="#adopciones") {
            var urlConParametros = baseUrl + '?colegio=' + <?php echo $colegio["id"] ?> + '&periodo='+ <?php echo $_GET["periodo"] ?>+ '&codigo='+ encodeURIComponent(codigo)+ '&id_calendario='+ <?php echo $colegio['id_calendario'] ?? 0 ?>;
          }else{
            var responsable = <?php echo json_encode($colegio["responsable"]); ?>;
            var f_cierre = <?php echo json_encode($gp_periodo["f_cierre"]); ?>;
            var urlConParametros = baseUrl + '?colegio=' + <?php echo $colegio["id"] ?> + '&periodo='+ <?php echo $_GET["periodo"] ?>+ '&codigo='+ encodeURIComponent(codigo)+ '&cod_zona='+ <?php echo $colegio['cod_zona'] ?>+ '&sub_zona='+ <?php echo $colegio['sub_zona'] ?>+ '&responsable='+encodeURIComponent(responsable)+ '&promotor='+ <?php echo $promotor['id'] ?? 0 ?>+ '&f_cierre='+encodeURIComponent(f_cierre);

           
          }
          

          if ($(target).is(':empty')) {
            $(target).html("<br><br><center style='font-size:30px; color:#E25906'>Cargando...</center>");
            $(target).load(urlConParametros);

          }

          setTimeout(function () {
            const selects = $(target).find('select.custom-select2');
            if (selects.length) {
              

              $("#modal_atenciones .custom-select2").select2({
                dropdownParent: $('#modal_atenciones')
              });

              $("#modal_profes .custom-select2").select2({
                dropdownParent: $('#modal_profes')
              });

              $("#modal_presupuesto .custom-select2").select2({
                  dropdownParent: $('#modal_presupuesto')
              });

              $("#modal_adopciones .custom-select2").select2({
                dropdownParent: $('#modal_adopciones')
              });
            }
          }, 1000); // Pequeña espera para asegurar que el DOM ya fue insertado
        });

        //activar tabs
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');

        if (tab) {
           $('.nav-link[href="#' + tab + '"]').tab('show');

        }

      });


      $('#empresa').on('change',function(){
        var valor = $(this).val();
        
        if (valor==1) {
          $(".col-responsable").addClass("d-none");
          $(".col-responsable").addClass("d-none");
           $("#responsable").removeAttr("required");
        }else{
          $(".col-responsable").removeClass("d-none");
          $("#responsable").attr("required","required");
        }
       
        var dataString = 'empresa='+valor;
        $.ajax({

          url: "ajax/buscar_zona.php",
          type: "POST",
          data: dataString,
          success: function (resp) {
                   
            $("#zona").html(resp);                        
            console.log(resp);
            if(valor =="") {
              $("#zona").html("");
            }
          },
          error: function (jqXHR,estado,error){
            alert("error");
            console.log(estado);
            console.log(error);
          },
          complete: function (jqXHR,estado){
            console.log(estado);
          }

                          
        })
                
      });

    </script>

  <!-- Toast notificación global -->
  <style>
    .cole-toast { position:fixed; top:24px; right:24px; min-width:260px; padding:14px 20px; border-radius:10px; font-size:.87rem; font-weight:600; color:#fff; z-index:99999; box-shadow:0 6px 20px rgba(0,0,0,.18); display:flex; align-items:center; gap:10px; opacity:0; transform:translateY(-16px); transition:opacity .3s, transform .3s; pointer-events:none; }
    .cole-toast.show { opacity:1; transform:translateY(0); }
    .cole-toast.ok   { background:#16a34a; }
  </style>
  <div class="cole-toast" id="cole-toast"><i class="bi bi-check-circle-fill"></i><span id="cole-toast-msg"></span></div>
  <script>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'reasignado'): ?>
    (function(){
      var $t = document.getElementById('cole-toast');
      document.getElementById('cole-toast-msg').textContent = 'Colegio reasignado correctamente';
      $t.classList.add('ok', 'show');
      setTimeout(function(){ $t.classList.remove('show'); }, 3500);
    })();
    <?php endif; ?>
  </script>

  </body>
</html>
