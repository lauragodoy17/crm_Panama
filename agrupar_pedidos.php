<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$promos   = [];
$can_pick = ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 2 || $_SESSION['tipo'] == 7);

if ($can_pick) {
  $req = $bdd->prepare("SELECT id, CONCAT(nombres, ' ', apellidos) AS promotor FROM usuarios WHERE (tipo=3 OR tipo=6) AND act=1 ORDER BY nombres ASC");
  $req->execute();
  $promos = $req->fetchAll();
}

$req_p = $bdd->prepare("SELECT id, periodo FROM periodos ORDER BY id DESC");
$req_p->execute();
$periodos = $req_p->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Agrupar pedidos</title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <style>
    .ag-form-card {
      background: #fff; border-radius: 14px;
      box-shadow: 0 2px 12px rgba(15,23,42,.09);
      overflow: hidden; margin-bottom: 30px;
    }
    .ag-form-head {
      display: flex; align-items: center; gap: 14px;
      padding: 20px 24px 18px; border-bottom: 1px solid #e2e8f0;
    }
    .ag-form-icon {
      width: 46px; height: 46px; border-radius: 12px;
      background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.25rem; color: #fff; flex-shrink: 0;
    }
    .ag-form-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; }
    .ag-form-sub   { font-size: .78rem; color: #64748b; margin: 2px 0 0; }
    .ag-form-body  { padding: 24px 28px 28px; }
    .ag-row {
      display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap;
    }
    .ag-field { flex: 1 1 220px; min-width: 0; }
    .ag-field-btn { flex: 0 0 auto; padding-bottom: 0; }
    .ag-field-label {
      font-size: .75rem; font-weight: 700; color: #374151;
      text-transform: uppercase; letter-spacing: .05em;
      display: flex; align-items: center; gap: 6px;
      margin: 0 0 8px;
    }
    .ag-field-label i { color: #2563eb; }
    .ag-select {
      width: 100%; padding: 10px 36px 10px 14px; border-radius: 8px;
      border: 1.5px solid #d1d5db; font-size: .9rem;
      background: #f9fafb; color: #0f172a;
      outline: none; transition: border-color .15s, background .15s;
      appearance: none; -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2364748b' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
    }
    .ag-select:focus { border-color: #2563eb; background: #fff; }
    .ag-btn {
      padding: 10px 28px; border-radius: 8px; font-size: .95rem; font-weight: 700;
      background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
      color: #fff; border: none; cursor: pointer; white-space: nowrap;
      display: inline-flex; align-items: center; gap: 8px;
      transition: opacity .15s, transform .1s;
    }
    .ag-btn:hover { opacity: .9; transform: translateY(-1px); }
  </style>
</head>
<body>

<?php include("template/nav_side.php"); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-sm-12">
            <div class="title"><h4>Agrupar pedidos de venta</h4></div>
          </div>
        </div>
      </div>

      <div class="ag-form-card">
        <div class="ag-form-head">
          <div class="ag-form-icon"><i class="bi bi-layers"></i></div>
          <div>
            <p class="ag-form-title">Agrupar pedidos</p>
            <p class="ag-form-sub">Selecciona los parámetros para buscar los pedidos a agrupar</p>
          </div>
        </div>
        <div class="ag-form-body">
          <form id="valoriza" action="pedidos_agrupar.php" method="POST">
            <div class="ag-row">

              <?php if ($can_pick): ?>
              <div class="ag-field">
                <label class="ag-field-label" for="promotor">
                  <i class="bi bi-person"></i> Usuario / Promotor
                </label>
                <select name="promotor" id="promotor" class="ag-select" required>
                  <option value="">— Seleccionar —</option>
                  <?php foreach ($promos as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['promotor']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php endif; ?>

              <div class="ag-field">
                <label class="ag-field-label" for="periodo">
                  <i class="bi bi-calendar3"></i> Período
                </label>
                <select name="periodo" id="periodo" class="ag-select">
                  <?php foreach ($periodos as $per): ?>
                    <option value="<?= $per['id'] ?>"><?= htmlspecialchars($per['periodo']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="ag-field-btn">
                <button type="submit" class="ag-btn">
                  <i class="bi bi-search"></i> Buscar pedidos
                </button>
              </div>

            </div>
          </form>
        </div>
      </div>

    </div>
    <?php include("template/footer.php"); ?>
  </div>
</div>

<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>
