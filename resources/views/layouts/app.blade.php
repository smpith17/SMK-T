<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') — SMK-T</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
:root{
  --bg:#f5f6fa;--bg2:#ffffff;--bg3:#f0f1f5;
  --text:#1a1d2e;--text2:#6b7280;--text3:#9ca3af;
  --border:#e5e7eb;--border2:#d1d5db;
  --teal:#0f9e74;--teal2:#0d8a64;--teal-light:#e6f7f2;--teal-dim:rgba(15,158,116,0.1);
  --blue:#1a6fbf;--blue-light:#e8f1fb;
  --amber:#c47b00;--amber-light:#fff3d0;
  --red:#dc3545;--red-light:#fde8ea;
  --green:#1a7a45;--green-light:#e6f4ec;
  --purple:#5b3fa6;--purple-light:#ede9fc;
  --gray-light:#f8f9fa;
  --shadow:0 1px 4px rgba(0,0,0,0.08);
  --shadow2:0 4px 20px rgba(0,0,0,0.12);
  --radius:10px;--radius-lg:14px;
  --font:'Segoe UI',system-ui,sans-serif;
}
body{font-family:var(--font);background:var(--bg);color:var(--text);font-size:14px;min-height:100vh;overflow-x:hidden}

/* === BUTTONS === */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 18px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;border:1.5px solid transparent;font-family:var(--font);transition:all .15s;text-decoration:none;white-space:nowrap}
.btn-primary{background:var(--teal);color:#fff;border-color:var(--teal)}
.btn-primary:hover{background:var(--teal2);border-color:var(--teal2)}
.btn-outline{background:transparent;color:var(--text);border-color:var(--border2)}
.btn-outline:hover{background:var(--bg3)}
.btn-danger{background:var(--red-light);color:var(--red);border-color:rgba(220,53,69,.2)}
.btn-danger:hover{background:rgba(220,53,69,.15)}
.btn-sm{padding:7px 12px;font-size:12px;gap:5px}
.btn-icon{width:36px;height:36px;padding:0;border-radius:8px;background:transparent;border:1.5px solid var(--border);color:var(--text2);font-size:16px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center}
.btn-icon:hover{background:var(--bg3);color:var(--text)}

/* === FORM === */
.form-group{margin-bottom:14px}
.form-label{font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;letter-spacing:.3px}
.form-input,.form-select{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:11px 14px;font-size:14px;font-family:var(--font);color:var(--text);background:var(--bg2);transition:border .15s;outline:none;-webkit-appearance:none}
.form-input:focus,.form-select:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-dim)}
.form-input.error,.form-select.error{border-color:var(--red)}
.form-card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;max-width:600px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-hint{font-size:11px;color:var(--text3);margin-top:4px}
.form-divider{height:1px;background:var(--border);margin:18px 0}
.form-section{font-size:11px;font-weight:600;color:var(--text3);letter-spacing:.8px;text-transform:uppercase;margin-bottom:12px}
.preview-box{background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin-bottom:16px}
.preview-label{font-size:10px;color:var(--text3);letter-spacing:.5px;margin-bottom:6px}
.preview-row{display:flex;gap:16px;flex-wrap:wrap}
.preview-item{font-size:12px;color:var(--text2)}
.preview-item strong{color:var(--teal)}
.err-msg{font-size:11px;color:var(--red);margin-top:4px;display:none}
.err-msg.show{display:block}

