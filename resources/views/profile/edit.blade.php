@extends('layouts.app')
@section('title', 'Edit Profil')
@section('content')
<div class="container py-5">
    <div class="mb-4"><span class="text-uppercase small fw-bold text-ec">Akun saya</span><h1 class="h2 fw-bold mb-1">Edit Profil</h1><p class="text-muted mb-0">Kelola identitas, keamanan akun, dan preferensi kendaraan Anda.</p></div>
    <div class="row g-4">
        <aside class="col-lg-4"><div class="card p-4 text-center position-sticky" style="top:6rem">
            @if($user->avatar)<img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar {{ $user->name }}" class="rounded-circle mx-auto object-fit-cover border border-4 border-white shadow-sm" width="120" height="120">@else<div class="profile-avatar-placeholder mx-auto"><i class="bi bi-person"></i></div>@endif
            <h2 class="h4 mt-3 mb-1">{{ $user->name }}</h2><p class="text-muted text-break mb-3">{{ $user->email }}</p><span class="badge rounded-pill text-bg-success-subtle text-ec align-self-center px-3 py-2"><i class="bi bi-shield-check me-1"></i> Akun aktif</span>
        </div></aside>
        <div class="col-lg-8">
            <section class="card p-4 p-md-5 mb-4">
                <div class="d-flex gap-3 align-items-start mb-4"><span class="icon-bubble"><i class="bi bi-person-vcard"></i></span><div><h2 class="h4 mb-1">Informasi profil</h2><p class="text-muted mb-0">Perbarui nama, email, avatar, atau password Anda.</p></div></div>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row g-3">@csrf @method('PUT')
                    <div class="col-md-6"><label for="name" class="form-label fw-semibold">Nama</label><input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$user->name) }}" autocomplete="name" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="email" class="form-label fw-semibold">Email</label><input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$user->email) }}" autocomplete="email" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-12"><label for="avatar" class="form-label fw-semibold">Avatar</label><input id="avatar" type="file" accept="image/*" name="avatar" class="form-control @error('avatar') is-invalid @enderror"><div class="form-text">JPG, PNG, atau WebP. Maksimal 2 MB.</div>@error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="password" class="form-label fw-semibold">Password baru</label><input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" placeholder="Biarkan kosong jika tetap">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="password_confirmation" class="form-label fw-semibold">Konfirmasi password</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password"></div>
                    <div class="col-12 pt-2"><button class="btn btn-ec px-4"><i class="bi bi-check2 me-1"></i> Simpan profil</button></div>
                </form>
            </section>
            <section class="card p-4 p-md-5">
                <div class="d-flex gap-3 align-items-start mb-4"><span class="icon-bubble"><i class="bi bi-sliders"></i></span><div><h2 class="h4 mb-1">Preferensi kendaraan</h2><p class="text-muted mb-0">Bantu E-Compare memberi hasil yang lebih relevan.</p></div></div>
                <form method="POST" action="{{ route('profile.preference') }}" class="row g-3">@csrf @method('PUT')
                    <div class="col-md-6"><label for="favorite_vehicle_type" class="form-label fw-semibold">Tipe favorit</label><select id="favorite_vehicle_type" name="favorite_vehicle_type" class="form-select @error('favorite_vehicle_type') is-invalid @enderror"><option value="">Belum ditentukan</option><option value="car" @selected(old('favorite_vehicle_type',$user->preference?->favorite_vehicle_type)==='car')>Mobil</option><option value="motorcycle" @selected(old('favorite_vehicle_type',$user->preference?->favorite_vehicle_type)==='motorcycle')>Motor</option></select>@error('favorite_vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="favorite_brand" class="form-label fw-semibold">Merek favorit</label><input id="favorite_brand" name="favorite_brand" class="form-control @error('favorite_brand') is-invalid @enderror" value="{{ old('favorite_brand',$user->preference?->favorite_brand) }}" placeholder="Contoh: Toyota">@error('favorite_brand')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="budget_range" class="form-label fw-semibold">Batas budget</label><div class="input-group"><span class="input-group-text">Rp</span><input id="budget_range" name="budget_range" type="number" min="0" step="1000000" class="form-control @error('budget_range') is-invalid @enderror" value="{{ old('budget_range',$user->preference?->budget_range) }}"></div>@error('budget_range')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="preferred_fuel" class="form-label fw-semibold">Bahan bakar</label><select id="preferred_fuel" name="preferred_fuel" class="form-select @error('preferred_fuel') is-invalid @enderror"><option value="">Belum ditentukan</option>@foreach(['Bensin','Diesel','Hybrid','Listrik'] as $fuel)<option value="{{ $fuel }}" @selected(old('preferred_fuel',$user->preference?->preferred_fuel)===$fuel)>{{ $fuel }}</option>@endforeach</select>@error('preferred_fuel')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-12 pt-2"><button class="btn btn-ec px-4"><i class="bi bi-check2 me-1"></i> Simpan preferensi</button></div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
