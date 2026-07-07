@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')

@if(isset($kritisCount) && $kritisCount > 0)
<div class="alert-banner" id="alertBanner">
  <span class="ico">🚨</span>
  <div class="txt">
    <strong>{{ $kritisCount }} kartu memerlukan tindakan segera</strong> — batas waktu sudah terlewati atau hampir habis.
  </div>
</div>
@endif

<div class="stats-grid">
  <div class="stat-card teal">
    <div class="stat-ico">💳</div>
    <div class="stat-val">{{ $kartu->total() }}</div>
    <div class="stat-lbl">Kartu aktif</div>
  </div>
  <div class="stat-card amber">
    <div class="stat-ico">⏳</div>
    <div class="stat-val">{{ $kartu->where('status', 'Disimpan')->count() }}</div>
    <div class="stat-lbl">Belum dihubungi</div>
  </div>
  <div class="stat-card red">
    <div class="stat-ico">🔴</div>
    <div class="stat-val">{{ $kritisCount ?? 0 }}</div>
    <div class="stat-lbl">Mendekati batas</div>
  </div>
  <div class="stat-card green">
    <div class="stat-ico">✅</div>
    <div class="stat-val">{{ $selesaiBulanIni ?? 0 }}</div>
    <div class="stat-lbl">Selesai bulan ini</div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">Daftar Kartu Aktif</span>
    <input class="search-box" placeholder="🔍 Cari nama (halaman ini)..." id="searchDash" oninput="filterDashboard()">
    <select class="sel-filter" id="filterDash" onchange="filterDashboard()">
      <option value="">Semua Status</option>
      <option value="Disimpan">Disimpan</option>
      <option value="Dihubungi">Dihubungi</option>
    </select>
  </div>

  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>No. Kartu</th>
          <th>Nama Nasabah</th>
          <th>Lokasi ATM</th>
          <th>Simpan Di</th>
          <th>Sisa Waktu</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($kartu as $item)
          @php
            $rowCls = '';
            $sisaCls = 'safe';
            $sisaLabel = '✓ ' . $item->sisa_hari . ' hari';
            if ($item->sisa_hari <= 0) {
              $rowCls = 'row-d'; $sisaCls = 'dang'; $sisaLabel = '⚠ Lewat batas';
            } elseif ($item->sisa_hari <= 2) {
              $rowCls = 'row-w'; $sisaCls = 'warn'; $sisaLabel = '⏳ ' . $item->sisa_hari . ' hari';
            }
            $badgeCls = $item->status === 'Disimpan' ? 'b-teal' : 'b-amber';
            $dotClr   = $item->status === 'Disimpan' ? 'var(--teal)' : 'var(--amber)';
          @endphp
          <tr class="dash-row {{ $rowCls }}"
              data-nama="{{ strtolower($item->nama_nasabah) }}"
              data-status="{{ $item->status }}">
            <td><span class="mono">{{ $item->nomor_kartu }}</span></td>
            <td><strong>{{ $item->nama_nasabah }}</strong></td>
            <td><span class="tag-chip">{{ $item->lokasi_atm }}</span></td>
            <td>{{ $item->lokasi_simpan }}</td>
            <td><span class="cdown {{ $sisaCls }}">{{ $sisaLabel }}</span></td>
            <td>
              <span class="badge {{ $badgeCls }}">
                <span class="bdot" style="background:{{ $dotClr }}"></span>
                {{ $item->status }}
              </span>
            </td>
            <td>
              <div class="dropdown">
                <button class="btn btn-outline btn-sm" onclick="toggleDD(this)">Aksi ▾</button>
                <div class="dropdown-menu">
                  <div class="dd-item" onclick="showModal('hubungi','{{ $item->nama_nasabah }}','{{ $item->id }}')">📞 Tandai Dihubungi</div>
                  <div class="dd-item" onclick="showModal('diambil','{{ $item->nama_nasabah }}','{{ $item->id }}')">✅ Kartu Diambil</div>
                  <div class="dd-divider"></div>
                  <div class="dd-item danger" onclick="showModal('musnahkan','{{ $item->nama_nasabah }}','{{ $item->id }}')">🗑 Musnahkan</div>
                </div>
              </div>
            </td>
          </tr>
        @endforeach

        @if(count($kartu) == 0)
        <tr>
          <td colspan="7" style="text-align:center;padding:30px;color:var(--text3)">
            Semua kartu telah diselesaikan.
          </td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
  
  <div style="padding: 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; overflow-x: auto;">
    {{ $kartu->links() }}
  </div>

