<form method="POST" action="{{ $action }}" data-confirm="{{ $confirm ?? 'Delete this record? This cannot be undone.' }}">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
</form>
