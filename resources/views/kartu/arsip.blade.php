@extends('layouts.app')

@section('title', 'Arsip Riwayat Kartu')

@section('content')
<div class="page-hdr" style="margin-bottom: 20px;">
  <div>
    <div class="page-title" style="font-size: 20px; font-weight: 700; color: var(--text);">Arsip Riwayat Kartu</div>
    <div class="page-sub" style="font-size: 13px; color: var(--text2); margin-top: 4px;">Rekam jejak kartu yang sudah selesai diproses.</div>
  </div>
</div>

<div class="card" style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: var(--shadow);">
  <div class="card-header" style="padding: 16px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
    <span class="card-title" style="font-weight: 700; color: var(--text);">Riwayat Selesai</span>
    <div style="display: flex; gap: 8px; align-items: center;">
      <input type="text" class="search-box" id="searchArsip" placeholder="🔍 Cari nama..." style="background: var(--bg3); border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 7px 12px; font-size: 13px; width: 180px; outline: none;" oninput="filterArsip()">
      <select class="sel-filter" id="filterStatusArsip" style="background: var(--bg3); border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 7px 10px; font-size: 12px; color: var(--text2); outline: none; cursor: pointer;" onchange="filterArsip()">
        <option value="">Semua Status</option>
        <option value="Diambil">Diambil Nasabah</option>
        <option value="Dimusnahkan">Dimusnahkan</option>
      </select>
    </div>
  </div>
  
  <div style="width: 100%; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; min-width: 560px;">
      <thead>
        <tr>
          <th style="padding: 12px 14px; font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; background: var(--bg3); border-bottom: 1px solid #e5e7eb; text-align: left;">No. Kartu</th>
          <th style="padding: 12px 14px; font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; background: var(--bg3); border-bottom: 1px solid #e5e7eb; text-align: left;">Nama Nasabah</th>
          <th style="padding: 12px 14px; font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; background: var(--bg3); border-bottom: 1px solid #e5e7eb; text-align: left;">Tgl. Masuk</th>
          <th style="padding: 12px 14px; font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; background: var(--bg3); border-bottom: 1px solid #e5e7eb; text-align: left;">Tgl. Selesai</th>
          <th style="padding: 12px 14px; font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; background: var(--bg3); border-bottom: 1px solid #e5e7eb; text-align: left;">Status Akhir</th>
          <th style="padding: 12px 14px; font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; background: var(--bg3); border-bottom: 1px solid #e5e7eb; text-align: left;">Log</th>
        </tr>
      </thead>
      <tbody>
        @foreach($arsip as $row)
          @php
            $badgeCls = $row->status_akhir === 'Diambil' ? 'var(--green-light)' : 'var(--red-light)';
            $badgeTxt = $row->status_akhir === 'Diambil' ? '#0f4a27' : '#8b1a1a';
            $dotColor = $row->status_akhir === 'Diambil' ? 'var(--green)' : 'var(--red)';
            
            // Mengambil log data dinamis terstruktur langsung dari database log_audit melalui controller
            $currentLogs = $row->custom_logs ?? [];
          @endphp
          <tr class="arsip-row" data-nama="{{ strtolower($row->nama_nasabah) }}" data-status="{{ $row->status_akhir }}" style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 14px;"><span style="font-family: 'Courier New', monospace; font-size: 13px; color: var(--text2);">{{ $row->nomor_kartu }}</span></td>
            <td style="padding: 14px;"><strong>{{ $row->nama_nasabah }}</strong></td>
            <td style="padding: 14px;"><span style="font-family: 'Courier New', monospace; font-size: 13px; color: var(--text2);">{{ $row->tanggal_masuk }}</span></td>
            <td style="padding: 14px;"><span style="font-family: 'Courier New', monospace; font-size: 13px; color: var(--text2);">{{ $row->tanggal_selesai }}</span></td>
            <td style="padding: 14px;">
              <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; background: {{ $badgeCls }}; color: {{ $badgeTxt }};">
                <span style="width: 5px; height: 5px; border-radius: 50%; background: {{ $dotColor }}; flex-shrink: 0;"></span>
                {{ $row->status_akhir }}
              </span>
            </td>
            <td style="padding: 14px;">
              <button class="btn btn-outline btn-sm" style="padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer;" 
                      onclick="showLogModal('{{ $row->nama_nasabah }}', '{{ $row->nomor_kartu }}', '{{ json_encode($currentLogs) }}')">
                📋 Log
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal-backdrop" id="modalLog" onclick="closeLogModal()" style="position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 300; display: none; align-items: center; justify-content: center; padding: 16px; backdrop-filter: blur(3px);">
  <div class="modal-box" onclick="event.stopPropagation()" style="background: white; border-radius: 14px; padding: 24px; width: 100%; max-width: 440px; box-shadow: 0 4px 20px rgba(0,0,0,0.12);">
    <div style="font-size: 16px; font-weight: 700; margin-bottom: 8px; color: var(--text);">📋 Log Audit — Kartu <span id="logNoKartu"></span></div>
    <div style="font-size: 12px; color: var(--text3); margin-bottom: 18px; font-family: 'Courier New', monospace;">Nasabah: <span id="logNama"></span> · Riwayat Sistem</div>
    
    <div id="logTimelineContainer" style="display: flex; flex-direction: column;">
    </div>
    
    <div style="margin-top: 20px; text-align: right;">
      <button class="btn btn-outline" style="padding: 8px 16px; font-size: 13px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer; font-weight: 600;" onclick="closeLogModal()">Tutup</button>
    </div>
  </div>
