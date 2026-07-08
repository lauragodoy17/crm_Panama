<?php
  require_once("php/aut.php"); require_once("conexion/bdd.php");

  $req_zona = $bdd->prepare("SELECT zona FROM zonas WHERE codigo='".$_SESSION['zona']."'");
  $req_zona->execute();
  $zona_actual = $req_zona->fetch();

  $requiere_responsable = ($_SESSION['tipo'] == 6);
  $es_admin = ($_SESSION['tipo'] == 1);

  $empresas_distribuidoras = [];
  if ($es_admin) {
    $req_emp = $bdd->prepare("SELECT codigo, zona FROM zonas WHERE zona NOT LIKE '%Eureka%' AND zona NOT LIKE '%ALEJANDRO%' ORDER BY zona");
    $req_emp->execute();
    $empresas_distribuidoras = $req_emp->fetchAll();
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Crear colegio</title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <style>
    /* ── Stepper ── */
    .cc-stepper { display:flex; align-items:flex-start; margin-bottom:32px; }
    .cc-step { display:flex; flex-direction:column; align-items:center; flex:1; position:relative; }
    .cc-step-circle {
      width:38px; height:38px; border-radius:50%;
      border:2px solid #dee2e6; background:#fff;
      display:flex; align-items:center; justify-content:center;
      font-weight:700; font-size:14px; color:#adb5bd; z-index:1;
      transition: all .3s;
    }
    .cc-step.active .cc-step-circle  { background:#4361ee; border-color:#4361ee; color:#fff; }
    .cc-step.done   .cc-step-circle  { background:#198754; border-color:#198754; color:#fff; }
    .cc-step-label { font-size:12px; margin-top:6px; color:#adb5bd; text-align:center; font-weight:500; }
    .cc-step.active .cc-step-label   { color:#4361ee; font-weight:600; }
    .cc-step.done   .cc-step-label   { color:#198754; }
    .cc-line { flex:1; height:2px; background:#dee2e6; margin-top:19px; transition:background .3s; }
    .cc-line.done { background:#198754; }

    /* ── Cards ── */
    .cc-card { background:#fff; border:1px solid #e9ecef; border-radius:10px; padding:24px; margin-bottom:0; }
    .cc-card-title { font-size:15px; font-weight:700; color:#212529; margin-bottom:18px; display:flex; align-items:center; gap:8px; }
    .cc-card-title i { color:#4361ee; }

    /* ── Sidebar ── */
    .cc-sidebar { display:flex; flex-direction:column; gap:16px; }
    .cc-summary-empty { text-align:center; padding:24px 16px; }
    .cc-summary-empty .cc-empty-icon { width:64px; height:64px; background:#f0f3ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; }
    .cc-summary-empty .cc-empty-icon i { font-size:28px; color:#4361ee; }
    .cc-summary-empty p { font-size:13px; color:#6c757d; margin:0; }
    .cc-summary-list { list-style:none; padding:0; margin:0; }
    .cc-summary-list li { display:flex; gap:8px; padding:7px 0; border-bottom:1px solid #f0f0f0; font-size:13px; }
    .cc-summary-list li:last-child { border-bottom:none; }
    .cc-summary-list .cc-sl-label { color:#6c757d; min-width:90px; }
    .cc-summary-list .cc-sl-val { color:#212529; font-weight:600; word-break:break-word; }
    .cc-tip { display:flex; align-items:flex-start; gap:8px; font-size:12.5px; color:#495057; margin-bottom:8px; }
    .cc-tip i { color:#198754; font-size:14px; margin-top:1px; flex-shrink:0; }
    .cc-info-box { background:#f0f3ff; border-radius:8px; padding:12px 14px; font-size:12.5px; color:#4361ee; }

    /* ── Steps content ── */
    .cc-step-content { display:none; }
    .cc-step-content.active { display:block; }

    /* ── Bottom actions ── */
    .cc-actions { display:flex; justify-content:space-between; align-items:center; margin-top:24px; }

    /* ── Fields ── */
    .form-group label { font-size:13px; font-weight:600; color:#495057; margin-bottom:5px; }
    .form-group label small { font-weight:400; }
  </style>
</head>
<body>
  <?php include("template/nav_side.php"); ?>
  <div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
      <div class="min-height-200px">

        <div class="page-header">
          <div class="row align-items-center">
            <div class="col">
              <div class="title"><h4>Crear colegio</h4></div>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="ver_colegios.php">Zonificación</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear colegio</li>
                </ol>
              </nav>
            </div>
            <div class="col-auto">
              <span class="badge badge-primary" style="font-size:13px">Zona: <?php echo htmlspecialchars($zona_actual['zona'] ?? ''); ?></span>
            </div>
          </div>
        </div>

        <div class="row">

          <!-- ── Columna principal ── -->
          <div class="col-lg-9 col-md-8">
            <div class="cc-card">

              <!-- Stepper -->
              <div class="cc-stepper">
                <div class="cc-step active" id="st1">
                  <div class="cc-step-circle" id="sc1">1</div>
                  <div class="cc-step-label">Información básica</div>
                </div>
                <div class="cc-line" id="ln1"></div>
                <div class="cc-step" id="st2">
                  <div class="cc-step-circle" id="sc2">2</div>
                  <div class="cc-step-label">Ubicación y contacto</div>
                </div>
              </div>

              <form name="crear_colegio" id="form-crear" action="php/crear_colegio.php" method="POST">

                <!-- ══ PASO 1: Información básica ══ -->
                <div class="cc-step-content active" id="step1">
                  <div class="cc-card-title"><i class="bi bi-info-circle"></i> Información básica</div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="colegio">Nombre del colegio <small style="color:red">*</small></label>
                        <input type="text" name="colegio" id="colegio" class="form-control" placeholder="Nombre del colegio" />
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="departamento">Provincia <small style="color:red">*</small></label>
                        <select name="departamento" id="departamento" class="form-control custom-select2">
                          <option value="">Seleccionar provincia</option>
                          <?php
                            $req = $bdd->prepare("SELECT * FROM departamentos ORDER BY departamento");
                            $req->execute();
                            foreach ($req->fetchAll() as $d) {
                              echo '<option value="'.$d["id"].'">'.$d["departamento"].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="ciudad">Ciudad <small style="color:red">*</small></label>
                        <input type="text" name="ciudad" id="ciudad" class="form-control" placeholder="Ciudad" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- ══ PASO 2: Ubicación y contacto ══ -->
                <div class="cc-step-content" id="step2">
                  <div class="cc-card-title"><i class="bi bi-geo-alt"></i> Ubicación y contacto</div>
                  <div class="row">
                    <div class="col-sm-<?php echo $requiere_responsable ? '4' : '6'; ?>">
                      <div class="form-group">
                        <label for="direccion">Ubicación <small style="color:red">*</small></label>
                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ej: Calle 10, Edificio 5" />
                      </div>
                    </div>
                    <div class="col-sm-<?php echo $requiere_responsable ? '4' : '6'; ?>">
                      <div class="form-group">
                        <label for="telefono">Teléfono <small style="color:red">*</small></label>
                        <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="Ej: 6012 3456" />
                      </div>
                    </div>
                    <?php if ($requiere_responsable): ?>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="responsable">Responsable <small style="color:red">*</small></label>
                        <input type="text" name="responsable" id="responsable" class="form-control" placeholder="Responsable" />
                      </div>
                    </div>
                    <?php endif; ?>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="web">Página web</label>
                        <input type="text" name="web" id="web" class="form-control" placeholder="Ej: https://colegio.edu.pa" />
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="cumple_colegio">Cumpleaños del colegio</label>
                        <input type="date" name="cumple_colegio" id="cumple_colegio" class="form-control" />
                      </div>
                    </div>
                  </div>

                  <?php if ($es_admin): ?>
                  <hr class="my-3" />
                  <div class="cc-card-title"><i class="bi bi-diagram-3"></i> Asignación de zona</div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="empresa">Empresa <small style="color:red">*</small></label>
                        <select name="empresa" id="empresa" class="form-control custom-select2">
                          <option value="">Seleccionar empresa...</option>
                          <option value="1">EUREKA</option>
                          <?php foreach ($empresas_distribuidoras as $emp): ?>
                            <option value="<?php echo htmlspecialchars($emp['codigo']); ?>"><?php echo htmlspecialchars($emp['zona']); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="zona_asignada">Zona <small style="color:red">*</small></label>
                        <select name="zona_asignada" id="zona_asignada" class="form-control custom-select2">
                          <option value="">Seleccionar zona...</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4 d-none" id="col-responsable-admin">
                      <div class="form-group">
                        <label for="responsable_admin">Responsable <small style="color:red">*</small></label>
                        <input type="text" name="responsable_admin" id="responsable_admin" class="form-control" placeholder="Nombre del responsable" />
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                </div>

                <?php if (!$es_admin): ?>
                <input type="hidden" name="cod_zona" value="<?php echo htmlspecialchars($_SESSION['zona']); ?>" />
                <?php endif; ?>

                <!-- ── Acciones ── -->
                <div class="cc-actions">
                  <a href="ver_colegios.php" class="btn btn-light px-4">Cancelar</a>
                  <div class="d-flex gap-2">
                    <button type="button" id="btn-prev" class="btn btn-outline-secondary px-4 d-none">
                      <i class="bi bi-arrow-left"></i> Anterior
                    </button>
                    <button type="button" id="btn-next" class="btn btn-primary px-4">
                      Siguiente <i class="bi bi-arrow-right"></i>
                    </button>
                    <button type="submit" id="btn-submit" class="btn btn-success px-4 d-none">
                      <i class="bi bi-check-lg"></i> Crear colegio
                    </button>
                  </div>
                </div>

              </form>
            </div>
          </div>

          <!-- ── Sidebar ── -->
          <div class="col-lg-3 col-md-4">
            <div class="cc-sidebar">

              <!-- Resumen -->
              <div class="cc-card">
                <div class="cc-card-title"><i class="bi bi-file-earmark-text"></i> Resumen del colegio</div>
                <div id="cc-summary-empty" class="cc-summary-empty">
                  <div class="cc-empty-icon"><i class="bi bi-building"></i></div>
                  <p>Aún no hay información.<br>Completa los campos para ver un resumen del colegio aquí.</p>
                </div>
                <ul class="cc-summary-list d-none" id="cc-summary-list">
                  <li id="sr-nombre"  class="d-none"><span class="cc-sl-label">Nombre</span><span class="cc-sl-val" id="sv-nombre">—</span></li>
                  <li id="sr-depto"   class="d-none"><span class="cc-sl-label">Provincia</span><span class="cc-sl-val" id="sv-depto">—</span></li>
                  <li id="sr-ciudad"  class="d-none"><span class="cc-sl-label">Ciudad</span><span class="cc-sl-val" id="sv-ciudad">—</span></li>
                  <li id="sr-dir"     class="d-none"><span class="cc-sl-label">Ubicación</span><span class="cc-sl-val" id="sv-dir">—</span></li>
                  <li id="sr-tel"     class="d-none"><span class="cc-sl-label">Teléfono</span><span class="cc-sl-val" id="sv-tel">—</span></li>
                  <?php if ($es_admin): ?>
                  <li id="sr-empresa" class="d-none"><span class="cc-sl-label">Empresa</span><span class="cc-sl-val" id="sv-empresa">—</span></li>
                  <li id="sr-zona"    class="d-none"><span class="cc-sl-label">Zona</span><span class="cc-sl-val" id="sv-zona">—</span></li>
                  <?php endif; ?>
                  <li id="sr-resp"    class="d-none"><span class="cc-sl-label">Responsable</span><span class="cc-sl-val" id="sv-resp">—</span></li>
                  <li id="sr-web"     class="d-none"><span class="cc-sl-label">Página web</span><span class="cc-sl-val" id="sv-web">—</span></li>
                  <li id="sr-cumple"  class="d-none"><span class="cc-sl-label">Cumpleaños</span><span class="cc-sl-val" id="sv-cumple">—</span></li>
                </ul>
              </div>

              <!-- Consejos -->
              <div class="cc-card">
                <div class="cc-card-title"><i class="bi bi-lightbulb"></i> Consejos</div>
                <?php if ($es_admin): ?>
                <div class="cc-tip"><i class="bi bi-check-circle-fill"></i> Selecciona la empresa y la zona a la que quedará asignado el colegio.</div>
                <?php else: ?>
                <div class="cc-tip"><i class="bi bi-check-circle-fill"></i> El colegio se asignará automáticamente a tu zona actual.</div>
                <?php endif; ?>
                <div class="cc-tip"><i class="bi bi-check-circle-fill"></i> El código del colegio se genera automáticamente al crearlo.</div>
                <div class="cc-tip"><i class="bi bi-check-circle-fill"></i> Los campos marcados con <span style="color:red">*</span> son obligatorios.</div>
              </div>

            </div>
          </div>

        </div><!-- /.row -->
      </div>
      <?php include("template/footer.php"); ?>
    </div>
  </div>

  <script src="vendors/scripts/core.js"></script>
  <script src="vendors/scripts/script.min.js"></script>
  <script src="vendors/scripts/process.js"></script>
  <script src="vendors/scripts/layout-settings.js"></script>

  <script>
  $(function () {

    var currentStep = 1;
    var totalSteps  = 2;
    var requiereResponsable = <?php echo $requiere_responsable ? 'true' : 'false'; ?>;
    var esAdmin = <?php echo $es_admin ? 'true' : 'false'; ?>;

    // ── Navegar pasos ────────────────────────────────────────────────────────
    function goTo(step) {
      // Ocultar todos los pasos
      $('.cc-step-content').removeClass('active');
      $('#step' + step).addClass('active');

      // Actualizar círculos y líneas
      for (var i = 1; i <= totalSteps; i++) {
        var $st = $('#st' + i);
        $st.removeClass('active done');
        if (i < step)       $st.addClass('done');
        else if (i === step) $st.addClass('active');
      }
      // Actualizar ícono de círculos completados
      for (var i = 1; i <= totalSteps; i++) {
        var $sc = $('#sc' + i);
        if (i < step) $sc.html('<i class="bi bi-check-lg"></i>');
        else          $sc.text(i);
      }
      // Líneas
      for (var i = 1; i < totalSteps; i++) {
        $('#ln' + i).toggleClass('done', i < step);
      }

      // Botones
      $('#btn-prev').toggleClass('d-none', step === 1);
      $('#btn-next').toggleClass('d-none', step === totalSteps);
      $('#btn-submit').toggleClass('d-none', step !== totalSteps);

      currentStep = step;
    }

    $('#btn-next').on('click', function () {
      if (!validarPaso(currentStep)) return;
      if (currentStep < totalSteps) goTo(currentStep + 1);
    });

    $('#btn-prev').on('click', function () {
      if (currentStep > 1) goTo(currentStep - 1);
    });

    // ── Validación por paso ──────────────────────────────────────────────────
    function validarPaso(paso) {
      if (paso === 1) {
        if (!$('#colegio').val().trim())     { alert('El nombre del colegio es obligatorio.'); $('#colegio').focus(); return false; }
        if (!$('#departamento').val())       { alert('Selecciona una provincia.'); return false; }
        if (!$('#ciudad').val().trim())      { alert('La ciudad es obligatoria.'); $('#ciudad').focus(); return false; }
      }
      if (paso === 2) {
        if (!$('#direccion').val().trim())   { alert('La ubicación es obligatoria.'); $('#direccion').focus(); return false; }
        if (!$('#telefono').val().trim())    { alert('El teléfono es obligatorio.'); $('#telefono').focus(); return false; }
        if (requiereResponsable && !$('#responsable').val().trim()) { alert('El responsable es obligatorio.'); $('#responsable').focus(); return false; }
        if (esAdmin) {
          if (!$('#empresa').val())          { alert('Selecciona la empresa a la que se asignará el colegio.'); return false; }
          if (!$('#zona_asignada').val())    { alert('Selecciona la zona a la que se asignará el colegio.'); return false; }
          if ($('#empresa').val() !== '1' && !$('#responsable_admin').val().trim()) {
            alert('El responsable es obligatorio.'); $('#responsable_admin').focus(); return false;
          }
        }
      }
      return true;
    }

    // ── Validar al enviar ────────────────────────────────────────────────────
    $('#form-crear').on('submit', function (e) {
      for (var p = 1; p <= totalSteps; p++) {
        if (!validarPaso(p)) { goTo(p); e.preventDefault(); return; }
      }
    });

    // ── Resumen lateral ──────────────────────────────────────────────────────
    function actualizarResumen() {
      var hay = false;
      function set(rowId, valId, val) {
        if (val) { $('#' + rowId).removeClass('d-none'); $('#' + valId).text(val); hay = true; }
        else      { $('#' + rowId).addClass('d-none'); }
      }
      set('sr-nombre',  'sv-nombre',  $('#colegio').val().trim());
      set('sr-depto',   'sv-depto',   $('#departamento option:selected').text() !== 'Seleccionar provincia' ? $('#departamento option:selected').text() : '');
      set('sr-ciudad',  'sv-ciudad',  $('#ciudad').val().trim());
      set('sr-dir',     'sv-dir',     $('#direccion').val().trim());
      set('sr-tel',     'sv-tel',     $('#telefono').val().trim());
      if (esAdmin) {
        set('sr-empresa', 'sv-empresa', $('#empresa option:selected').val() ? $('#empresa option:selected').text() : '');
        set('sr-zona',    'sv-zona',    $('#zona_asignada option:selected').val() ? $('#zona_asignada option:selected').text() : '');
      }
      var respVal = $('#responsable').length ? $('#responsable').val().trim() : ($('#responsable_admin').val() || '').trim();
      set('sr-resp',    'sv-resp',    respVal);
      set('sr-web',     'sv-web',     $('#web').val().trim());
      set('sr-cumple',  'sv-cumple',  $('#cumple_colegio').val().trim());

      if (hay) {
        $('#cc-summary-empty').addClass('d-none');
        $('#cc-summary-list').removeClass('d-none');
      } else {
        $('#cc-summary-empty').removeClass('d-none');
        $('#cc-summary-list').addClass('d-none');
      }
    }

    $('input, select').on('input change', actualizarResumen);

    // ── Select2 en provincia ─────────────────────────────────────────────────
    if ($.fn.select2) {
      $('#departamento').select2({ width: '100%', placeholder: 'Seleccionar provincia...' });
      if (esAdmin) {
        $('#empresa').select2({ width: '100%', placeholder: 'Seleccionar empresa...' });
        $('#zona_asignada').select2({ width: '100%', placeholder: 'Seleccionar zona...' });
      }
    }

    // ── Empresa → Zona (asignación, solo admin) ────────────────────────────────
    if (esAdmin) {
      $('#empresa').on('change', function () {
        var valor = $(this).val();

        if (valor === '1') {
          $('#col-responsable-admin').addClass('d-none');
          $('#responsable_admin').removeAttr('required');
        } else {
          $('#col-responsable-admin').toggleClass('d-none', !valor);
          if (valor) $('#responsable_admin').attr('required', 'required');
        }

        if (!valor) {
          $('#zona_asignada').html('<option value="">Seleccionar zona...</option>').trigger('change');
          return;
        }

        $.ajax({
          url: 'ajax/buscar_zona.php',
          type: 'POST',
          data: { empresa: valor },
          success: function (resp) {
            $('#zona_asignada').html(resp).trigger('change');
          },
          error: function () {
            alert('No se pudieron cargar las zonas de la empresa seleccionada.');
          }
        });
      });
    }

  });
  </script>

</body>
</html>
