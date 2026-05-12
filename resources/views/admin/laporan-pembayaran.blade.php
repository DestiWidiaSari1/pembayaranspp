@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/laporan-pembayaran.css') }}">

<!-- HEADER -->
<div class="page-header">
    <div>
        <h2>Laporan Pembayaran SPP</h2>
        <p>Lihat dan Cetak Laporan Pembayaran SPP</p>
    </div>
    <div class="export-group">
        <button class="btn-export btn-pdf">
            <i class="fa fa-file-pdf"></i> Export PDF
        </button>
        <button class="btn-export btn-excel">
            <i class="fa fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<div class="content-box">

    <!-- FILTER -->
<form method="GET" action="{{ route('laporan-pembayaran') }}" id="filterForm">
<div class="filter-wrapper">

    <!-- DROPDOWN BULAN -->
    <div class="filter-item">
        <label>Filter Bulan</label>
        <div class="custom-dropdown" id="dropdownBulan">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dropdownBulan')">
                <span id="labelBulan">{{ request('bulan') ?: 'Semua Bulan' }}</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <input type="hidden" name="bulan" id="inputBulan" value="{{ request('bulan') }}">
            <div class="dropdown-menu">
                <div class="dropdown-item {{ !request('bulan') ? 'active' : '' }}" onclick="pilihFilter('dropdownBulan','labelBulan','inputBulan','','Semua Bulan',this)">Semua Bulan</div>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                <div class="dropdown-item {{ request('bulan') == $b ? 'active' : '' }}" onclick="pilihFilter('dropdownBulan','labelBulan','inputBulan','{{ $b }}','{{ $b }}',this)">{{ $b }}</div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- DROPDOWN TAHUN -->
    <div class="filter-item">
        <label>Filter Tahun</label>
        <div class="custom-dropdown" id="dropdownTahun">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dropdownTahun')">
                <span id="labelTahun">{{ request('tahun') ?: 'Semua Tahun' }}</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <input type="hidden" name="tahun" id="inputTahun" value="{{ request('tahun') }}">
            <div class="dropdown-menu">
                <div class="dropdown-item {{ !request('tahun') ? 'active' : '' }}" onclick="pilihFilter('dropdownTahun','labelTahun','inputTahun','','Semua Tahun',this)">Semua Tahun</div>
                @foreach(['2024','2025','2026'] as $y)
                <div class="dropdown-item {{ request('tahun') == $y ? 'active' : '' }}" onclick="pilihFilter('dropdownTahun','labelTahun','inputTahun','{{ $y }}','{{ $y }}',this)">{{ $y }}</div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- DROPDOWN STATUS -->
    <div class="filter-item">
        <label>Status</label>
        <div class="custom-dropdown" id="dropdownStatus">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dropdownStatus')">
                <span id="labelStatus">{{ request('status') == 'lunas' ? 'Lunas' : (request('status') == 'belum' ? 'Belum Lunas' : 'Semua Status') }}</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <input type="hidden" name="status" id="inputStatus" value="{{ request('status') }}">
            <div class="dropdown-menu">
                <div class="dropdown-item {{ !request('status') ? 'active' : '' }}" onclick="pilihFilter('dropdownStatus','labelStatus','inputStatus','','Semua Status',this)">Semua Status</div>
                <div class="dropdown-item {{ request('status') == 'lunas' ? 'active' : '' }}" onclick="pilihFilter('dropdownStatus','labelStatus','inputStatus','lunas','Lunas',this)">Lunas</div>
                <div class="dropdown-item {{ request('status') == 'belum' ? 'active' : '' }}" onclick="pilihFilter('dropdownStatus','labelStatus','inputStatus','belum','Belum Lunas',this)">Belum Lunas</div>
            </div>
        </div>
    </div>

    <div class="filter-item" style="justify-content:flex-end;">
        <label style="opacity:0;">.</label>
        <button type="submit" class="btn-filter">
            <i class="fa fa-search"></i> Filter
        </button>
    </div>

</div>
</form>

    <!-- SUMMARY -->
