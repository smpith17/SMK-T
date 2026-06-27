@extends('layouts.guest')

@section('title', 'Login - SMK-T')

@section('content')
<div class="login-brand">
  <div class="brand-icon">💳</div>
  <div>
    <div class="brand-title">SMK-T</div>
    <div class="brand-sub">Sistem Monitoring Kartu Tertelan</div>
  </div>
</div>

<p class="login-greeting">Masuk sesuai peran Anda untuk mengakses sistem.</p>

<div style="margin-bottom:16px">
  <div class="form-label">DEMO — PILIH PERAN</div>
  <div class="role-tabs">
    <button class="role-tab active" onclick="setRole('satpam',this)">Satpam</button>
    <button class="role-tab" onclick="setRole('cs',this)">Customer Service</button>
    <button class="role-tab" onclick="setRole('admin',this)">Admin</button>
  </div>
</div>

<form action="{{ url('/login') }}" method="POST">
  @csrf
  <div class="form-group">
    <label class="form-label">USERNAME</label>
    <input class="form-input {{ $errors->has('username') ? 'error' : '' }}"
           id="loginUser" name="username"
           value="{{ old('username', 'satpam_budi') }}"
           placeholder="Masukkan username" required>
    @error('username')
      <div class="err-msg">{{ $message }}</div>
    @enderror
  </div>
  <div class="form-group">
    <label class="form-label">PASSWORD</label>
    <input class="form-input" type="password" name="password"
           placeholder="Masukkan password" required>
  </div>
  <button type="submit" class="btn btn-primary btn-block">Masuk ke Sistem →</button>
</form>

<p style="text-align:center;margin-top:14px;font-size:11px;color:var(--text3)">
  Sistem internal · Hanya untuk petugas bank
</p>
@endsection

@section('scripts')
<script>
const roles = {
  satpam: { username: 'satpam_budi' },
  cs:     { username: 'cs_siti' },
  admin:  { username: 'admin_rian' }
};
function setRole(roleName, el) {
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('loginUser').value = roles[roleName].username;
}
</script>
@endsection