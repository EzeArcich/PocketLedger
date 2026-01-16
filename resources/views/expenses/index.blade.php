@extends('layouts.app')
@section('content')
<style>
    body {
        font-family: 'Montserrat', sans-serif;
letter-spacing: .5px;
text-transform: uppercase;

    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a class="btn btn-outline-success" href="{{ route('expenses.create') }}">add +</a>
        </div>
    </div>
    <div class="row mt-2 justify-content-center">
        <div class="col-md-10">
            <table class="table">
                <thead class="table-success">
                    <tr>
                        <th>id</th>
                        <th>user_id</th>
                        <th>amount</th>
                        <th>spent_at</th>
                        <th>description</th>
                        <th>created_at</th>
                        <th>updated_at</th>
                        <th>Actions</th>
                    </tr>  
                </thead>
                <tbody>
                    @foreach ($expenses as $exp)
                    <tr>
                        <td>{{$exp->id}}</td>
                        <td>{{$exp->user->name}}</td>
                        <td>{{$exp->amount}}</td>
                        <td>{{$exp->spent_at}}</td>
                        <td>{{$exp->description}}</td>
                        <td>{{$exp->created_at}}</td>
                        <td>{{$exp->updated_at}}</td>
                        <td>
                            @can('update', $exp)
                                <a class="btn btn-outline-info" href="{{ route('expenses.edit', $exp) }}">
                                    Edit
                                </a>
                            @else
                                <span class="btn btn-outline-info invisible">Edit</span>
                            @endcan

                            {{-- Delete después lo protegemos también --}}
                            @can('delete', $exp)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger js-delete"
                                    data-url="{{ route('expenses.destroy', $exp) }}"
                                    >
                                    Eliminar
                                </button>
                            @else
                                <span class="btn btn-outline-danger invisible">Delete</span>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $expenses->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Seguro que querés eliminar este registro? Esta acción no se puede deshacer.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sí, eliminar</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('confirmDeleteModal');
  const modal = new bootstrap.Modal(modalEl);
  const confirmBtn = document.getElementById('confirmDeleteBtn');

  let deleteUrl = null;
  let triggerBtn = null;

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.js-delete');
    if (!btn) return;

    deleteUrl = btn.dataset.url;
    triggerBtn = btn;

    modal.show();
  });

  confirmBtn.addEventListener('click', async () => {
    if (!deleteUrl) return;

    confirmBtn.disabled = true;

    try {
      const res = await fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        }
      });

      if (!res.ok) {
        // si tu backend devuelve JSON con message, lo leés
        let msg = 'No se pudo eliminar.';
        try {
          const data = await res.json();
          if (data?.message) msg = data.message;
        } catch (_) {}
        window.showBsToast(msg, 'secondary');
        return;
      }

      // ✅ éxito
      window.showBsToast('Eliminado correctamente', 'danger');

      // opcional: sacás el item del DOM
      // si tu botón está dentro de una fila:
      const row = triggerBtn.closest('tr');
      if (row) row.remove();

      modal.hide();
    } catch (err) {
      window.showBsToast('Error de red. Intentá de nuevo.', 'secondary');
    } finally {
      confirmBtn.disabled = false;
      deleteUrl = null;
      triggerBtn = null;
    }
  });

  // si el usuario cierra el modal, limpiamos
  modalEl.addEventListener('hidden.bs.modal', () => {
    deleteUrl = null;
    triggerBtn = null;
    confirmBtn.disabled = false;
  });
});
</script>
@endpush


@endsection