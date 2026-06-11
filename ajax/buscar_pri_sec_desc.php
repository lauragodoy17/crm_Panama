<?php
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');

	$sql = "SELECT id,libro FROM libros WHERE pri_sec='".$_POST["pri_sec"]."' ORDER BY libro";
	$req = $bdd->prepare($sql);
	$req->execute();
	$libros = $req->fetchAll();

	if (empty($libros)) return;
?>
<style>
  .pri-sec-wrap {
    margin: 8px 0 14px;
    border-left: 3px solid #a5b4fc;
    background: #f5f3ff;
    border-radius: 0 10px 10px 0;
    padding: 14px 16px 8px;
  }
  .pri-sec-header {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 700;
    color: #6d28d9;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 12px;
  }
  .pri-sec-header i { font-size: 13px; }
  .pri-sec-row {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }
  .pri-sec-book-name {
    flex: 1;
    min-width: 180px;
  }
  .pri-sec-book-name > span {
    display: block;
    font-size: 11px;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 5px;
  }
  .pri-sec-book-tag {
    display: flex;
    align-items: center;
    gap: 7px;
    background: #ede9fe;
    color: #4c1d95;
    font-size: 12.5px;
    font-weight: 600;
    border-radius: 7px;
    padding: 8px 12px;
    border: 1px solid #c4b5fd;
    width: 100%;
    line-height: 1.3;
  }
  .pri-sec-book-tag i { font-size: 13px; color: #7c3aed; flex-shrink: 0; }
  .pri-sec-field-wrap {
    width: 130px;
    flex-shrink: 0;
  }
  .pri-sec-field-wrap > span {
    display: block;
    font-size: 11px;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 5px;
  }
  .pri-sec-field-wrap .form-control {
    border-radius: 7px;
    border-color: #c4b5fd;
    font-size: 13px;
  }
  .pri-sec-field-wrap .form-control:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,.12);
  }
</style>

<div class="pri-sec-wrap">
  <div class="pri-sec-header">
    <i class="bi bi-diagram-2"></i>
    Libros secundarios &nbsp;·&nbsp; <?= count($libros) ?>
  </div>

  <?php foreach($libros as $lib): ?>
  <div class="pri-sec-row">
    <div class="pri-sec-book-name">
      <span>Libro</span>
      <div class="pri-sec-book-tag">
        <i class="bi bi-book-half"></i>
        <?= htmlspecialchars($lib['libro']) ?>
      </div>
      <input type="hidden" name="pri_sec[]" value="<?= $lib['id'] ?>">
    </div>
    <div class="pri-sec-field-wrap">
      <span>Descuento % <small style="color:red;">*</small></span>
      <input type="number" class="form-control descuento_pri_sec" name="descuento_pri_sec[]" placeholder="0" min="0" max="100">
    </div>
    <div class="pri-sec-field-wrap">
      <span>Cantidad <small style="color:red;">*</small></span>
      <input type="number" class="form-control cantidad_pri_sec" name="cantidad_pri_sec[]" placeholder="0" min="0">
    </div>
  </div>
  <?php endforeach; ?>
</div>