</div>

<script>
function filterArsip() {
  const searchVal = document.getElementById('searchArsip').value.toLowerCase();
  const statusVal = document.getElementById('filterStatusArsip').value;
  
  document.querySelectorAll('.arsip-row').forEach(row => {
    const nama = row.getAttribute('data-nama');
    const status = row.getAttribute('data-status');
    const matchSearch = nama.includes(searchVal);
    const matchStatus = statusVal === '' || status === statusVal;
    
    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
  });
}

function showLogModal(nama, noKartu, logsJson) {
  document.getElementById('logNama').textContent = nama;
  document.getElementById('logNoKartu').textContent = noKartu;
  
  const logs = JSON.parse(logsJson);
  const container = document.getElementById('logTimelineContainer');
  container.innerHTML = ''; 
  
  logs.forEach((log, index) => {
    let dotColor = 'var(--teal)';
    let statusText = `Input — Status: ${log.status}`;
    
    if (log.status === 'Dihubungi') {
      dotColor = 'var(--amber)';
      statusText = `Ubah Status → ${log.status}`;
    } else if (log.status === 'Diambil') {
      dotColor = 'var(--green)';
      statusText = `Ubah Status Selesai: ${log.status}`;
    } else if (log.status === 'Dimusnahkan') {
      dotColor = 'var(--red)';
      statusText = `Ubah Status Selesai: ${log.status}`;
    }

    const isLast = index === logs.length - 1;
    
    const itemHtml = `
      <div style="display: flex; gap: 12px; padding: 4px 0;">
        <div style="display: flex; flex-direction: column; align-items: center; padding-top: 4px;">
          <div style="width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; background: ${dotColor};"></div>
          ${!isLast ? `<div style="flex: 1; width: 1px; background: #e5e7eb; margin-top: 4px; min-height: 24px;"></div>` : ''}
        </div>
        <div style="padding-bottom: ${!isLast ? '14px' : '4px'};">
          <div style="font-size: 13px; font-weight: 600; color: var(--text);">${statusText}</div>
          <div style="font-size: 11px; color: var(--text3); margin-top: 2px; font-family: 'Courier New', monospace;">${log.tanggal} · ${log.petugas}</div>
        </div>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
  });

  document.getElementById('modalLog').style.display = 'flex';
}

function closeLogModal() {
  document.getElementById('modalLog').style.display = 'none';
}
</script>
@endsection