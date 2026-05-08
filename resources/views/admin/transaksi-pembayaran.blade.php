@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/transaksi-pembayaran.css') }}">


    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Transaksi Pembayaran</h2>
            <p>Kelola transaksi pembayaran siswa.</p>
        </div>
        <button class="btn-primary" onclick="openModal('modalTambah')">
            + Pembayaran Baru
        </button>
    </div>

    @if(session('success'))
    <div class="alert-success" id="alertMsg">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- CONTENT --}}
    <div class="content-box">

      {{-- FILTER --}}
    <div class="filter-box">
        <div class="search-box">
            <i class="fa fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari NIS atau nama siswa..." oninput="filterTable()">
    </div>

    <!-- DROPDOWN KELAS -->
    <div class="custom-dropdown" id="dropdownKelas">
        <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dropdownKelas')">
            <span id="labelKelas">Semua Kelas</span>
            <span class="dropdown-arrow">▼</span>
        </button>
        <div class="dropdown-menu">
            <div class="dropdown-item active" onclick="pilihKelas('Semua Kelas', this)">Semua Kelas</div>
            <div class="dropdown-item" onclick="pilihKelas('X IPA 1', this)">X IPA 1</div>
            <div class="dropdown-item" onclick="pilihKelas('X IPA 2', this)">X IPA 2</div>
            <div class="dropdown-item" onclick="pilihKelas('X IPA 3', this)">X IPA 3</div>
            <div class="dropdown-item" onclick="pilihKelas('XI IPA 1', this)">XI IPA 1</div>
            <div class="dropdown-item" onclick="pilihKelas('XI IPA 2', this)">XI IPA 2</div>
            <div class="dropdown-item" onclick="pilihKelas('XI IPA 3', this)">XI IPA 3</div>
            <div class="dropdown-item" onclick="pilihKelas('XII IPA 1', this)">XII IPA 1</div>
            <div class="dropdown-item" onclick="pilihKelas('XII IPA 2', this)">XII IPA 2</div>
            <div class="dropdown-item" onclick="pilihKelas('XII IPA 3', this)">XII IPA 3</div>
            <div class="dropdown-item" onclick="pilihKelas('X IPS 1', this)">X IPS 1</div>
            <div class="dropdown-item" onclick="pilihKelas('X IPS 2', this)">X IPS 2</div>
            <div class="dropdown-item" onclick="pilihKelas('X IPS 3', this)">X IPS 3</div>
            <div class="dropdown-item" onclick="pilihKelas('XI IPS 1', this)">XI IPS 1</div>
            <div class="dropdown-item" onclick="pilihKelas('XI IPS 2', this)">XI IPS 2</div>
            <div class="dropdown-item" onclick="pilihKelas('XI IPS 3', this)">XI IPS 3</div>
            <div class="dropdown-item" onclick="pilihKelas('XII IPS 1', this)">XII IPS 1</div>
            <div class="dropdown-item" onclick="pilihKelas('XII IPS 2', this)">XII IPS 2</div>
            <div class="dropdown-item" onclick="pilihKelas('XII IPS 3', this)">XII IPS 3</div>
        </div>
    </div>

    <!-- DROPDOWN STATUS -->
    <div class="custom-dropdown" id="dropdownStatus">
        <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dropdownStatus')">
            <span id="labelStatus">Semua Status</span>
            <span class="dropdown-arrow">▼</span>
        </button>
        <div class="dropdown-menu">
            <div class="dropdown-item active" onclick="pilihStatus('Semua Status', this)">Semua Status</div>
            <div class="dropdown-item" onclick="pilihStatus('lunas', this)">Lunas</div>
            <div class="dropdown-item" onclick="pilihStatus('belum', this)">Belum Lunas</div>
            <div class="dropdown-item" onclick="pilihStatus('cicilan', this)">Cicilan</div>
        </div>
    </div>
