<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/riwayat-pembayaran.css') }}">
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
                <li>
                    <a href="{{ route('siswa.tagihan') }}">
                        <i class="fa fa-file-invoice"></i> Tagihan SPP
                    </a>
                </li>
                <li class="active">
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

            {{-- HEADER + FILTER --}}
            <div class="riwayat-header">
                <div>
                    <h2>Riwayat Pembayaran</h2>
                    <p>Berikut adalah riwayat pembayaran SPP yang telah anda lakukan</p>
                </div>
                {{-- CUSTOM DROPDOWN TAHUN --}}
                <div class="custom-filter-dropdown" id="dropTahun">
                    <button type="button" class="filter-btn" onclick="toggleFilterDrop()">
                        <i class="fa fa-calendar"></i>
                        <span id="labelTahun">Tahun {{ $tahun }}</span>
                        <span class="arrow">▼</span>
                    </button>
                    <div class="filter-menu" id="filterMenu">
                        <div class="filter-header">Pilih Tahun</div>
                        @foreach(['2024','2025','2026'] as $t)
                        <div class="filter-item {{ $tahun == $t ? 'active' : '' }}"
                             onclick="pilihTahun('{{ $t }}')">{{ $t }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- INFO CARDS --}}
            <div class="info-row">
                <div class="info-card-left">
                    <div class="ic-icon orange"><i class="fa fa-file-alt"></i></div>
                    <div>
                        <div class="ic-label">Total Pembayaran ({{ $tahun }})</div>
                        <div class="ic-value">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                        <div class="ic-sub">{{ $jumlahBayar }} Kali Pembayaran</div>
                    </div>
                </div>
                <div class="info-card-right">
                    <div class="ic-icon brown"><i class="fa fa-file-lines"></i></div>
                    <div>
                        <div class="ic-label-bold">Informasi</div>
                        <div class="ic-desc">Simpan bukti pembayaran sebagai arsip Anda.</div>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Bayar</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status Pembayaran</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $i => $t)
                        @php $jumlahFmt = number_format($t->jumlah_bayar, 0, ',', '.'); @endphp
                        <tr>
                            <td>{{ $transaksis->firstItem() + $i }}</td>
                            <td>{{ $t->tanggal_bayar }}</td>
                            <td>{{ $t->bulan }} {{ $t->tahun }}</td>
                            <td>Rp {{ $jumlahFmt }}</td>
                            <td>{{ ucfirst($t->metode) }}</td>
                            <td><span class="badge-st {{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
                            <td>
                            @php
                                $sNis   = session('siswa_nis');
                                $sNama  = session('siswa_nama');
                                $sKelas = session('siswa_kelas');
                            @endphp
                            <button class="btn-download" onclick="bukaDetail(
                                '{{ $sNis }}',
                                '{{ $sNama }}',
                                '{{ $sKelas }}',
                                '{{ $t->tahun }}',
                                '{{ $t->bulan }}',
                                '{{ $jumlahFmt }}',
                                '{{ ucfirst($t->metode) }}',
                                '{{ ucfirst($t->status) }}',
                                '{{ $t->tanggal_bayar }}'
                            )">
                                <i class="fa fa-download"></i> Download
                            </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty">Belum ada riwayat pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                <div class="pagination-box">
                    <div class="pagination-left">
                        @if($transaksis->onFirstPage())
                            <span class="page-nav disabled">‹</span>
                        @else
                            <a href="{{ $transaksis->previousPageUrl() }}" class="page-nav">‹</a>
                        @endif

                        @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="page-num {{ $page == $transaksis->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        @if($transaksis->hasMorePages())
                            <a href="{{ $transaksis->nextPageUrl() }}" class="page-nav">›</a>
                        @else
                            <span class="page-nav disabled">›</span>
                        @endif
                    </div>
                    <div class="pagination-info">
                        Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }} data
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

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
function toggleFilterDrop() {
    document.getElementById('dropTahun').classList.toggle('open');
}

function pilihTahun(val) {
    window.location.href = '{{ route("siswa.riwayat") }}?tahun=' + val;
}

document.addEventListener('click', e => {
    if (!e.target.closest('#dropTahun'))
        document.getElementById('dropTahun').classList.remove('open');
});

function bukaDetail(nis, nama, kelas, tahun, bulan, jumlah, metode, status, tanggal) {
    document.getElementById('dNIS').textContent     = nis;
    document.getElementById('dNama').textContent    = nama;
    document.getElementById('dKelas').textContent   = kelas;
    document.getElementById('dTahun').textContent   = tahun;
    document.getElementById('dBulan').textContent   = bulan;
    document.getElementById('dJumlah').textContent  = 'Rp ' + jumlah;
    document.getElementById('dMetode').textContent  = metode;
    document.getElementById('dStatus').textContent  = status;
    document.getElementById('dTanggal').textContent = tanggal;
    document.getElementById('modalDetail').classList.add('show');
}