</div>

<form id="formAksi" method="POST" style="display:none">
  @csrf
  <input type="hidden" name="status" id="inputStatus">
</form>

<div class="modal-backdrop" id="modalMain" onclick="closeModal()">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-title" id="mTitle">Konfirmasi</div>
    <div class="modal-body" id="mBody">Apakah Anda yakin?</div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal()">Batal</button>
      <button class="btn btn-primary" id="mConfirm" onclick="confirmModal()">Konfirmasi</button>
    </div>
  </div>
</div>

<script>
let selectedId = '';
let selectedStatus = '';

function toggleDD(btn) {
  const menu = btn.nextElementSibling;

  document.querySelectorAll('.dropdown-menu.open').forEach(m => {
    if (m !== menu) {
      m.classList.remove('open');
      m.removeAttribute('style');
    }
  });

  if (menu.classList.contains('open')) {
    menu.classList.remove('open');
    menu.removeAttribute('style');
  } else {
    const rect = btn.getBoundingClientRect();
    menu.style.cssText = `
      position: fixed;
      top: ${rect.bottom + 4}px;
      right: ${window.innerWidth - rect.right}px;
      left: auto;
      z-index: 9999;
    `;
    menu.classList.add('open');
  }

  event.stopPropagation();
}

document.addEventListener('click', () => {
  document.querySelectorAll('.dropdown-menu.open').forEach(m => {
    m.classList.remove('open');
    m.removeAttribute('style');
  });
});

function showModal(type, name, id) {
  document.querySelectorAll('.dropdown-menu.open').forEach(m => {
    m.classList.remove('open');
    m.removeAttribute('style');
  });
  selectedId = id;
  const cfg = {
    hubungi:   { status:'Dihubungi',   title:'📞 Tandai Sudah Dihubungi',   body:`Tandai kartu milik <strong>${name}</strong> sudah dihubungi? Perubahan akan dicatat di log audit.`,                                                                 btn:'Konfirmasi',         btnCls:'btn btn-primary' },
    diambil:   { status:'Diambil',     title:'✅ Kartu Diambil Nasabah',    body:`Konfirmasi kartu milik <strong>${name}</strong> sudah diambil setelah verifikasi identitas?`,                                                                         btn:'Konfirmasi Diambil', btnCls:'btn btn-primary' },
    musnahkan: { status:'Dimusnahkan', title:'⚠️ Konfirmasi Pemusnahan',    body:`<span style="color:var(--red)">Apakah Anda yakin memusnahkan kartu <strong>${name}</strong>? Tindakan ini <strong>tidak dapat dibatalkan</strong>.</span>`,               btn:'Musnahkan Kartu',    btnCls:'btn btn-danger'  },
  };
  const c = cfg[type];
  selectedStatus = c.status;
  document.getElementById('mTitle').textContent = c.title;
  document.getElementById('mBody').innerHTML = c.body;
  const btn = document.getElementById('mConfirm');
  btn.textContent = c.btn;
  btn.className = c.btnCls;
  document.getElementById('modalMain').classList.add('open');
}

function closeModal() {
  document.getElementById('modalMain').classList.remove('open');
}

function confirmModal() {
  document.getElementById('inputStatus').value = selectedStatus;
  const form = document.getElementById('formAksi');
  form.action = `/kartu/${selectedId}/status`;
  form.submit();
}

function filterDashboard() {
  const search = document.getElementById('searchDash').value.toLowerCase();
  const status = document.getElementById('filterDash').value;
  document.querySelectorAll('.dash-row').forEach(row => {
    const match = row.getAttribute('data-nama').includes(search)
      && (status === '' || row.getAttribute('data-status') === status);
    row.style.display = match ? '' : 'none';
  });
}
</script>
@endsection