</div>

        {{-- TABLE --}}
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis ?? [] as $t)
                    <tr>
                        <td>{{ $t->siswa->nis ?? '-' }}</td>
                        <td>{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $t->siswa->kelas ?? '-' }}</td>
                        <td>{{ $t->bulan }}</td>
                        <td>{{ $t->tahun }}</td>
                        <td>Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ $t->status }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="aksi-group">
                                <button class="btn-detail"
                                    onclick="openDetail({{ $t->id }})">
                                    <i class="fa fa-eye"></i> Detail
                                </button>
                                <button class="btn-hapus"
                                    onclick="openHapus({{ $t->id }}, '{{ $t->siswa->nama_lengkap ?? '' }}')">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:28px; color:#7b6a58;">
                            Belum ada data transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-box">
                <div class="pagination-left">
                    @if(isset($transaksis) && $transaksis->onFirstPage())
                        <span class="page-nav disabled">‹ Previous</span>
                    @elseif(isset($transaksis))
                        <a href="{{ $transaksis->previousPageUrl() }}" class="page-nav">‹ Previous</a>
                    @endif

                    @if(isset($transaksis))
                        @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="page-num {{ $page == $transaksis->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach
                    @endif

                    @if(isset($transaksis) && $transaksis->hasMorePages())
                        <a href="{{ $transaksis->nextPageUrl() }}" class="page-nav">Next ›</a>
                    @elseif(isset($transaksis))
                        <span class="page-nav disabled">Next ›</span>
                    @endif
                </div>
                <div>
                    @if(isset($transaksis))
                        Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }} data
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===================== MODAL TAMBAH PEMBAYARAN ===================== --}}
<div class="modal-overlay" id="modalTambah" onclick="overlayClose(event,'modalTambah')">
    <div class="modal-box">
        <h3 class="modal-title">Pembayaran Baru</h3>
        <p class="modal-sub">Cari siswa lalu input data pembayaran SPP.</p>

        <form id="formTambah" method="POST" action="{{ route('transaksi.store') }}">
            @csrf

            {{-- CARI SISWA --}}
            <div class="form-group">
                <label>Cari NIS Siswa</label>
                <div class="nis-search-box">
                    <input type="text" id="inputNIS" class="form-input" placeholder="Ketik NIS siswa...">
                    <button type="button" class="btn-cari" onclick="cariSiswa()">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
            </div>

            {{-- INFO SISWA (muncul setelah cari) --}}
            <div id="infoSiswa" style="display:none;">
                <div class="info-card">
                    <div class="info-card-title">Data Siswa</div>
                    <div class="info-row"><span>NIS</span><span id="sSiswa_nis">-</span></div>
                    <div class="info-row"><span>Nama</span><span id="sSiswa_nama">-</span></div>
                    <div class="info-row"><span>Kelas</span><span id="sSiswa_kelas">-</span></div>
                    <div class="info-row"><span>Jurusan</span><span id="sSiswa_jurusan">-</span></div>
                </div>
                <input type="hidden" name="siswa_id" id="inputSiswaId">

                {{-- INFO TAGIHAN SPP --}}
                <div class="info-card">
                    <div class="info-card-title">Tagihan SPP</div>
                    <div class="info-row"><span>Tahun</span><span id="sSPP_tahun">-</span></div>
                    <div class="info-row"><span>Nominal</span><span id="sSPP_nominal">-</span></div>
                    <div class="info-row"><span>Status</span><span id="sSPP_status">-</span></div>
                </div>

                {{-- INPUT PEMBAYARAN --}}
                <div class="form-grid">
                    <div class="form-group">
                        <label>Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label>Bulan</label>
                        <select name="bulan" class="form-input form-select" required>
                            <option value="">Pilih Bulan</option>
                            <option>Januari</option><option>Februari</option><option>Maret</option>
                            <option>April</option><option>Mei</option><option>Juni</option>
                            <option>Juli</option><option>Agustus</option><option>September</option>
                            <option>Oktober</option><option>November</option><option>Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-input" placeholder="2025" min="2020" max="2030" required>
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode" class="form-input form-select" required>
                            <option value="">Pilih Metode</option>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Jumlah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar" class="form-input" placeholder="500000" min="0" required>
                    </div>
                </div>

                <div class="info-notice">
                    <i class="fa fa-info-circle"></i>
                    Pastikan jumlah pembayaran sesuai dengan tagihan yang harus dibayarkan.
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-modal-batal" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-modal-simpan" id="btnSimpan" style="display:none;">
                    <i class="fa fa-check"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL DETAIL PEMBAYARAN ===================== --}}
