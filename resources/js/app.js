import './bootstrap';

window.showBsToast = function (message, variant = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const el = document.createElement('div');
  el.className = `toast align-items-center text-bg-${variant} border-0`;
  el.role = 'alert';
  el.ariaLive = 'assertive';
  el.ariaAtomic = 'true';

  el.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;

  container.appendChild(el);

  const toast = new bootstrap.Toast(el, { delay: 2500 });
  toast.show();

  el.addEventListener('hidden.bs.toast', () => el.remove());
};
