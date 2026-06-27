<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>@yield('title', 'SMK-T')</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
:root{
  --bg:#f5f6fa;--bg2:#ffffff;--bg3:#f0f1f5;
  --text:#1a1d2e;--text2:#6b7280;--text3:#9ca3af;
  --border:#e5e7eb;--border2:#d1d5db;
  --teal:#0f9e74;--teal2:#0d8a64;--teal-light:#e6f7f2;--teal-dim:rgba(15,158,116,0.1);
  --red:#dc3545;--red-light:#fde8ea;
  --shadow:0 1px 4px rgba(0,0,0,0.08);
  --shadow2:0 4px 20px rgba(0,0,0,0.12);
  --radius:10px;--radius-lg:14px;
  --font:'Segoe UI',system-ui,sans-serif;
}
body{font-family:var(--font);color:var(--text);font-size:14px;min-height:100vh;background:linear-gradient(135deg,#0f9e74 0%,#0d5c8a 100%);display:flex;align-items:center;justify-content:center;padding:20px}
.login-card{background:var(--bg2);border-radius:var(--radius-lg);padding:28px 24px;width:100%;max-width:380px;box-shadow:var(--shadow2)}
.login-brand{display:flex;align-items:center;gap:12px;margin-bottom:24px}
.brand-icon{width:44px;height:44px;background:var(--teal);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.brand-title{font-size:20px;font-weight:700}
.brand-sub{font-size:11px;color:var(--text2)}
.login-greeting{font-size:14px;color:var(--text2);margin-bottom:20px;line-height:1.5}
.role-tabs{display:flex;gap:6px;margin-bottom:18px;background:var(--bg3);border-radius:8px;padding:3px}
.role-tab{flex:1;padding:7px 4px;border-radius:6px;text-align:center;font-size:12px;font-weight:500;cursor:pointer;color:var(--text2);transition:all .15s;border:none;background:transparent;font-family:var(--font)}
.role-tab.active{background:var(--bg2);color:var(--teal);box-shadow:var(--shadow);font-weight:600}
.form-group{margin-bottom:14px}
.form-label{font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;letter-spacing:.3px}
.form-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:11px 14px;font-size:14px;font-family:var(--font);color:var(--text);background:var(--bg2);transition:border .15s;outline:none;-webkit-appearance:none}
.form-input:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-dim)}
.form-input.error{border-color:var(--red)}
.err-msg{font-size:11px;color:var(--red);margin-top:4px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 18px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;border:1.5px solid transparent;font-family:var(--font);transition:all .15s;text-decoration:none;white-space:nowrap}
.btn-primary{background:var(--teal);color:#fff;border-color:var(--teal)}
.btn-primary:hover{background:var(--teal2);border-color:var(--teal2)}
.btn-outline{background:transparent;color:var(--text);border-color:var(--border2)}
.btn-outline:hover{background:var(--bg3)}
.btn-block{width:100%;padding:13px}
</style>
</head>
<body>
<div class="login-card">
  @yield('content')
</div>
@yield('scripts')
</body>
</html>