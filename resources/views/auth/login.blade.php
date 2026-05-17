@extends('layouts.auth')

@section('title', 'Login')
@section('eyebrow', 'ADMIN PORTAL')
@section('heading', 'Selamat Datang Kembali')
@section('subheading', 'Masuk untuk mengelola data gudang Anda.')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="sigap-field">
            <label>Email</label>
            <div class="sigap-input-wrap">
                <i class="bi bi-envelope sigap-input-icon"></i>
                <input
                    type="email"
                    name="email"
                    class="sigap-input"
                    placeholder="admin@example.com"
                    value="{{ old('email') }}"
                    required
                    autofocus>
            </div>
        </div>

        <div class="sigap-field">
            <label>Password</label>
            <div class="sigap-input-wrap">
                <i class="bi bi-lock sigap-input-icon"></i>
                <input
                    type="password"
                    name="password"
                    class="sigap-input"
                    placeholder="••••••••"
                    required>
            </div>
        </div>

        <label class="sigap-check">
            <input type="checkbox" name="remember">
            <span>Ingat saya selama 30 hari</span>
        </label>

        <button type="submit" class="sigap-submit">
            <i class="bi bi-box-arrow-in-right"></i> Masuk ke Dashboard
        </button>
    </form>

    <div class="sigap-divider"><span>ATAU</span></div>

    <div class="sigap-altlink">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini →</a>
    </div>
@endsection
