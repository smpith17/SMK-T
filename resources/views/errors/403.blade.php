@extends('layouts.guest')

@section('title', '403 Akses Ditolak')

@section('content')
<div style="text-align:center; padding:20px 0;">
  <div style="font-size:50px; margin-bottom:10px;">🛑</div>
  <h1 style="font-size:24px; font-weight:700; margin-bottom:8px; color:var(--text);">Akses Ditolak</h1>
  <p style="font-size:14px; color:var(--text2); margin-bottom:24px; line-height:1.6;">
    Maaf, peran Anda tidak memiliki otorisasi untuk mengakses halaman ini. Halaman ini hanya diperuntukkan bagi <strong>Customer Service</strong> atau <strong>Admin</strong>.
  </p>
  
  <a href="{{ url('/input') }}" class="btn btn-primary">
    ← Kembali ke Halaman Input
  </a>
</div>
@endsection