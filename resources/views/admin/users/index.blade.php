@extends('layouts.admin')

@section('title', 'Pengguna')

@section('admin-content')
<h1 class="fw-bold">Kelola Pengguna</h1>
<p class="text-muted">Atur role dan status akun.</p>

<div class="card overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php($updateFormId = 'update-user-'.$user->id)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong><br>
                            <small class="text-muted">{{ $user->email }}</small>
                            <form id="{{ $updateFormId }}" method="POST" action="{{ route('admin.users.update', $user) }}">
                                @csrf
                                @method('PUT')
                            </form>
                        </td>
                        <td>
                            <select name="role_id" form="{{ $updateFormId }}" class="form-select form-select-sm" aria-label="Role {{ $user->name }}">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected($user->role_id === $role->id)>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="is_active" form="{{ $updateFormId }}" class="form-select form-select-sm" aria-label="Status {{ $user->name }}">
                                <option value="1" @selected($user->is_active)>Aktif</option>
                                <option value="0" @selected(! $user->is_active)>Nonaktif</option>
                            </select>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="submit" form="{{ $updateFormId }}" class="btn btn-sm btn-outline-success" title="Simpan perubahan">
                                    <i class="bi bi-check2"></i>
                                </button>
                                <form class="confirm-delete" method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus pengguna" @disabled($user->id === auth()->id())>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