<div class="summary-box">
    <div class="summary-card">
        <div class="summary-icon bg-green"><i class="fa fa-users"></i></div>
        <div class="summary-text">
            <h4>Total Siswa</h4>
            <p>{{ $totalSiswa }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-orange"><i class="fa fa-money-bill-wave"></i></div>
        <div class="summary-text">
            <h4>Total Transaksi</h4>
            <p>{{ $totalTransaksi }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-blue"><i class="fa fa-wallet"></i></div>
        <div class="summary-text">
            <h4>Total Pemasukan</h4>
            <p>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-purple"><i class="fa fa-chart-pie"></i></div>
        <div class="summary-text">
            <h4>Lunas</h4>
            <p>{{ $persen_lunas }}%</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon bg-pink"><i class="fa fa-clock"></i></div>
        <div class="summary-text">
            <h4>Belum Lunas</h4>
            <p>{{ $persen_belum }}%</p>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jumlah Bayar</th>
                <th>Metode</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $t)
            <tr>
                <td>{{ $transaksis->firstItem() + $i }}</td>
                <td>{{ $t->tanggal_bayar }}</td>
                <td>{{ $t->siswa->nis ?? '-' }}</td>
                <td class="text-left">{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                <td>{{ $t->siswa->kelas ?? '-' }}</td>
                <td>{{ $t->jumlah_bayar ? 'Rp ' . number_format($t->jumlah_bayar, 0, ',', '.') : '–' }}</td>
                <td>{{ $t->metode ?? '–' }}</td>
                <td>
                    <span class="badge {{ $t->status }}">
                        {{ $t->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </td>
                <td>
                    <button class="btn-detail">
                        <i class="fa fa-eye"></i> Lihat Detail
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:28px; color:#7b6a58;">
                    Belum ada data transaksi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-box">
        <div class="pagination-left">
            @if($transaksis->onFirstPage())
                <span class="page-nav disabled">‹ Previous</span>
            @else
                <a href="{{ $transaksis->previousPageUrl() }}" class="page-nav">‹ Previous</a>
            @endif

            @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-num {{ $page == $transaksis->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($transaksis->hasMorePages())
                <a href="{{ $transaksis->nextPageUrl() }}" class="page-nav">Next ›</a>
            @else
                <span class="page-nav disabled">Next ›</span>
            @endif
        </div>
        <span>Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }} data</span>
    </div>
</div>
</div> {{-- tutup content-box --}}

<!-- MODAL DETAIL -->
<div class="modal-overlay" id="modalDetail" onclick="if(event.target===this)closeDetail()">
    <div class="modal-box-laporan">
        <div class="modal-header-laporan">
            <div>
                <h3 class="modal-title-laporan">Detail Pembayaran</h3>
                <p class="modal-sub-laporan">Informasi lengkap transaksi pembayaran</p>
            </div>
            <button onclick="closeDetail()" class="modal-close-laporan">&times;</button>
        </div>
        <div class="modal-body-laporan">
            <div class="detail-card">
                <div class="detail-card-title">DATA SISWA</div>
                <div class="detail-row"><span>NIS</span><span id="dNIS">-</span></div>
                <div class="detail-row"><span>Nama</span><span id="dNama">-</span></div>
                <div class="detail-row"><span>Kelas</span><span id="dKelas">-</span></div>
            </div>
            <div class="detail-card dark">
                <div class="detail-card-title">RINGKASAN PEMBAYARAN</div>
                <div class="detail-row"><span>Tanggal Bayar</span><span id="dTanggal">-</span></div>
                <div class="detail-row"><span>Jumlah Bayar</span><span id="dJumlah">-</span></div>
                <div class="detail-row"><span>Metode</span><span id="dMetode">-</span></div>
                <div class="detail-row"><span>Status</span><span id="dStatus">-</span></div>
            </div>
        </div>
        <div class="modal-footer-laporan">
            <button onclick="closeDetail()" class="btn-modal-tutup">Tutup</button>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function toggleDropdown(id) {
    const dd = document.getElementById(id);
    const isOpen = dd.classList.contains('open');

    document.querySelectorAll('.custom-dropdown').forEach(d => {
        d.classList.remove('open');
        const m = d.querySelector('.dropdown-menu');
        if (m) m.removeAttribute('style');
    });

    if (!isOpen) {
        dd.classList.add('open');
        const btn = dd.querySelector('.dropdown-toggle');
        const menu = dd.querySelector('.dropdown-menu');
        const rect = btn.getBoundingClientRect();

        menu.style.position = 'fixed';
        menu.style.top = (rect.bottom + 6) + 'px';
        menu.style.minWidth = rect.width + 'px';

        if (rect.left + 170 > window.innerWidth) {
            menu.style.left = 'auto';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
        } else {
            menu.style.left = rect.left + 'px';
            menu.style.right = 'auto';
        }
    }
}

function pilihFilter(ddId, labelId, inputId, nilai, label, el) {
    document.getElementById(labelId).textContent = label;
    document.getElementById(inputId).value = nilai;
    document.querySelectorAll('#' + ddId + ' .dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(ddId).classList.remove('open');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.custom-dropdown').forEach(d => {
            d.classList.remove('open');
            const m = d.querySelector('.dropdown-menu');
            if (m) m.removeAttribute('style');
        });
    }
});
// ===== EXPORT PDF =====
document.querySelector('.btn-pdf').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('l', 'mm', 'a4'); // landscape
    const W = pdf.internal.pageSize.getWidth();
    let y = 15;

    // Header
    pdf.setFontSize(14); pdf.setFont('helvetica', 'bold');
    pdf.text('LAPORAN PEMBAYARAN SPP', W / 2, y, { align: 'center' });
    y += 6;
    pdf.setFontSize(9); pdf.setFont('helvetica', 'normal');
    pdf.text('Sistem Pembayaran SPP Sekolah', W / 2, y, { align: 'center' });
    y += 4;
    pdf.setLineWidth(0.5); pdf.line(15, y, W - 15, y); y += 8;

    // Kolom
    const cols = ['No','Tanggal','NIS','Nama Siswa','Kelas','Jumlah','Metode','Status'];
    const widths = [12, 30, 18, 50, 22, 28, 22, 24];
    let x = 15;

    pdf.setFontSize(10); pdf.setFont('helvetica', 'bold');
    cols.forEach((col, i) => { pdf.text(col, x + 2, y); x += widths[i]; });
    y += 2; pdf.line(15, y, W - 15, y); y += 6;

    // Data dari tabel
    pdf.setFont('helvetica', 'normal'); pdf.setFontSize(9);
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return;
        x = 15;
        const vals = [
            cells[0].textContent.trim(),
            cells[1].textContent.trim(),
            cells[2].textContent.trim(),
            cells[3].textContent.trim(),
            cells[4].textContent.trim(),
            cells[5].textContent.trim(),
            cells[6].textContent.trim(),
            cells[7].textContent.trim(),
        ];
        vals.forEach((val, i) => {
            pdf.text(val.substring(0, 20), x + 2, y);
            x += widths[i];
        });
        y += 7;
        if (y > 190) { pdf.addPage(); y = 15; }
    });

    pdf.setLineWidth(0.3); pdf.line(15, y, W - 15, y);
    pdf.save('laporan-pembayaran.pdf');
});

