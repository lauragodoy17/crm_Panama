<div class="footer-wrap pd-20 mb-20 card-box d-print-none">
  Ink-pulse - CRM de Eureka Contenidos Educativos
</div>

<script>
(function () {
  var observer = new MutationObserver(function () {
    setTimeout(function () {
      $(window).trigger('resize');
      if ($.fn && $.fn.dataTable) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
      }
    }, 320);
  });
  observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
})();
</script>

<!-- Modal: Pedido de venta -->
<div class="modal fade" id="modal_pedidos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(15,23,42,.18);">

      <div style="padding:22px 24px 18px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:11px;background:linear-gradient(135deg,#1d4ed8,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="bi bi-cart-plus" style="color:#fff;font-size:1.2rem;"></i>
        </div>
        <div style="flex:1;">
          <h5 style="margin:0;font-size:.98rem;font-weight:700;color:#0f172a;">Pedido de venta</h5>
          <p style="margin:2px 0 0;font-size:.76rem;color:#64748b;">Selecciona el período para continuar</p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
          style="font-size:1.3rem;color:#94a3b8;background:none;border:none;cursor:pointer;padding:0;line-height:1;">&times;</button>
      </div>

      <form action="colegios_pedidos.php" method="POST">
        <div style="padding:24px;">
          <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:8px;">
            Período<span style="color:#ef4444;margin-left:2px;">*</span>
          </label>
          <select name="periodo" id="periodo_pedido" required
            style="width:100%;padding:10px 36px 10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:.875rem;color:#1e293b;background:#f9fafb;outline:none;
                   appearance:none;-webkit-appearance:none;font-family:inherit;
                   background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'8\' viewBox=\'0 0 12 8\'%3E%3Cpath d=\'M1 1l5 5 5-5\' stroke=\'%2364748b\' stroke-width=\'1.5\' fill=\'none\' stroke-linecap=\'round\'/%3E%3C/svg%3E');
                   background-repeat:no-repeat;background-position:right 14px center;cursor:pointer;">
            <?php
              $sql_p = "SELECT id, periodo FROM periodos ORDER BY id DESC";
              $req_p = $bdd->prepare($sql_p); $req_p->execute();
              foreach ($req_p->fetchAll() as $per):
            ?>
            <option value="<?= $per['id'] ?>"><?= htmlspecialchars($per['periodo']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="padding:0 24px 22px;display:flex;justify-content:flex-end;gap:10px;">
          <button type="button" data-dismiss="modal"
            style="padding:9px 20px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;color:#64748b;font-size:.875rem;font-weight:600;cursor:pointer;">
            Cancelar
          </button>
          <button type="submit"
            style="padding:9px 22px;border-radius:8px;background:linear-gradient(135deg,#1d4ed8,#2563eb);border:none;color:#fff;font-size:.875rem;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(29,78,216,.3);">
            <i class="bi bi-arrow-right-circle" style="margin-right:4px;"></i> Continuar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Modal: Devoluciones de venta -->
<div class="modal fade" id="modal_devols" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(15,23,42,.18);">

      <div style="padding:22px 24px 18px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:11px;background:linear-gradient(135deg,#b45309,#d97706);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="bi bi-arrow-return-left" style="color:#fff;font-size:1.2rem;"></i>
        </div>
        <div style="flex:1;">
          <h5 style="margin:0;font-size:.98rem;font-weight:700;color:#0f172a;">Devoluciones de venta</h5>
          <p style="margin:2px 0 0;font-size:.76rem;color:#64748b;">Selecciona el período para continuar</p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
          style="font-size:1.3rem;color:#94a3b8;background:none;border:none;cursor:pointer;padding:0;line-height:1;">&times;</button>
      </div>

      <form action="colegios_devols.php" method="POST">
        <div style="padding:24px;">
          <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:8px;">
            Período<span style="color:#ef4444;margin-left:2px;">*</span>
          </label>
          <select name="periodo" id="periodo_devols" required
            style="width:100%;padding:10px 36px 10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:.875rem;color:#1e293b;background:#f9fafb;outline:none;
                   appearance:none;-webkit-appearance:none;font-family:inherit;
                   background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'8\' viewBox=\'0 0 12 8\'%3E%3Cpath d=\'M1 1l5 5 5-5\' stroke=\'%2364748b\' stroke-width=\'1.5\' fill=\'none\' stroke-linecap=\'round\'/%3E%3C/svg%3E');
                   background-repeat:no-repeat;background-position:right 14px center;cursor:pointer;">
            <?php
              $sql_p2 = "SELECT id, periodo FROM periodos ORDER BY id DESC";
              $req_p2 = $bdd->prepare($sql_p2); $req_p2->execute();
              foreach ($req_p2->fetchAll() as $per2):
            ?>
            <option value="<?= $per2['id'] ?>"><?= htmlspecialchars($per2['periodo']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="padding:0 24px 22px;display:flex;justify-content:flex-end;gap:10px;">
          <button type="button" data-dismiss="modal"
            style="padding:9px 20px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;color:#64748b;font-size:.875rem;font-weight:600;cursor:pointer;">
            Cancelar
          </button>
          <button type="submit"
            style="padding:9px 22px;border-radius:8px;background:linear-gradient(135deg,#b45309,#d97706);border:none;color:#fff;font-size:.875rem;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(180,83,9,.3);">
            <i class="bi bi-arrow-right-circle" style="margin-right:4px;"></i> Continuar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
