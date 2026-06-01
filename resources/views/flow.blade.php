<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>System Architecture Flow - KopDes</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#fef2f2;display:flex;justify-content:center;padding:40px 20px;font-family:'Segoe UI',system-ui,-apple-system,sans-serif}
.diagram{position:relative;width:1100px;height:960px;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.06);padding:0}
.card{position:absolute;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;border:1.5px solid}
.card-title{font-size:15px;font-weight:600;line-height:1.3}
.card-sub{font-size:12px;font-weight:400;line-height:1.4;margin-top:4px;opacity:.75}

.tone-red{background:#fff;border-color:#dc2626;color:#991b1b}
.tone-red-strong{background:#fff;border-color:#b91c1c;color:#7f1d1d}
.red{background:#fef2f2;border-color:#ef4444;color:#991b1b}
.tone-red-soft{background:#fff;border-color:#ef4444;color:#991b1b}
.tone-red-line{background:#fff;border-color:#dc2626;color:#7f1d1d}
.tone-red-pale{background:#fff;border-color:#fecaca;color:#991b1b}
.tone-red-dark{background:#fff;border-color:#b91c1c;color:#7f1d1d}

svg.connectors{position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;overflow:visible}

.branch-label{position:absolute;font-size:11px;font-weight:600;color:#991b1b;letter-spacing:.3px}
.branch-label.fail{color:#ef4444}
.branch-label.success{color:#dc2626}
.return-label{position:absolute;font-size:12px;font-weight:500;color:#991b1b}
.divider{position:absolute;width:1px;background:#fecaca}
</style>
</head>
<body>

<div class="diagram">
  {{-- Buka aplikasi --}}
  <div class="card tone-red" style="left:460px;top:40px;width:180px;height:46px">
    <div class="card-title">Buka aplikasi</div>
  </div>

  {{-- Halaman login --}}
  <div class="card tone-red" style="left:430px;top:116px;width:240px;height:70px">
    <div class="card-title">Halaman login</div>
    <div class="card-sub">No. HP / NIK + PIN / Password</div>
  </div>

  {{-- Validasi kredensial --}}
  <div class="card tone-red-strong" style="left:430px;top:216px;width:240px;height:70px">
    <div class="card-title">Validasi kredensial</div>
  </div>

  {{-- Gagal (fail) --}}
  <div class="card red" style="left:185px;top:326px;width:190px;height:60px">
    <div class="card-title">Gagal</div>
    <div class="card-sub">Tampil pesan error</div>
  </div>

  {{-- Cek role pengguna --}}
  <div class="card tone-red-strong" style="left:445px;top:326px;width:210px;height:60px">
    <div class="card-title">Cek role pengguna</div>
  </div>

  {{-- Role: Super Admin --}}
  <div class="card tone-red" style="left:165px;top:435px;width:170px;height:70px">
    <div class="card-title">Super Admin</div>
    <div class="card-sub">Nakala / Romulus</div>
  </div>

  {{-- Role: Admin Desa --}}
  <div class="card tone-red-soft" style="left:365px;top:435px;width:170px;height:70px">
    <div class="card-title">Admin Desa</div>
    <div class="card-sub">Kepala desa / staf</div>
  </div>

  {{-- Role: Pengurus Kopdes --}}
  <div class="card tone-red-line" style="left:565px;top:435px;width:170px;height:70px">
    <div class="card-title">Pengurus Kopdes</div>
    <div class="card-sub">KDMP / Koperasi</div>
  </div>

  {{-- Role: Pengurus BUMDes --}}
  <div class="card tone-red-pale" style="left:765px;top:435px;width:170px;height:70px">
    <div class="card-title">Pengurus BUMDes</div>
    <div class="card-sub">Manajer unit usaha</div>
  </div>

  {{-- Dashboard: UMKM / Petani --}}
  <div class="card tone-red" style="left:165px;top:535px;width:170px;height:70px">
    <div class="card-title">UMKM / Petani</div>
    <div class="card-sub">Produsen lokal</div>
  </div>

  {{-- Dashboard: Operator MBG --}}
  <div class="card tone-red-soft" style="left:365px;top:535px;width:170px;height:70px">
    <div class="card-title">Operator MBG</div>
    <div class="card-sub">Sekolah / penerima</div>
  </div>

  {{-- Dashboard: Warga desa --}}
  <div class="card tone-red-line" style="left:565px;top:535px;width:170px;height:70px">
    <div class="card-title">Warga desa</div>
    <div class="card-sub">Marketplace / beli</div>
  </div>

  {{-- Dashboard: Pemda / Viewer --}}
  <div class="card tone-red-pale" style="left:765px;top:535px;width:170px;height:70px">
    <div class="card-title">Pemda / Viewer</div>
    <div class="card-sub">Read-only laporan</div>
  </div>

  {{-- Dashboard sesuai role --}}
  <div class="card tone-red-dark" style="left:440px;top:655px;width:220px;height:55px">
    <div class="card-title">Dashboard sesuai role</div>
  </div>

  {{-- Sesi aktif / JWT --}}
  <div class="card tone-red-dark" style="left:415px;top:740px;width:270px;height:70px">
    <div class="card-title">Sesi aktif / JWT / session token</div>
    <div class="card-sub">Auto-refresh &bull; expire 8 jam &bull; remember me 30 hari</div>
  </div>

  {{-- Logout --}}
  <div class="card tone-red-pale" style="left:445px;top:840px;width:210px;height:60px">
    <div class="card-title">Logout / sesi berakhir</div>
    <div class="card-sub">Hapus token, kembali ke login</div>
  </div>

  {{-- SVG Connectors --}}
  <svg class="connectors" viewBox="0 0 1100 960" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <marker id="arrow" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto">
        <path d="M 0 0 L 10 5 L 0 10 z" fill="#dc2626"/>
      </marker>
      <marker id="arrow-dotted" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="5" markerHeight="5" orient="auto">
        <path d="M 0 0 L 10 5 L 0 10 z" fill="#dc2626"/>
      </marker>
    </defs>

    {{-- A. Buka aplikasi ke Halaman login --}}
    <line x1="550" y1="86" x2="550" y2="116" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- B. Halaman login ke Validasi kredensial --}}
    <line x1="550" y1="186" x2="550" y2="216" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- C. Validasi ke branch point --}}
    <line x1="550" y1="286" x2="550" y2="296" stroke="#dc2626" stroke-width="1.5"/>

    {{-- C1. Left branch (fail) ke Gagal --}}
    <polyline points="550,296 280,296 280,326" stroke="#dc2626" stroke-width="1.5" fill="none" marker-end="url(#arrow)"/>

    {{-- C2. Right branch (success) ke Cek role --}}
    <line x1="550" y1="296" x2="550" y2="326" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- D. Dotted return arrow (Gagal ke Validasi) --}}
    <path d="M 280 386 L 370 386 Q 385 386 385 370 L 385 300 Q 385 290 400 290 L 425 290" 
          stroke="#dc2626" stroke-width="1.5" fill="none" 
          stroke-dasharray="5,4" marker-end="url(#arrow-dotted)"/>

    {{-- E. Cek role ke 4-column spread --}}
    <line x1="550" y1="386" x2="550" y2="405" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="250" y1="405" x2="850" y2="405" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="250" y1="405" x2="250" y2="435" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
    <line x1="450" y1="405" x2="450" y2="435" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
    <line x1="650" y1="405" x2="650" y2="435" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
    <line x1="850" y1="405" x2="850" y2="435" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- F. Role cards ke Dashboard cards --}}
    <line x1="250" y1="505" x2="250" y2="535" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
    <line x1="450" y1="505" x2="450" y2="535" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
    <line x1="650" y1="505" x2="650" y2="535" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
    <line x1="850" y1="505" x2="850" y2="535" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- G. Dashboard cards ke merge ke Dashboard sesuai role --}}
    <line x1="250" y1="605" x2="250" y2="625" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="450" y1="605" x2="450" y2="625" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="650" y1="605" x2="650" y2="625" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="850" y1="605" x2="850" y2="625" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="250" y1="625" x2="850" y2="625" stroke="#dc2626" stroke-width="1.5"/>
    <line x1="550" y1="625" x2="550" y2="655" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- H. Dashboard sesuai role ke Sesi aktif --}}
    <line x1="550" y1="710" x2="550" y2="740" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>

    {{-- I. Sesi aktif ke Logout --}}
    <line x1="550" y1="810" x2="550" y2="840" stroke="#dc2626" stroke-width="1.5" marker-end="url(#arrow)"/>
  </svg>

  {{-- Branch labels --}}
  <div class="branch-label fail" style="left:370px;top:290px">Gagal</div>
  <div class="branch-label success" style="left:562px;top:290px">Sukses</div>
  <div class="return-label" style="left:350px;top:360px">Coba lagi</div>

  {{-- Column dividers for 4-column section --}}
  <div class="divider" style="left:320px;top:440px;height:160px"></div>
  <div class="divider" style="left:520px;top:440px;height:160px"></div>
  <div class="divider" style="left:720px;top:440px;height:160px"></div>
</div>

</body>
</html>
