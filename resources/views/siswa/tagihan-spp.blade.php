<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan SPP</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tagihan-spp.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-wrapper">

    {{-- NAVBAR --}}
    <div class="navbar">
        <div class="nav-left">
            <i class="fa-solid fa-building logo-icon"></i>
            <span class="logo-text">Sistem Pembayaran SPP</span>
        </div>
        <div class="nav-right">
            <div class="user-box">
                <img src="{{ asset('images/fotoadmin.jpg') }}">
                <div>
                    <span class="user-name">{{ session('siswa_nama') }}</span>
                    <span class="user-kelas">{{ session('siswa_kelas') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        {{-- SIDEBAR --}}
        <div class="sidebar">
            <ul class="menu">
                <li>
                    <a href="{{ route('siswa.dashboard') }}">
                        <i class="fa fa-lock"></i> Dashboard
                    </a>
                </li>
                <li class="active">
                    <a href="{{ route('siswa.tagihan') }}">
                        <i class="fa fa-file-invoice"></i> Tagihan SPP
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.riwayat') }}">
                        <i class="fa fa-history"></i> Riwayat Pembayaran
                    </a>
                </li>
            </ul>
            <form method="POST" action="{{ route('siswa.logout') }}">
                @csrf
                <button type="submit" class="logout">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        {{-- MAIN --}}
        <div class="main">

            {{-- HEADER + FILTER SEJAJAR --}}
            <div class="page-header-row">
                <div class="page-header">
                    <h2>Tagihan SPP</h2>
                    <p>Berikut adalah daftar tagihan SPP yang harus dibayar</p>
                </div>
                <div class="custom-filter-dropdown" id="filterDropdown">
                    <button class="filter-btn" onclick="toggleFilter()">
                        <i class="fa fa-calendar"></i>
                        <span id="filterLabel">2026</span>
                        <i class="fa fa-chevron-down arrow"></i>
                    </button>
                    <div class="filter-menu">
                        <div class="filter-header">Pilih Tahun</div>
                        <div class="filter-item" onclick="pilihTahun('', 'Semua Tahun', this)">Semua Tahun</div>
                        <div class="filter-item" onclick="pilihTahun('2024', '2024', this)">2024</div>
                        <div class="filter-item" onclick="pilihTahun('2025', '2025', this)">2025</div>
                        <div class="filter-item active" onclick="pilihTahun('2026', '2026', this)">2026</div>
                    </div>
                </div>
            </div>

            @php
                $tahunIni  = date('Y');
                $bulanSkrg = (int) date('n');
                $bulanList = ['Januari','Februari','Maret','April','Mei','Juni',
                              'Juli','Agustus','September','Oktober','November','Desember'];
                $nominal   = $spp ? $spp->nominal : 0;

                $belumBayarCount = 0;
                for ($b = $bulanMulai; $b <= $bulanSkrg; $b++) {
                    if (!$bayarPerBulan->has($b)) $belumBayarCount++;
                }
                $belumLunas = $belumBayarCount * $nominal;
                $adaData    = false;
            @endphp

            <div class="info-row">
                <div class="info-card-left">
                    <div class="ic-icon orange"><i class="fa fa-file-alt"></i></div>
                    <div>
                        <div class="ic-label">Total Tagihan Belum Lunas</div>
                        <div class="ic-value">Rp {{ number_format($belumLunas, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="info-card-right">
                    <div class="ic-icon brown"><i class="fa fa-file-lines"></i></div>
                    <div class="ic-desc">Segera lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari keterlambatan.</div>
                </div>
            </div>

            <div class="table-wrap">
                <table id="tagihanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Nominal</th>
                            <th>Status Pembayaran</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($bulanList as $i => $bulan)
                    @php $numBulan = $i + 1; @endphp
                    @if($numBulan < $bulanMulai) @continue @endif
                    @php
                        $adaData    = true;
                        $bayar      = $bayarPerBulan->get($numBulan)?->first();
                        $status     = $bayar ? $bayar->status : 'belum';
                        $jtBulan    = $numBulan < 12 ? $numBulan + 1 : 1;
                        $jtTahun    = $numBulan < 12 ? $tahunIni : $tahunIni + 1;
                        $jatuhTempo = '10/' . str_pad($jtBulan, 2, '0', STR_PAD_LEFT) . '/' . $jtTahun;
                        $ket        = $bayar ? 'Pembayaran tepat waktu' : 'Segera lakukan pembayaran';
                        $jumlahFmt  = $bayar ? 'Rp ' . number_format($bayar->jumlah_bayar, 0, ',', '.') : '';
                    @endphp
                    <tr data-tahun="{{ $tahunIni }}">
                        <td>{{ $numBulan - $bulanMulai + 1 }}</td>
                        <td>{{ $bulan }} {{ $tahunIni }}</td>
                        <td>Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                        <td><span class="badge-st {{ $status }}">{{ $status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}</span></td>
                        <td>{{ $jatuhTempo }}</td>
                        <td>{{ $ket }}</td>
                        <td>
                            @if($bayar)
                            <button class="btn-lihat" onclick="bukaDetail(
                                '{{ session('siswa_nis') }}',
                                '{{ session('siswa_nama') }}',
                                '{{ session('siswa_kelas') }}',
                                '{{ $tahunIni }}',
                                '{{ $bulan }}',
                                '{{ $jumlahFmt }}',
                                '{{ ucfirst($bayar->metode) }}',
                                '{{ ucfirst($bayar->status) }}',
                                '{{ $bayar->tanggal_bayar }}'
                            )"><i class="fa fa-eye"></i> Lihat Detail</button>
                            @else
                            <span class="btn-lihat-disabled">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @if(!$adaData)
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fa fa-inbox"></i>
                            <span>Belum ada data tagihan untuk tahun ini</span>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal-overlay" id="modalDetail" onclick="tutupModal(event)">
    <div class="modal-box">
        <h3 class="modal-title">Detail Pembayaran</h3>
        <p class="modal-sub">Informasi lengkap transaksi pembayaran siswa.</p>

        <div class="detail-card light">
            <div class="detail-card-title">DATA SISWA</div>
            <div class="detail-row"><span>NIS</span><strong id="dNIS">-</strong></div>
            <div class="detail-row"><span>Nama</span><strong id="dNama">-</strong></div>
            <div class="detail-row"><span>Kelas</span><strong id="dKelas">-</strong></div>
        </div>

        <div class="detail-card dark">
            <div class="detail-card-title">RINGKASAN PEMBAYARAN</div>
            <div class="detail-row dark"><span>Tahun</span><strong id="dTahun">-</strong></div>
            <div class="detail-row dark"><span>Bulan</span><strong id="dBulan">-</strong></div>
            <div class="detail-row dark"><span>Jumlah Bayar</span><strong id="dJumlah">-</strong></div>
            <div class="detail-row dark"><span>Metode</span><strong id="dMetode">-</strong></div>
            <div class="detail-row dark"><span>Status</span><strong id="dStatus">-</strong></div>
            <div class="detail-row dark"><span>Tanggal Bayar</span><strong id="dTanggal">-</strong></div>
        </div>

        <div class="modal-actions">
            <button class="btn-tutup" onclick="document.getElementById('modalDetail').classList.remove('show')">Tutup</button>
            <button class="btn-unduh" onclick="unduhPDF()">
                <i class="fa fa-download"></i> Unduh PDF
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function toggleFilter() {
    document.getElementById('filterDropdown').classList.toggle('open');
}

function pilihTahun(val, label, el) {
    document.getElementById('filterLabel').textContent = label;
    document.getElementById('filterDropdown').classList.remove('open');
    document.querySelectorAll('.filter-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('#tagihanTable tbody tr').forEach(row => {
        row.style.display = (!val || row.dataset.tahun === val) ? '' : 'none';
    });
}

document.addEventListener('click', function(e) {
    const dd = document.getElementById('filterDropdown');
    if (dd && !dd.contains(e.target)) dd.classList.remove('open');
});

function bukaDetail(nis, nama, kelas, tahun, bulan, jumlah, metode, status, tanggal) {
    document.getElementById('dNIS').textContent     = nis;
    document.getElementById('dNama').textContent    = nama;
    document.getElementById('dKelas').textContent   = kelas;
    document.getElementById('dTahun').textContent   = tahun;
    document.getElementById('dBulan').textContent   = bulan;
    document.getElementById('dJumlah').textContent  = jumlah;
    document.getElementById('dMetode').textContent  = metode;
    document.getElementById('dStatus').textContent  = status;
    document.getElementById('dTanggal').textContent = tanggal;
    document.getElementById('modalDetail').classList.add('show');
}

function tutupModal(e) {
    if (e.target.id === 'modalDetail') {
        document.getElementById('modalDetail').classList.remove('show');
    }
}

function unduhPDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p','mm','a4');
    const W = pdf.internal.pageSize.getWidth();
    let y = 15;

    pdf.setFontSize(14); pdf.setFont('helvetica','bold');
    pdf.text('BUKTI PEMBAYARAN SPP', W/2, y, {align:'center'}); y+=7;
    pdf.setFontSize(9); pdf.setFont('helvetica','normal');
    pdf.text('Sistem Pembayaran SPP Sekolah', W/2, y, {align:'center'}); y+=4;
    pdf.line(15,y,W-15,y); y+=7;

    pdf.setFontSize(11); pdf.setFont('helvetica','bold');
    pdf.text('Data Siswa', 15, y); y+=6;
    pdf.setFont('helvetica','normal'); pdf.setFontSize(10);
    pdf.text('NIS    : ' + document.getElementById('dNIS').textContent, 15, y); y+=5;
    pdf.text('Nama   : ' + document.getElementById('dNama').textContent, 15, y); y+=5;
    pdf.text('Kelas  : ' + document.getElementById('dKelas').textContent, 15, y); y+=8;

    pdf.setFont('helvetica','bold'); pdf.setFontSize(11);
    pdf.text('Ringkasan Pembayaran', 15, y); y+=6;
    pdf.setFont('helvetica','normal'); pdf.setFontSize(10);
    pdf.text('Tahun        : ' + document.getElementById('dTahun').textContent, 15, y); y+=5;
    pdf.text('Bulan        : ' + document.getElementById('dBulan').textContent, 15, y); y+=5;
    pdf.text('Jumlah Bayar : ' + document.getElementById('dJumlah').textContent, 15, y); y+=5;
    pdf.text('Metode       : ' + document.getElementById('dMetode').textContent, 15, y); y+=5;
    pdf.text('Status       : ' + document.getElementById('dStatus').textContent, 15, y); y+=5;
    pdf.text('Tanggal Bayar: ' + document.getElementById('dTanggal').textContent, 15, y); y+=8;

    pdf.line(15,y,W-15,y); y+=5;
    pdf.setFontSize(8); pdf.setFont('helvetica','italic');
    pdf.text('Dokumen ini dicetak otomatis oleh Sistem Pembayaran SPP', W/2, y, {align:'center'});
    pdf.save('bukti-pembayaran.pdf');
}
</script>

</body>
</html>