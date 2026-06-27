@extends('layouts.app')

@section('title', 'Input Kartu Tertelan')

@section('content')
<div class="page-hdr">
  <div>
    <div class="page-title">Input Kartu Tertelan</div>
    <div class="page-sub">Isi formulir setelah menemukan kartu ATM yang tertelan.</div>
  </div>
</div>

@if(session('success'))
<div class="alert-banner alert-success">
  <span class="ico">✅</span>
  <div class="txt"><strong>Data berhasil disimpan!</strong> Countdown 7 hari dimulai otomatis.</div>
</div>
@endif

<div class="form-card">
  <form action="{{ url('/input') }}" method="POST">
    @csrf

    <div class="form-section">Data Nasabah</div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">No. Kartu (4 digit terakhir) <span style="color:var(--red)">*</span></label>
        <input class="form-input {{ $errors->has('nomor_kartu') ? 'error' : '' }}"
               name="nomor_kartu"
               value="{{ old('nomor_kartu') }}"
               placeholder="cth: 1234" maxlength="4"
               inputmode="numeric" pattern="[0-9]{4}"
               oninput="this.value=this.value.replace(/\D/g,'')"
               required>
        @error('nomor_kartu')
          <div class="err-msg show">{{ $message }}</div>
        @else
          <div class="form-hint">Lihat 4 angka terakhir di kartu fisik</div>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Nama Nasabah <span style="color:var(--red)">*</span></label>
        <input class="form-input {{ $errors->has('nama_nasabah') ? 'error' : '' }}"
               name="nama_nasabah"
               value="{{ old('nama_nasabah') }}"
               placeholder="Nama lengkap pemilik kartu" required>
        @error('nama_nasabah')
          <div class="err-msg show">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="form-divider"></div>
    <div class="form-section">Lokasi Kejadian</div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Kode / Nama Mesin ATM <span style="color:var(--red)">*</span></label>
        <input class="form-input {{ $errors->has('lokasi_atm') ? 'error' : '' }}"
               name="lokasi_atm"
               value="{{ old('lokasi_atm') }}"
               placeholder="cth: ATM-PLZ-01" required>
        @error('lokasi_atm')
          <div class="err-msg show">{{ $message }}</div>
        @else
          <div class="form-hint">Lihat kode di stiker mesin ATM</div>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Lokasi Penyimpanan <span style="color:var(--red)">*</span></label>
        <select class="form-select {{ $errors->has('lokasi_simpan') ? 'error' : '' }}"
                name="lokasi_simpan" required>
          <option value="">-- Pilih lokasi simpan --</option>
          <option value="Kantor Pusat" {{ old('lokasi_simpan') == 'Kantor Pusat' ? 'selected' : '' }}>Kantor Pusat</option>
          <option value="Cabang" {{ old('lokasi_simpan') == 'Cabang' ? 'selected' : '' }}>Cabang</option>
          <option value="Capem" {{ old('lokasi_simpan') == 'Capem' ? 'selected' : '' }}>Capem</option>
        </select>
        @error('lokasi_simpan')
          <div class="err-msg show">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="preview-box">
      <div class="preview-label">DIISI OTOMATIS OLEH SISTEM</div>
      <div class="preview-row">
        <div class="preview-item">📅 Tgl masuk: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</strong></div>
        <div class="preview-item">⏰ Batas: <strong>{{ \Carbon\Carbon::now()->addDays(7)->translatedFormat('d M Y') }}</strong></div>
        <div class="preview-item">🔵 Status: <strong>Disimpan</strong></div>
      </div>
    </div>

    <div style="display:flex;gap:10px">
      <button type="submit" class="btn btn-primary" style="flex:1">💾 Simpan Data Kartu</button>
      <button type="reset" class="btn btn-outline">Reset</button>
    </div>
  </form>
</div>
@endsection