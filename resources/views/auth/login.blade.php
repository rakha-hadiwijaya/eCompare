<x-guest-layout>
    <h2 class="fw-bold mt-3">Selamat datang kembali</h2>
    <p class="text-muted">Masuk untuk membandingkan dan menyimpan kendaraan.</p>
    @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
    <form method="POST" action="{{ route('login') }}">@csrf
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="d-flex justify-content-between mb-3"><label><input type="checkbox" name="remember"> Ingat saya</label><a href="{{ route('password.request') }}">Lupa password?</a></div>
        <button class="btn btn-ec w-100 py-2">Masuk</button><p class="text-center mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    </form>
</x-guest-layout>