function tutupModal(e) {
    if (e.target.id === 'modalDetail')
        document.getElementById('modalDetail').classList.remove('show');
}

function unduhPDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p','mm','a4');
    const W = pdf.internal.pageSize.getWidth();
    let y = 15;
    const nis=document.getElementById('dNIS').textContent;
    const nama=document.getElementById('dNama').textContent;
    const kelas=document.getElementById('dKelas').textContent;
    const tahun=document.getElementById('dTahun').textContent;
    const bulan=document.getElementById('dBulan').textContent;
    const jumlah=document.getElementById('dJumlah').textContent;
    const metode=document.getElementById('dMetode').textContent;
    const status=document.getElementById('dStatus').textContent;
    const tanggal=document.getElementById('dTanggal').textContent;
    const noTrans='TRX-'+Date.now().toString().slice(-8);
    const now=new Date().toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});

    pdf.setFontSize(16); pdf.setFont('helvetica','bold');
    pdf.text('SISTEM PEMBAYARAN SPP',W/2,y,{align:'center'}); y+=6;
    pdf.setFontSize(9); pdf.setFont('helvetica','normal');
    pdf.text('Jl. Pendidikan No. 1 | Telp. 021-0000000 | Email: admin@admin.sch.id',W/2,y,{align:'center'}); y+=4;
    pdf.setLineWidth(0.8); pdf.line(15,y,W-15,y); y+=1;
    pdf.setLineWidth(0.3); pdf.line(15,y,W-15,y); y+=7;

    pdf.setFontSize(13); pdf.setFont('helvetica','bold');
    pdf.text('BUKTI PEMBAYARAN SPP',W/2,y,{align:'center'}); y+=10;

    pdf.setFontSize(10); pdf.setFont('helvetica','normal');
    const c1=15,c2=50,c3=W/2+5,c4=W/2+40;
    pdf.text('NO TRANS',c1,y); pdf.text(': '+noTrans,c2,y);
    pdf.text('NIS',c3,y); pdf.text(': '+nis,c4,y); y+=6;
    pdf.text('TANGGAL',c1,y); pdf.text(': '+tanggal,c2,y);
    pdf.text('NAMA SISWA',c3,y); pdf.text(': '+nama,c4,y); y+=6;
    pdf.text('JAM CETAK',c1,y); pdf.text(': '+new Date().toLocaleTimeString('id-ID'),c2,y);
    pdf.text('KELAS',c3,y); pdf.text(': '+kelas,c4,y); y+=8;
    pdf.line(15,y,W-15,y); y+=6;

    pdf.setFont('helvetica','bold');
    pdf.text('No.',15,y); pdf.text('Keterangan Pembayaran',28,y);
    pdf.text('Jumlah (Rp.)',W-15,y,{align:'right'}); y+=4;
    pdf.line(15,y,W-15,y); y+=6;

    pdf.setFont('helvetica','normal');
    pdf.text('1.',15,y); pdf.text('Pembayaran SPP - '+bulan+' '+tahun,28,y);
    pdf.text(jumlah,W-15,y,{align:'right'}); y+=6;
    pdf.text('2.',15,y); pdf.text('Metode Pembayaran: '+metode,28,y);
    pdf.text('-',W-15,y,{align:'right'}); y+=6;
    pdf.line(15,y,W-15,y); y+=6;

    pdf.setFont('helvetica','bold');
    pdf.text('Grand Total :',W/2+10,y);
    pdf.text(jumlah,W-15,y,{align:'right'}); y+=10;

    pdf.setFont('helvetica','normal'); pdf.setFontSize(9);
    pdf.text('Status Pembayaran : '+status.toUpperCase(),15,y); y+=14;

    pdf.setFontSize(10);
    pdf.text('Catatan :',15,y); y+=5;
    pdf.text('- Simpan sebagai bukti pembayaran yang SAH.',15,y); y+=5;
    pdf.text('- Uang yang sudah dibayarkan tidak dapat diminta kembali.',15,y); y+=5;
    pdf.text('- Struk ini berlaku sebagai tanda terima resmi.',15,y); y+=14;

    pdf.text(now,W-15,y,{align:'right'}); y+=5;
    pdf.text('Yang Menerima,',W-15,y,{align:'right'}); y+=22;
    pdf.setFont('helvetica','bold');
    pdf.text('Admin SPP',W-15,y,{align:'right'}); y+=5;
    pdf.setFont('helvetica','normal'); pdf.setFontSize(9);
    pdf.text('Bendahara Sekolah',W-15,y,{align:'right'}); y+=12;

    pdf.line(15,y,W-15,y); y+=5;
    pdf.setFontSize(8); pdf.setFont('helvetica','italic');
    pdf.text('Dokumen ini dicetak secara otomatis oleh Sistem Pembayaran SPP',W/2,y,{align:'center'});
    pdf.save('bukti-'+bulan+'-'+tahun+'.pdf');
}
</script>

</body>
</html>
