/* ==========================================================================
   SUMAS — Dashboard shell interactions (Admin + Student)
   ========================================================================== */
document.addEventListener('DOMContentLoaded', function () {

  var sidebar = document.querySelector('.dash-sidebar');
  var overlay = document.querySelector('.sidebar-overlay');
  var toggleBtns = document.querySelectorAll('.sidebar-toggle');

  function isMobile() { return window.innerWidth < 992; }

  toggleBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!sidebar) return;
      if (isMobile()) {
        sidebar.classList.toggle('show');
        if (overlay) overlay.classList.toggle('show');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });
  });

  if (overlay) {
    overlay.addEventListener('click', function () {
      sidebar.classList.remove('show');
      overlay.classList.remove('show');
    });
  }

  /* Highlight active nav link based on current file name */
  var current = window.location.pathname.split('/').pop() || 'dashboard.html';
  document.querySelectorAll('.dash-sidebar .nav-link[href]').forEach(function (link) {
    var href = link.getAttribute('href').split('/').pop();
    if (href === current) link.classList.add('active');
  });

  /* Live clock in topbar (if present) */
  var clockEl = document.getElementById('liveClock');
  if (clockEl) {
    function updateClock() {
      var now = new Date();
      clockEl.textContent = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
    }
    updateClock();
    setInterval(updateClock, 60000);
  }

  /* Generic delete confirmation modal trigger */
  document.querySelectorAll('[data-delete-target]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var nameEl = document.getElementById('deleteItemName');
      if (nameEl) nameEl.textContent = btn.getAttribute('data-item-name') || 'this item';
    });
  });

  /* Toast launcher helper: any element with data-toast="success|warning|danger" */
  document.querySelectorAll('[data-toast]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var type = btn.getAttribute('data-toast');
      var msg = btn.getAttribute('data-toast-msg') || 'Action completed successfully.';
      showToast(type, msg);
    });
  });

  window.showToast = function (type, message) {
    var container = document.getElementById('toastContainer');
    if (!container) return;
    var icons = { success: 'fa-circle-check', warning: 'fa-triangle-exclamation', danger: 'fa-circle-xmark', info: 'fa-circle-info' };
    var colors = { success: 'var(--sumas-success)', warning: 'var(--sumas-warning)', danger: 'var(--sumas-danger)', info: 'var(--sumas-info)' };
    var titles = { success: 'Success', warning: 'Warning', danger: 'Error', info: 'Notice' };
    var el = document.createElement('div');
    el.className = 'toast align-items-center border-0';
    el.setAttribute('role', 'alert');
    el.innerHTML =
      '<div class="d-flex">' +
        '<div class="toast-body d-flex align-items-center gap-2">' +
          '<i class="fa-solid ' + (icons[type] || icons.info) + '" style="color:' + (colors[type] || colors.info) + '"></i>' +
          '<span><strong>' + (titles[type] || titles.info) + ':</strong> ' + message + '</span>' +
        '</div>' +
        '<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>' +
      '</div>';
    container.appendChild(el);
    var toast = new bootstrap.Toast(el, { delay: 4000 });
    toast.show();
    el.addEventListener('hidden.bs.toast', function () { el.remove(); });
  };

  /* Mark all notifications read */
  var markAllBtn = document.getElementById('markAllRead');
  if (markAllBtn) {
    markAllBtn.addEventListener('click', function () {
      document.querySelectorAll('.notif-item.unread').forEach(function (n) { n.classList.remove('unread'); });
      var countBadge = document.getElementById('notifCount');
      if (countBadge) countBadge.remove();
      showToast('success', 'All notifications marked as read.');
    });
  }

  /* Build a lightweight mini calendar for the current month */
  var calEl = document.getElementById('miniCalendar');
  if (calEl) {
    var now = new Date();
    var year = now.getFullYear(), month = now.getMonth();
    var firstDay = new Date(year, month, 1).getDay();
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var eventDays = [5, 14, 22]; // sample highlighted days
    var html = '<table class="mini-cal"><thead><tr>' +
      ['S','M','T','W','T','F','S'].map(function(d){return '<th>'+d+'</th>';}).join('') +
      '</tr></thead><tbody><tr>';
    for (var i = 0; i < firstDay; i++) html += '<td></td>';
    var col = firstDay;
    for (var d = 1; d <= daysInMonth; d++) {
      var cls = d === now.getDate() ? 'today' : (eventDays.indexOf(d) > -1 ? 'event' : '');
      html += '<td><span class="' + cls + '">' + d + '</span></td>';
      col++;
      if (col % 7 === 0 && d !== daysInMonth) html += '</tr><tr>';
    }
    html += '</tr></tbody></table>';
    calEl.innerHTML = html;
  }

});