// ===== EXPORT EXCEL =====
document.querySelector('.btn-excel').addEventListener('click', function() {
    const rows = document.querySelectorAll('tbody tr');
    let csv = 'No,Tanggal Bayar,NIS,Nama Siswa,Kelas,Jumlah Bayar,Metode,Status\n';
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return;
        const vals = Array.from(cells).slice(0, 8).map(c => '"' + c.textContent.trim() + '"');
        csv += vals.join(',') + '\n';
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'laporan-pembayaran.csv'; a.click();
    URL.revokeObjectURL(url);
});

// ===== LIHAT DETAIL =====
document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = this.closest('tr');
        const cells = row.querySelectorAll('td');
        document.getElementById('dNIS').textContent     = cells[2].textContent.trim();
        document.getElementById('dNama').textContent    = cells[3].textContent.trim();
        document.getElementById('dKelas').textContent   = cells[4].textContent.trim();
        document.getElementById('dTanggal').textContent = cells[1].textContent.trim();
        document.getElementById('dJumlah').textContent  = cells[5].textContent.trim();
        document.getElementById('dMetode').textContent  = cells[6].textContent.trim();
        document.getElementById('dStatus').textContent  = cells[7].textContent.trim();
        document.getElementById('modalDetail').classList.add('active');
    });
});

function closeDetail() {
    document.getElementById('modalDetail').classList.remove('active');
}

</script>
@endsection