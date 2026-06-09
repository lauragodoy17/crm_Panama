(function () {
  /* ── Estilos ──────────────────────────────────────────── */
  var css = `
    .ink-overlay {
      position:fixed; inset:0; background:rgba(15,23,42,.5);
      z-index:9990; display:flex; align-items:center; justify-content:center;
      animation:ink-fade .18s ease;
    }
    .ink-modal {
      background:#fff; border-radius:16px; padding:28px 28px 22px;
      width:340px; max-width:calc(100vw - 32px);
      box-shadow:0 24px 60px rgba(15,23,42,.2);
      animation:ink-up .2s ease;
    }
    .ink-modal-icon {
      width:54px; height:54px; border-radius:14px;
      display:flex; align-items:center; justify-content:center;
      font-size:1.45rem; margin:0 auto 16px;
    }
    .ink-modal-icon.danger  { background:#fee2e2; color:#dc2626; }
    .ink-modal-icon.warning { background:#fef3c7; color:#d97706; }
    .ink-modal-icon.info    { background:#dbeafe; color:#2563eb; }
    .ink-modal-icon.success { background:#dcfce7; color:#16a34a; }
    .ink-modal-title {
      font-size:1rem; font-weight:700; color:#0f172a;
      text-align:center; margin:0 0 8px;
    }
    .ink-modal-text {
      font-size:.875rem; color:#64748b; text-align:center;
      margin:0 0 22px; line-height:1.55;
    }
    .ink-modal-btns { display:flex; gap:10px; }
    .ink-modal-btns button {
      flex:1; padding:10px 0; border-radius:8px; font-size:.875rem;
      font-weight:600; border:none; cursor:pointer; transition:opacity .15s, transform .1s;
    }
    .ink-modal-btns button:hover { opacity:.88; transform:translateY(-1px); }
    .ink-btn-cancel  { background:#f1f5f9; color:#475569; }
    .ink-btn-danger  { background:#dc2626; color:#fff; }
    .ink-btn-warning { background:#d97706; color:#fff; }
    .ink-btn-info    { background:#2563eb; color:#fff; }
    .ink-btn-success { background:#16a34a; color:#fff; }

    /* ── Toast ───────────────────────────────────────────── */
    #ink-toast-wrap {
      position:fixed; top:20px; right:20px; z-index:9995;
      display:flex; flex-direction:column; gap:10px; pointer-events:none;
    }
    .ink-toast {
      display:flex; align-items:center; gap:12px;
      min-width:260px; max-width:360px;
      padding:14px 16px; border-radius:12px;
      font-size:.875rem; font-weight:500; line-height:1.4;
      box-shadow:0 8px 32px rgba(15,23,42,.14);
      pointer-events:all; animation:ink-right .3s ease;
    }
    .ink-toast.ok    { background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d; }
    .ink-toast.error { background:#fef2f2; border:1.5px solid #fecaca; color:#b91c1c; }
    .ink-toast.warn  { background:#fffbeb; border:1.5px solid #fde68a; color:#92400e; }
    .ink-toast i  { font-size:1.15rem; flex-shrink:0; }
    .ink-toast-x  { margin-left:auto; background:none; border:none; cursor:pointer;
                    color:inherit; opacity:.55; font-size:.95rem; padding:0 0 0 6px; line-height:1; }
    .ink-toast-x:hover { opacity:1; }

    @keyframes ink-fade  { from{opacity:0} to{opacity:1} }
    @keyframes ink-up    { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:none} }
    @keyframes ink-right { from{opacity:0;transform:translateX(22px)} to{opacity:1;transform:none} }
  `;
  var s = document.createElement('style');
  s.textContent = css;
  document.head.appendChild(s);

  /* ── Contenedor de toasts ─────────────────────────────── */
  var wrap = document.createElement('div');
  wrap.id = 'ink-toast-wrap';
  document.body.appendChild(wrap);

  /* ── inkToast(mensaje, tipo) ─────────────────────────── */
  window.inkToast = function (msg, type) {
    type = type || 'ok';
    var icon = { ok:'bi-check-circle-fill', error:'bi-x-circle-fill', warn:'bi-exclamation-triangle-fill' }[type] || 'bi-check-circle-fill';
    var t = document.createElement('div');
    t.className = 'ink-toast ' + type;
    t.innerHTML = '<i class="bi ' + icon + '"></i><span>' + msg + '</span>'
                + '<button class="ink-toast-x" onclick="this.parentElement.remove()">&#x2715;</button>';
    wrap.appendChild(t);
    setTimeout(function () {
      t.style.transition = 'opacity .4s';
      t.style.opacity = '0';
      setTimeout(function () { if (t.parentNode) t.remove(); }, 420);
    }, 4800);
  };

  /* ── inkConfirm(opciones, onConfirmar) ────────────────── */
  window.inkConfirm = function (opts, onOk) {
    if (typeof opts === 'string') opts = { title: opts };
    var type  = opts.type  || 'danger';
    var title = opts.title || '¿Confirmar acción?';
    var text  = opts.text  || '';
    var btnOk = opts.btnOk || 'Confirmar';
    var iconMap = { danger:'bi-trash3', warning:'bi-exclamation-triangle-fill', info:'bi-question-circle-fill', success:'bi-check-circle-fill' };

    var ov = document.createElement('div');
    ov.className = 'ink-overlay';
    ov.innerHTML =
      '<div class="ink-modal">'
      + '<div class="ink-modal-icon ' + type + '"><i class="bi ' + (iconMap[type] || iconMap.danger) + '"></i></div>'
      + '<p class="ink-modal-title">' + title + '</p>'
      + (text ? '<p class="ink-modal-text">' + text + '</p>' : '')
      + '<div class="ink-modal-btns">'
      +   '<button class="ink-btn-cancel" id="_ink_cancel">Cancelar</button>'
      +   '<button class="ink-btn-' + type + '" id="_ink_ok">' + btnOk + '</button>'
      + '</div></div>';

    document.body.appendChild(ov);
    ov.querySelector('#_ink_cancel').onclick = function () { ov.remove(); };
    ov.querySelector('#_ink_ok').onclick     = function () { ov.remove(); onOk(); };
    ov.addEventListener('click', function (e) { if (e.target === ov) ov.remove(); });
  };

  /* ── Auto-toast desde parámetros URL ─────────────────── */
  document.addEventListener('DOMContentLoaded', function () {
    var p = new URLSearchParams(window.location.search);
    var status = p.get('ink_status'), msg = p.get('ink_msg');
    if (status && msg) {
      inkToast(decodeURIComponent(msg), status);
      p.delete('ink_status'); p.delete('ink_msg');
      var qs = p.toString();
      history.replaceState(null, '', window.location.pathname + (qs ? '?' + qs : ''));
    }
  });
})();