/* === LAYOUT === */
#appWrapper{display:flex;height:100vh;overflow:hidden}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:190}
.sidebar-overlay.open{display:block}
.sidebar{width:220px;background:var(--bg2);border-right:1px solid var(--border);flex-direction:column;display:flex;flex-shrink:0;transition:transform .25s;z-index:200}
.sidebar-head{padding:16px 14px 12px;border-bottom:1px solid var(--border)}
.s-brand{display:flex;align-items:center;gap:9px}
.s-icon{width:32px;height:32px;background:var(--teal);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.s-title{font-size:14px;font-weight:700}
.s-sub{font-size:10px;color:var(--text3);letter-spacing:.5px}
.s-nav{flex:1;padding:10px 8px;overflow-y:auto}
.s-section{font-size:10px;font-weight:600;color:var(--text3);letter-spacing:.8px;padding:8px 8px 4px;text-transform:uppercase}
.s-item{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;color:var(--text2);font-size:13px;font-weight:500;margin-bottom:1px;border:1.5px solid transparent;transition:all .15s;text-decoration:none;cursor:pointer}
.s-item:hover{background:var(--bg3);color:var(--text)}
.s-item.active{background:var(--teal-dim);color:var(--teal);border-color:rgba(15,158,116,.15)}
.s-item .ico{font-size:16px;width:18px;text-align:center;flex-shrink:0}
.s-item .s-badge{margin-left:auto;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px}
.s-foot{padding:10px 8px;border-top:1px solid var(--border)}
.s-user{display:flex;align-items:center;gap:9px;padding:8px;border-radius:8px;text-decoration:none;color:inherit}
.s-user:hover{background:var(--bg3)}
.s-avatar{width:32px;height:32px;border-radius:50%;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.s-uname{font-size:12px;font-weight:600}
.s-urole{font-size:10px;color:var(--text3)}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0}
.topbar{height:52px;background:var(--bg2);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 16px;gap:12px;flex-shrink:0}
.topbar-menu{display:none;background:none;border:none;font-size:22px;cursor:pointer;color:var(--text);padding:4px}
.topbar-title{font-size:15px;font-weight:700;flex:1}
.topbar-right{display:flex;align-items:center;gap:8px}
.topbar-date{font-size:11px;color:var(--text3)}
.content{flex:1;overflow-y:auto;padding:20px 16px}

/* === PAGE ELEMENTS === */
.page-hdr{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;gap:12px}
.page-title{font-size:18px;font-weight:700;margin-bottom:2px}
.page-sub{font-size:13px;color:var(--text2)}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:12px;margin-bottom:18px}
.stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.stat-card.teal::before{background:var(--teal)}
.stat-card.amber::before{background:var(--amber)}
.stat-card.red::before{background:var(--red)}
.stat-card.green::before{background:var(--green)}
.stat-ico{font-size:20px;margin-bottom:8px}
.stat-val{font-size:26px;font-weight:700;line-height:1;color:var(--text)}
.stat-lbl{font-size:11px;color:var(--text2);margin-top:3px}
.alert-banner{background:var(--red-light);border:1px solid rgba(220,53,69,.2);border-radius:var(--radius);padding:12px 14px;display:flex;align-items:center;gap:10px;margin-bottom:16px;font-size:13px}
.alert-banner .ico{font-size:18px;flex-shrink:0}
.alert-banner .txt{flex:1;color:var(--text)}
.alert-banner strong{color:var(--red)}
.alert-success{background:var(--green-light);border-color:rgba(26,122,69,.2)}
.alert-success strong{color:var(--green)}
.card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;margin-bottom:16px}
.card-header{padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.card-title{font-size:14px;font-weight:700;flex:1}
.search-box{background:var(--bg3);border:1.5px solid var(--border);border-radius:8px;padding:7px 12px;font-size:13px;font-family:var(--font);color:var(--text);outline:none;width:180px;transition:border .15s}
.search-box:focus{border-color:var(--teal)}
.sel-filter{background:var(--bg3);border:1.5px solid var(--border);border-radius:8px;padding:7px 10px;font-size:12px;color:var(--text2);font-family:var(--font);cursor:pointer;outline:none;-webkit-appearance:none}

/* === TABLE === */
.tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
table{width:100%;border-collapse:collapse;min-width:560px}
th{padding:10px 14px;font-size:11px;font-weight:600;color:var(--text3);letter-spacing:.6px;text-transform:uppercase;background:var(--bg3);border-bottom:1px solid var(--border);text-align:left;white-space:nowrap}
td{padding:13px 14px;border-bottom:1px solid var(--border);font-size:13px;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr.row-d td{background:#fff5f6}
tr.row-w td{background:#fffbf0}
tr:hover td{background:rgba(0,0,0,.02)}
tr.row-d:hover td{background:#fee8ea}
tr.row-w:hover td{background:#fff3cc}
.mono{font-family:'Courier New',monospace;font-size:12px;color:var(--text2)}
.tag-chip{display:inline-block;padding:2px 8px;border-radius:5px;background:var(--bg3);border:1px solid var(--border);font-size:11px;color:var(--text2);font-family:'Courier New',monospace}

/* === BADGES === */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap}
.bdot{width:5px;height:5px;border-radius:50%;flex-shrink:0}
.b-teal{background:var(--teal-light);color:#085041}
.b-amber{background:var(--amber-light);color:#7a4800}
.b-red{background:var(--red-light);color:#8b1a1a}
.b-green{background:var(--green-light);color:#0f4a27}
.b-purple{background:var(--purple-light);color:#3a2570}
.b-gray{background:var(--bg3);color:var(--text2)}
.cdown{font-size:12px;font-weight:600;font-family:'Courier New',monospace}
.cdown.safe{color:var(--green)}
.cdown.warn{color:var(--amber)}
.cdown.dang{color:var(--red)}

/* === DROPDOWN === */
.dropdown{position:relative;display:inline-block}
.dropdown-menu{position:absolute;right:0;top:calc(100% + 4px);background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow2);min-width:170px;z-index:50;overflow:hidden;display:none}
.dropdown-menu.open{display:block}
.dd-item{display:flex;align-items:center;gap:8px;padding:10px 14px;font-size:13px;cursor:pointer;color:var(--text);transition:background .1s;white-space:nowrap}
.dd-item:hover{background:var(--bg3)}
.dd-item.danger{color:var(--red)}
.dd-item.danger:hover{background:var(--red-light)}
.dd-divider{height:1px;background:var(--border);margin:3px 0}

/* === MODAL === */
.modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:300;display:none;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(3px)}
.modal-backdrop.open{display:flex}
.modal-box{background:var(--bg2);border-radius:var(--radius-lg);padding:24px;width:100%;max-width:400px;box-shadow:var(--shadow2)}
.modal-title{font-size:16px;font-weight:700;margin-bottom:8px}
.modal-body{font-size:13px;color:var(--text2);line-height:1.6;margin-bottom:20px}
.modal-footer{display:flex;gap:8px;justify-content:flex-end}

/* === REKAP === */
.bar-chart{display:flex;flex-direction:column;gap:10px}
.bar-item{display:flex;align-items:center;gap:10px}
.bar-label{font-size:12px;color:var(--text2);width:110px;flex-shrink:0}
.bar-track{flex:1;height:8px;background:var(--bg3);border-radius:4px;overflow:hidden}
.bar-fill{height:100%;border-radius:4px;transition:width .6s ease}
.bar-val{font-size:12px;font-family:'Courier New',monospace;color:var(--text2);width:20px;text-align:right}
.rekap-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1fr);gap:16px}

/* === AKUN GRID === */
.akun-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px}
.akun-card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;display:flex;align-items:center;gap:12px}
.akun-avatar{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0}
.akun-name{font-size:13px;font-weight:600}
.akun-user{font-size:11px;color:var(--text3);font-family:'Courier New',monospace;margin-bottom:4px}
.akun-actions{display:flex;gap:5px;margin-left:auto}
.akun-add{border-style:dashed;cursor:pointer;opacity:.5;justify-content:center;flex-direction:column;text-align:center;min-height:70px}
.akun-add:hover{opacity:.8;background:var(--bg3)}

/* === LOG MODAL === */
.log-list{display:flex;flex-direction:column}
.log-item{display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)}
.log-item:last-child{border-bottom:none}
.log-dot-col{display:flex;flex-direction:column;align-items:center;padding-top:3px}
.log-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.log-line{flex:1;width:1px;background:var(--border);margin-top:3px}
.log-action{font-size:13px;font-weight:600}
.log-meta{font-size:11px;color:var(--text3);margin-top:2px;font-family:'Courier New',monospace}

/* === MOBILE === */
@media(max-width:768px){
  .sidebar{position:fixed;left:0;top:0;bottom:0;transform:translateX(-100%);box-shadow:var(--shadow2)}
  .sidebar.open{transform:translateX(0)}
  .topbar-menu{display:block}
  .topbar-date{display:none}
  .content{padding:14px 12px}
  .form-row{grid-template-columns:1fr}
  .rekap-grid{grid-template-columns:1fr}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .search-box{width:140px}
  .page-hdr{flex-direction:column;gap:8px}
  .page-hdr .btn{align-self:flex-start}
}
@media(max-width:400px){
  .stats-grid{grid-template-columns:1fr 1fr}
  .card-header{gap:6px}
  .search-box{width:100%;order:3}
}
</style>
</head>
<body>
<div id="appWrapper">
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-head">
      <div class="s-brand">
        <div class="s-icon">💳</div>
        <div>
          <div class="s-title">SMK-T</div>
          <div class="s-sub">MONITORING KARTU ATM</div>
        </div>
      </div>
    </div>

    <nav class="s-nav">
      <div class="s-section">Menu Utama</div>

      @if(in_array(Auth::user()->role, ['cs', 'admin']))
      <a href="{{ url('/dashboard') }}" class="s-item {{ Request::is('dashboard') ? 'active' : '' }}">
        <span class="ico">📊</span> Dashboard
        @isset($kritisCount)
          @if($kritisCount > 0)
            <span class="s-badge">{{ $kritisCount }}</span>
          @endif
        @endisset
      </a>
      @endif

      <a href="{{ url('/input') }}" class="s-item {{ Request::is('input') ? 'active' : '' }}">
        <span class="ico">➕</span> Input Kartu
      </a>

      @if(in_array(Auth::user()->role, ['cs', 'admin']))
      <div class="s-section">Data & Laporan</div>
      <a href="{{ url('/arsip') }}" class="s-item {{ Request::is('arsip') ? 'active' : '' }}">
        <span class="ico">📁</span> Arsip Riwayat
      </a>
      <a href="{{ url('/rekap') }}" class="s-item {{ Request::is('rekap') ? 'active' : '' }}">
        <span class="ico">📈</span> Rekap Mingguan
      </a>
      @endif

      @if(Auth::user()->role === 'admin')
      <div class="s-section">Pengaturan</div>
      <a href="{{ url('/akun') }}" class="s-item {{ Request::is('akun') ? 'active' : '' }}">
        <span class="ico">👥</span> Manajemen Akun
      </a>
      @endif
    </nav>

    <div class="s-foot">
      @php
        $roleColor = Auth::user()->role === 'admin' ? 'amber' : (Auth::user()->role === 'cs' ? 'purple' : 'teal');
      @endphp
      <a href="{{ url('/logout') }}" class="s-user">
        <div class="s-avatar" style="background:var(--{{ $roleColor }}-light);color:var(--{{ $roleColor }})">
          {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
        </div>
        <div>
          <div class="s-uname">{{ Auth::user()->nama }}</div>
          <div class="s-urole">{{ strtoupper(Auth::user()->role) }} · Ketuk untuk keluar</div>
        </div>
      </a>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <button class="topbar-menu" onclick="openSidebar()" aria-label="Menu">☰</button>
      <div class="topbar-title">@yield('title')</div>
      <div class="topbar-right">
        <span class="topbar-date" id="topbarDate"></span>
      </div>
    </div>
    <div class="content">
      @yield('content')
    </div>
  </div>
</div>

<script>
function openSidebar(){
  document.getElementById('sidebar').classList.add('open');
  document.getElementById('sidebarOverlay').classList.add('open');
}
function closeSidebar(){
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('open');
}
const _m=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
const _d=new Date();
document.getElementById('topbarDate').textContent=_d.getDate()+' '+_m[_d.getMonth()]+' '+_d.getFullYear();
</script>
</body>
</html>