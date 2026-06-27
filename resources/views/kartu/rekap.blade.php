@extends('layouts.app')

@section('title', 'Rekap Mingguan')

@section('content')
<div class="page-hdr" style="margin-bottom: 20px; display: flex; flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
  <div>
    <div class="page-title" style="font-size: 20px; font-weight: 700; color: var(--text);">Rekap Mingguan</div>
    <div class="page-sub" style="font-size: 13px; color: var(--text2); margin-top: 4px;">Ringkasan operasional kartu ATM tertelan.</div>
  </div>
  
  <a href="{{ url('/rekap/excel') }}" class="btn" style="background: #1a7a45; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: var(--shadow); white-space: nowrap;">
    🟢 Unduh Format Excel (.xlsx)
  </a>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr); gap: 16px; margin-bottom: 20px;">
  <div class="stat-card teal" style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; box-shadow: var(--shadow);">
    <div class="stat-ico" style="font-size: 24px; margin-bottom: 8px;">📥</div>
    <div class="stat-val" style="font-size: 24px; font-weight: 700; color: var(--text);">{{ $stats->masuk }}</div>
    <div class="stat-lbl" style="font-size: 12px; color: var(--text2); margin-top: 4px;">Masuk minggu ini</div>
  </div>
  <div class="stat-card green" style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; box-shadow: var(--shadow);">
    <div class="stat-ico" style="font-size: 24px; margin-bottom: 8px;">🤝</div>
    <div class="stat-val" style="font-size: 24px; font-weight: 700; color: var(--text);">{{ $stats->diambil }}</div>
    <div class="stat-lbl" style="font-size: 12px; color: var(--text2); margin-top: 4px;">Diambil nasabah</div>
  </div>
  <div class="stat-card red" style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; box-shadow: var(--shadow);">
    <div class="stat-ico" style="font-size: 24px; margin-bottom: 8px;">✂️</div>
    <div class="stat-val" style="font-size: 24px; font-weight: 700; color: var(--text);">{{ $stats->dimusnahkan }}</div>
    <div class="stat-lbl" style="font-size: 12px; color: var(--text2); margin-top: 4px;">Dimusnahkan</div>
  </div>
</div>

<div class="rekap-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
  
  <div class="card" style="padding: 16px; margin-bottom: 0; background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: var(--shadow);">
    <div class="card-title" style="margin-bottom: 14px; font-weight: 700; color: var(--text);">Frekuensi per Mesin ATM</div>
    <div class="bar-chart" style="display: flex; flex-direction: column; gap: 12px;">
      @foreach($grafikAtm as $atm)
      <div class="bar-item" style="display: flex; align-items: center; gap: 12px;">
        <div class="bar-label" style="width: 80px; font-size: 12px; font-weight: 600; color: var(--text2);">{{ $atm->nama }}</div>
        <div class="bar-track" style="flex: 1; height: 8px; background: var(--bg3); border-radius: 10px; overflow: hidden;">
          <div class="bar-fill" style="height: 100%; width: {{ $atm->persentase }}%; background: {{ $atm->warna }}; border-radius: 10px;"></div>
        </div>
        <div class="bar-val" style="width: 30px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text);">{{ $atm->jumlah }}</div>
      </div>
      @endforeach
    </div>
    @if(count($grafikAtm) > 0)
    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 12px; color: var(--text2);">
      💡 <strong>{{ $grafikAtm[0]->nama }}</strong> paling sering bermasalah — pertimbangkan perawatan rutin.
    </div>
    @endif
  </div>

  <div class="card" style="padding: 16px; margin-bottom: 0; background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: var(--shadow);">
    <div class="card-title" style="margin-bottom: 14px; font-weight: 700; color: var(--text);">Rata-rata Penanganan</div>
    <div style="text-align: center; padding: 16px 0;">
      <div style="font-size: 44px; font-weight: 700; color: var(--teal); font-family: 'Courier New', monospace;">{{ $waktu->rata_rata }}</div>
      <div style="font-size: 13px; color: var(--text2); margin-top: 4px;">hari rata-rata</div>
    </div>
    <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; display: flex; flex-direction: column; gap: 8px;">
      <div style="display: flex; justify-content: space-between; font-size: 12px;">
        <span style="color: var(--text2);">Tercepat</span>
        <span style="color: var(--green); font-family: 'Courier New', monospace; font-weight: 600;">{{ $waktu->tercepat }} hari</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 12px;">
        <span style="color: var(--text2);">Terlama</span>
        <span style="color: var(--red); font-family: 'Courier New', monospace; font-weight: 600;">{{ $waktu->terlama }} hari</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 12px;">
        <span style="color: var(--text2);">Target SOP</span>
        <span style="color: var(--amber); font-family: 'Courier New', monospace; font-weight: 600;">&lt; 7 hari</span>
      </div>
    </div>
  </div>
  
</div>
@endsection