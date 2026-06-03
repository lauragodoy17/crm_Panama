<?php require_once("php/aut.php"); require_once("conexion/bdd.php"); ?>
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
                  <li class="breadcrumb-item">Zonificación</li>
                  <li class="breadcrumb-item active">Crear colegio</li>
                </ol>
              </nav>
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
                <div class="cc-line" id="ln2"></div>
                <div class="cc-step" id="st3">
                  <div class="cc-step-circle" id="sc3">3</div>
                  <div class="cc-step-label">Clasificación</div>
                </div>
              </div>

              <form name="crear_colegio" id="form-crear" action="php/crear_colegio.php" method="POST">

                <!-- ══ PASO 1: Información básica ══ -->
                <div class="cc-step-content active" id="step1">
                  <div class="cc-card-title"><i class="bi bi-info-circle"></i> Información básica</div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="dane">DANE <small style="color:red">*</small></label>
                        <input type="text" name="dane" id="dane" class="form-control" placeholder="Ej: 111001000123" maxlength="12" inputmode="numeric" />
                        <small id="dane-feedback" class="form-text" style="display:none"></small>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div class="form-group">
                        <label for="colegio">Nombre del colegio <small style="color:red">*</small></label>
                        <input type="text" name="colegio" id="colegio" class="form-control" placeholder="Nombre del colegio" />
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="calendario">Calendario <small style="color:red">*</small></label>
                        <select name="calendario" id="calendario" class="form-control">
                          <option value="">Seleccionar calendario</option>
                          <?php
                            $req = $bdd->prepare("SELECT * FROM calendarios WHERE act=1");
                            $req->execute();
                            foreach ($req->fetchAll() as $c) {
                              echo '<option value="'.$c["id"].'">'.$c["calendario"].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="departamento">Departamento <small style="color:red">*</small></label>
                        <select name="departamento" id="departamento" class="form-control custom-select2">
                          <option value="">Seleccionar departamento</option>
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
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="ciudad_select">Ciudad <small style="color:red">*</small></label>
                        <select id="ciudad_select" class="form-control">
                          <option value="">Primero seleccione un departamento</option>
                        </select>
                        <input type="text" id="ciudad_nueva" class="form-control mt-2 d-none" placeholder="Escriba el nombre de la ciudad" />
                        <input type="hidden" id="ciudad_hidden" name="ciudad" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- ══ PASO 2: Ubicación y contacto ══ -->
                <div class="cc-step-content" id="step2">
                  <div class="cc-card-title"><i class="bi bi-geo-alt"></i> Ubicación y contacto</div>
                  <div class="row">
                    <div class="col-sm-5">
                      <div class="form-group">
                        <label for="direccion">Dirección <small style="color:red">*</small></label>
                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ej: Calle 10 # 5-23" />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="barrio">Barrio</label>
                        <input type="text" name="barrio" id="barrio" class="form-control" placeholder="Barrio" />
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="telefono">Teléfono <small style="color:red">*</small></label>
                        <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="Ej: 601 234 5678" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- ══ PASO 3: Clasificación ══ -->
                <div class="cc-step-content" id="step3">
                  <div class="cc-card-title"><i class="bi bi-diagram-3"></i> Clasificación</div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="empresa">Empresa <small style="color:red">*</small></label>
                        <select name="empresa" id="empresa" class="form-control custom-select2">
                          <option value="">Seleccionar empresa</option>
                          <option value="1">EUREKA</option>
                          <?php
                            $req = $bdd->prepare("SELECT * FROM zonas WHERE zona NOT LIKE '%Eureka%' AND zona NOT LIKE '%ALEJANDRO%'");
                            $req->execute();
                            foreach ($req->fetchAll() as $z) {
                              echo '<option value="'.$z["codigo"].'">'.$z["zona"].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="zona">Zona <small style="color:red">*</small></label>
                        <select name="zona" id="zona" class="form-control custom-select2">
                          <option value="">Seleccionar zona</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4 col-responsable d-none">
                      <div class="form-group">
                        <label for="responsable">Responsable <small style="color:red">*</small></label>
                        <input type="text" name="responsable" id="responsable" class="form-control" placeholder="Responsable" />
                      </div>
                    </div>
                  </div>
                </div>

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
                  <li id="sr-dane"    class="d-none"><span class="cc-sl-label">DANE</span><span class="cc-sl-val" id="sv-dane">—</span></li>
                  <li id="sr-nombre"  class="d-none"><span class="cc-sl-label">Nombre</span><span class="cc-sl-val" id="sv-nombre">—</span></li>
                  <li id="sr-cal"     class="d-none"><span class="cc-sl-label">Calendario</span><span class="cc-sl-val" id="sv-cal">—</span></li>
                  <li id="sr-depto"   class="d-none"><span class="cc-sl-label">Departamento</span><span class="cc-sl-val" id="sv-depto">—</span></li>
                  <li id="sr-ciudad"  class="d-none"><span class="cc-sl-label">Ciudad</span><span class="cc-sl-val" id="sv-ciudad">—</span></li>
                  <li id="sr-dir"     class="d-none"><span class="cc-sl-label">Dirección</span><span class="cc-sl-val" id="sv-dir">—</span></li>
                  <li id="sr-tel"     class="d-none"><span class="cc-sl-label">Teléfono</span><span class="cc-sl-val" id="sv-tel">—</span></li>
                  <li id="sr-empresa" class="d-none"><span class="cc-sl-label">Empresa</span><span class="cc-sl-val" id="sv-empresa">—</span></li>
                </ul>
              </div>

              <!-- Consejos -->
              <div class="cc-card">
                <div class="cc-card-title"><i class="bi bi-lightbulb"></i> Consejos</div>
                <div class="cc-tip"><i class="bi bi-check-circle-fill"></i> El DANE debe tener 12 dígitos numéricos.</div>
                <div class="cc-tip"><i class="bi bi-check-circle-fill"></i> Selecciona la empresa y zona correspondiente para una mejor asignación.</div>
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
    var totalSteps  = 3;

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
        var dane = $('#dane').val().trim();
        if (!dane)                        { alert('El DANE es obligatorio.'); $('#dane').focus(); return false; }
        if (!/^\d{12}$/.test(dane))       { alert('El DANE debe tener exactamente 12 dígitos numéricos.'); $('#dane').focus(); return false; }
        if (!$('#colegio').val().trim())   { alert('El nombre del colegio es obligatorio.'); $('#colegio').focus(); return false; }
        if (!$('#calendario').val())       { alert('Selecciona un calendario.'); return false; }
        if (!$('#departamento').val())     { alert('Selecciona un departamento.'); return false; }
        if (!$('#ciudad_hidden').val().trim()) { alert('Selecciona o escribe una ciudad.'); return false; }
      }
      if (paso === 2) {
        if (!$('#direccion').val().trim()) { alert('La dirección es obligatoria.'); $('#direccion').focus(); return false; }
        if (!$('#telefono').val().trim())  { alert('El teléfono es obligatorio.'); $('#telefono').focus(); return false; }
      }
      if (paso === 3) {
        if (!$('#empresa').val())  { alert('Selecciona una empresa.'); return false; }
        if (!$('#zona').val())     { alert('Selecciona una zona.'); return false; }
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
      set('sr-dane',    'sv-dane',    $('#dane').val().trim());
      set('sr-nombre',  'sv-nombre',  $('#colegio').val().trim());
      set('sr-cal',     'sv-cal',     $('#calendario option:selected').text() !== 'Seleccionar calendario' ? $('#calendario option:selected').text() : '');
      set('sr-depto',   'sv-depto',   $('#departamento option:selected').text() !== 'Seleccionar departamento' ? $('#departamento option:selected').text() : '');
      set('sr-ciudad',  'sv-ciudad',  $('#ciudad_hidden').val().trim());
      set('sr-dir',     'sv-dir',     $('#direccion').val().trim());
      set('sr-tel',     'sv-tel',     $('#telefono').val().trim());
      set('sr-empresa', 'sv-empresa', $('#empresa option:selected').text() !== 'Seleccionar empresa' ? $('#empresa option:selected').text() : '');

      if (hay) {
        $('#cc-summary-empty').addClass('d-none');
        $('#cc-summary-list').removeClass('d-none');
      } else {
        $('#cc-summary-empty').removeClass('d-none');
        $('#cc-summary-list').addClass('d-none');
      }
    }

    $('input, select').on('input change', actualizarResumen);

    // Feedback en tiempo real del DANE
    $('#dane').on('input', function () {
      var val = $(this).val().replace(/\D/g, ''); // solo dígitos
      $(this).val(val); // elimina letras al escribir
      var $fb = $('#dane-feedback');
      if (val.length === 0) {
        $(this).removeClass('is-valid is-invalid');
        $fb.hide();
      } else if (val.length < 12) {
        $(this).removeClass('is-valid').addClass('is-invalid');
        $fb.show().css('color', '#dc3545').text('Faltan ' + (12 - val.length) + ' dígito(s)');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
        $fb.show().css('color', '#198754').text('DANE válido ✓');
      }
    });

    // ── Ciudad dinámica ──────────────────────────────────────────────────────
    $('#departamento').on('change', function () {
      var depto = $(this).val();
      $('#ciudad_select').html('<option value="">Cargando...</option>');
      $('#ciudad_nueva').addClass('d-none').val('').removeAttr('required');
      $('#ciudad_hidden').val('');
      if (!depto) { $('#ciudad_select').html('<option value="">Primero seleccione un departamento</option>'); return; }
      $.ajax({
        url: 'ajax/buscar_ciudades.php', type: 'POST', data: { departamento: depto },
        success: function (resp) { $('#ciudad_select').html(resp); },
        error:   function ()     { $('#ciudad_select').html('<option value="">Error al cargar ciudades</option>'); }
      });
    });

    $('#ciudad_select').on('change', function () {
      var val = $(this).val();
      if (val === '__otra__') {
        $('#ciudad_nueva').removeClass('d-none').attr('required','required').focus();
        $('#ciudad_hidden').val('');
      } else {
        $('#ciudad_nueva').addClass('d-none').val('').removeAttr('required');
        $('#ciudad_hidden').val(val);
      }
      actualizarResumen();
    });

    $('#ciudad_nueva').on('input', function () {
      $('#ciudad_hidden').val($(this).val());
      actualizarResumen();
    });

    // ── Empresa / Zona ───────────────────────────────────────────────────────
    $('#empresa').on('change', function () {
      var valor = $(this).val();
      if (valor == 1) {
        $('.col-responsable').addClass('d-none');
        $('#responsable').removeAttr('required');
      } else {
        $('.col-responsable').removeClass('d-none');
        $('#responsable').attr('required','required');
      }
      $.ajax({
        url: 'ajax/buscar_zona.php', type: 'POST', data: 'empresa=' + valor,
        success: function (resp) { $('#zona').html(valor ? resp : '<option value="">Seleccionar zona</option>'); }
      });
    });

  });
  </script>

</body>
</html>