<div class="modal-overlay" id="modalDetail" onclick="overlayClose(event,'modalDetail')">
    <div class="modal-box" id="kontenDetail">
        <h3 class="modal-title">Detail Pembayaran</h3>
        <p class="modal-sub">Informasi lengkap transaksi pembayaran siswa.</p>

        <div class="info-card">
            <div class="info-card-title">Data Siswa</div>
            <div class="info-row"><span>NIS</span><span id="dNIS">-</span></div>
            <div class="info-row"><span>Nama</span><span id="dNama">-</span></div>
            <div class="info-row"><span>Kelas</span><span id="dKelas">-</span></div>
        </div>

        <div class="ringkasan-card">
            <div class="info-card-title">Ringkasan Pembayaran</div>
            <div class="ringkasan-row"><span>Tahun</span><span id="dTahun">-</span></div>
            <div class="ringkasan-row"><span>Bulan</span><span id="dBulan">-</span></div>
            <div class="ringkasan-row"><span>Jumlah Bayar</span><span id="dJumlah">-</span></div>
            <div class="ringkasan-row"><span>Metode</span><span id="dMetode">-</span></div>
            <div class="ringkasan-row"><span>Status</span><span id="dStatus">-</span></div>
            <div class="ringkasan-row"><span>Tanggal Bayar</span><span id="dTanggal">-</span></div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-modal-batal" onclick="closeModal('modalDetail')">Tutup</button>
            <button type="button" class="btn-modal-simpan" onclick="cetakBukti()">
                <i class="fa fa-download"></i> Unduh PDF
            </button>
        </div>
    </div>
</div>

{{-- ===================== MODAL HAPUS ===================== --}}
<div class="modal-overlay" id="modalHapus" onclick="overlayClose(event,'modalHapus')">
    <div class="modal-box modal-sm" style="text-align:center;">

        <div style="width:64px;height:64px;background:#fde8e8;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fa fa-trash" style="color:#d64545;font-size:24px;"></i>
        </div>

        <h3 class="modal-title" style="text-align:center;">Hapus Transaksi</h3>
        <p class="modal-sub" id="hapusMsg" style="text-align:center;">Apakah Anda yakin?</p>

        <form id="formHapus" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-actions" style="justify-content:center;">
                <button type="button" class="btn-modal-batal" onclick="closeModal('modalHapus')">Batal</button>
                <button type="submit" class="btn-modal-hapus">
                    <i class="fa fa-trash"></i> Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const BASE = '{{ url("/") }}';
const CSRF = '{{ csrf_token() }}';

// ======= DROPDOWN FILTER =======
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
        const menuW = 170;

        menu.style.position = 'fixed';
        menu.style.top = (rect.bottom + 6) + 'px';
        menu.style.minWidth = rect.width + 'px';

        if (rect.left + menuW > window.innerWidth) {
            menu.style.left = 'auto';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
        } else {
            menu.style.left = rect.left + 'px';
            menu.style.right = 'auto';
        }
    }
}

document.addEventListener('scroll', function(e) {
    if (!e.target.closest || !e.target.classList.contains('dropdown-menu')) {
        document.querySelectorAll('.custom-dropdown').forEach(d => {
            d.classList.remove('open');
            const m = d.querySelector('.dropdown-menu');
            if (m) m.removeAttribute('style');
        });
    }
}, true);

document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.custom-dropdown').forEach(d => {
            d.classList.remove('open');
            const m = d.querySelector('.dropdown-menu');
            if (m) m.removeAttribute('style');
        });
    }
});

document.querySelectorAll('.dropdown-menu').forEach(menu => {
    menu.addEventListener('wheel', function(e) { e.stopPropagation(); }, { passive: true });
});

let selectedKelas = 'Semua Kelas';
let selectedStatus = 'Semua Status';

function pilihKelas(nilai, el) {
    selectedKelas = nilai;
    document.getElementById('labelKelas').textContent = nilai;
    document.querySelectorAll('#dropdownKelas .dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('dropdownKelas').classList.remove('open');
    filterTable();
}

function pilihStatus(nilai, el) {
    selectedStatus = nilai;
    document.getElementById('labelStatus').textContent = nilai === 'Semua Status' ? 'Semua Status' :
        nilai === 'lunas' ? 'Lunas' : nilai === 'belum' ? 'Belum Lunas' : 'Cicilan';
    document.querySelectorAll('#dropdownStatus .dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('dropdownStatus').classList.remove('open');
    filterTable();
}

function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 7) return;

        const nis   = cells[0]?.textContent.toLowerCase() || '';
        const nama  = cells[1]?.textContent.toLowerCase() || '';
        const kelas = cells[2]?.textContent.trim() || '';
        const status = cells[6]?.textContent.trim().toLowerCase() || '';

        const matchSearch = nis.includes(search) || nama.includes(search);
        const matchKelas  = selectedKelas === 'Semua Kelas' || kelas === selectedKelas;
        const matchStatus = selectedStatus === 'Semua Status' || status.includes(selectedStatus);

        row.style.display = matchSearch && matchKelas && matchStatus ? '' : 'none';
    });
}

