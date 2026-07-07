<x-guest-layout>
    <h2 class="fw-bold mt-3">Lupa password</h2><p class="text-muted">Masukkan email untuk menerima tautan reset.</p>
    @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
    <form method="POST" action="{{ route('password.email') }}">@csrf
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
        <button class="btn btn-ec w-100">Kirim tautan reset</button><a href="{{ route('login') }}" class="btn btn-link w-100 mt-2">Kembali ke login</a>
    </form>
</x-guest-layout>
