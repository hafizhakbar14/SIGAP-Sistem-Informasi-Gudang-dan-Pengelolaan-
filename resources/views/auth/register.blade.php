@extends('layouts.auth')

@section('title', 'Register')
@section('eyebrow', 'ADMIN PORTAL')
@section('heading', 'Buat Akun Admin')
@section('subheading', 'Daftarkan akun untuk mengelola gudang.')

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="sigap-field">
            <label>Nama Lengkap</label>
            <div class="sigap-input-wrap">
                <i class="bi bi-person sigap-input-icon"></i>
                <input
                    type="text"
                    name="name"
                    class="sigap-input"
                    placeholder="Nama Admin"
                    value="{{ old('name') }}"
                    required
                    autofocus>
            </div>
        </div>

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
                    required>
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
                    placeholder="Minimal 6 karakter"
                    required>
            </div>
        </div>

        <div class="sigap-field">
            <label>Konfirmasi Password</label>
            <div class="sigap-input-wrap">
                <i class="bi bi-lock-fill sigap-input-icon"></i>
                <input
                    type="password"
                    name="password_confirmation"
                    class="sigap-input"
                    placeholder="Ulangi password"
                    required>
            </div>
        </div>

        <button type="submit" class="sigap-submit">
            <i class="bi bi-person-plus"></i> Daftar Akun
        </button>
    </form>

    <div class="sigap-divider"><span>ATAU</span></div>

    <div class="sigap-altlink">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini →</a>
    </div>
@endsection