// ======= MODAL =======
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function overlayClose(e, id) { if (e.target.id === id) closeModal(id); }

// ===== CARI SISWA =====
async function cariSiswa() {
    const nis = document.getElementById('inputNIS').value.trim();
    if (!nis) return alert('Masukkan NIS terlebih dahulu!');
    try {
        const res = await fetch(`${BASE}/transaksi/cari-siswa?nis=${nis}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (!data.siswa) { alert('Siswa tidak ditemukan!'); return; }
        document.getElementById('sSiswa_nis').textContent     = data.siswa.nis;
        document.getElementById('sSiswa_nama').textContent    = data.siswa.nama_lengkap;
        document.getElementById('sSiswa_kelas').textContent   = data.siswa.kelas;
        document.getElementById('sSiswa_jurusan').textContent = data.siswa.jurusan;
        document.getElementById('inputSiswaId').value         = data.siswa.id;
        if (data.spp) {
            document.getElementById('sSPP_tahun').textContent   = data.spp.tahun;
            document.getElementById('sSPP_nominal').textContent = 'Rp ' + parseInt(data.spp.nominal).toLocaleString('id-ID');
            document.getElementById('sSPP_status').textContent  = data.status_tagihan ?? '-';
        }
        document.getElementById('infoSiswa').style.display = 'block';
        document.getElementById('btnSimpan').style.display = 'flex';
    } catch(err) { console.error(err); alert('Terjadi kesalahan.'); }
}

// ===== DETAIL =====
async function openDetail(id) {
    try {
        const res = await fetch(`${BASE}/transaksi/${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        const t = data.transaksi;
        document.getElementById('dNIS').textContent     = t.siswa?.nis ?? '-';
        document.getElementById('dNama').textContent    = t.siswa?.nama_lengkap ?? '-';
        document.getElementById('dKelas').textContent   = t.siswa?.kelas ?? '-';
        document.getElementById('dTahun').textContent   = t.tahun;
        document.getElementById('dBulan').textContent   = t.bulan;
        document.getElementById('dJumlah').textContent  = 'Rp ' + parseInt(t.jumlah_bayar).toLocaleString('id-ID');
        document.getElementById('dMetode').textContent  = t.metode;
        document.getElementById('dStatus').textContent  = t.status;
        document.getElementById('dTanggal').textContent = t.tanggal_bayar;
        openModal('modalDetail');
    } catch(err) { console.error(err); }
}

// ===== HAPUS =====
function openHapus(id, nama) {
    document.getElementById('hapusMsg').innerHTML =
        `Data transaksi siswa <strong>"${nama}"</strong> akan dihapus dan tidak dapat dikembalikan.`;
    document.getElementById('formHapus').action = `${BASE}/transaksi/${id}`;
    openModal('modalHapus');
}

window.addEventListener('DOMContentLoaded', () => {
    const al = document.getElementById('alertMsg');
    if (al) setTimeout(() => al.style.display = 'none', 4000);
});

async function cetakBukti() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');
    const W = pdf.internal.pageSize.getWidth();
    
    // Data dari modal
    const nis     = document.getElementById('dNIS').textContent;
    const nama    = document.getElementById('dNama').textContent;
    const kelas   = document.getElementById('dKelas').textContent;
    const tahun   = document.getElementById('dTahun').textContent;
    const bulan   = document.getElementById('dBulan').textContent;
    const jumlah  = document.getElementById('dJumlah').textContent;
    const metode  = document.getElementById('dMetode').textContent;
    const status  = document.getElementById('dStatus').textContent;
    const tanggal = document.getElementById('dTanggal').textContent;
    const noTrans = 'TRX-' + Date.now().toString().slice(-8);
    const now     = new Date().toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'});

    let y = 15;

    // ===== HEADER =====
    pdf.setFontSize(16);
    pdf.setFont('helvetica', 'bold');
    pdf.text('SISTEM PEMBAYARAN SPP', W / 2, y, { align: 'center' });
    y += 6;

    pdf.setFontSize(10);
    pdf.setFont('helvetica', 'normal');
    pdf.text('Jl. Pendidikan No. 1 | Telp. 021-0000000 | Email: admin@admin.sch.id', W / 2, y, { align: 'center' });
    y += 4;

    // Garis header
    pdf.setLineWidth(0.8);
    pdf.line(15, y, W - 15, y);
    y += 1;
    pdf.setLineWidth(0.3);
    pdf.line(15, y, W - 15, y);
    y += 7;

    // ===== JUDUL =====
    pdf.setFontSize(13);
    pdf.setFont('helvetica', 'bold');
    pdf.text('BUKTI PEMBAYARAN SPP', W / 2, y, { align: 'center' });
    y += 10;

    // ===== INFO TRANSAKSI =====
    pdf.setFontSize(10);
    pdf.setFont('helvetica', 'normal');

    const col1 = 15;
    const col2 = 50;
    const col3 = W / 2 + 5;
    const col4 = W / 2 + 40;

    pdf.text('NO TRANS', col1, y);
    pdf.text(': ' + noTrans, col2, y);
    pdf.text('NIS', col3, y);
    pdf.text(': ' + nis, col4, y);
    y += 6;

    pdf.text('TANGGAL', col1, y);
    pdf.text(': ' + tanggal, col2, y);
    pdf.text('NAMA SISWA', col3, y);
    pdf.text(': ' + nama, col4, y);
    y += 6;

    pdf.text('JAM CETAK', col1, y);
    pdf.text(': ' + new Date().toLocaleTimeString('id-ID'), col2, y);
    pdf.text('KELAS', col3, y);
    pdf.text(': ' + kelas, col4, y);
    y += 8;

    // ===== GARIS =====
    pdf.setLineWidth(0.3);
    pdf.line(15, y, W - 15, y);
    y += 6;

    // ===== TABEL PEMBAYARAN =====
    pdf.setFont('helvetica', 'bold');
    pdf.text('No.', 15, y);
    pdf.text('Keterangan Pembayaran', 28, y);
    pdf.text('Jumlah (Rp.)', W - 15, y, { align: 'right' });
    y += 4;

    pdf.setLineWidth(0.3);
    pdf.line(15, y, W - 15, y);
    y += 6;

    pdf.setFont('helvetica', 'normal');
    pdf.text('1.', 15, y);
    pdf.text(`Pembayaran SPP - ${bulan} ${tahun}`, 28, y);
    pdf.text(jumlah, W - 15, y, { align: 'right' });
    y += 6;

    pdf.text('2.', 15, y);
    pdf.text(`Metode Pembayaran: ${metode}`, 28, y);
    pdf.text('-', W - 15, y, { align: 'right' });
    y += 6;

    pdf.line(15, y, W - 15, y);
    y += 6;

    // ===== GRAND TOTAL =====
    pdf.setFont('helvetica', 'bold');
    pdf.text('Grand Total :', W / 2 + 10, y);
    pdf.text(jumlah, W - 15, y, { align: 'right' });
    y += 10;

    // ===== STATUS =====
    pdf.setFont('helvetica', 'normal');
    pdf.setFontSize(9);
    pdf.text('Status Pembayaran : ' + status.toUpperCase(), 15, y);
    y += 14;

    // ===== TANDA TANGAN =====
    pdf.setFontSize(10);
    pdf.text('Catatan :', 15, y);
    y += 5;
    pdf.text('- Simpan sebagai bukti pembayaran yang SAH.', 15, y);
    y += 5;
    pdf.text('- Uang yang sudah dibayarkan tidak dapat diminta kembali.', 15, y);
    y += 5;
    pdf.text('- Struk ini berlaku sebagai tanda terima resmi.', 15, y);
    y += 14;

    pdf.text(now, W - 15, y, { align: 'right' });
    y += 5;
    pdf.text('Yang Menerima,', W - 15, y, { align: 'right' });
    y += 22;

    pdf.setFont('helvetica', 'bold');
    pdf.text('Admin SPP', W - 15, y, { align: 'right' });
    y += 5;
    pdf.setFont('helvetica', 'normal');
    pdf.setFontSize(9);
    pdf.text('Bendahara Sekolah', W - 15, y, { align: 'right' });
    y += 12;

    // ===== FOOTER =====
    pdf.setLineWidth(0.3);
    pdf.line(15, y, W - 15, y);
    y += 5;
    pdf.setFontSize(8);
    pdf.setFont('helvetica', 'italic');
    pdf.text('Dokumen ini dicetak secara otomatis oleh Sistem Pembayaran SPP', W / 2, y, { align: 'center' });

    // ===== SIMPAN =====
    pdf.save(`bukti-pembayaran-${nis}-${bulan}-${tahun}.pdf`);
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

@endsection
