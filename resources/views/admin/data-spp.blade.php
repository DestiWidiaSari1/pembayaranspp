@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/data-spp.css') }}">

<div class="page-header">
    <div>
        <h2>Data SPP</h2>
        <p>Kelola data SPP sekolah sebagai acuan pembayaran.</p>
    </div>
    <button class="btn-primary" onclick="openModal('modalTambah')">
        + Tambah SPP
    </button>
</div>

@if(session('success'))
<div class="alert-success" id="alertMsg">
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="content-box">

    {{-- FILTER --}}
    <form method="GET" action="{{ route('data-spp') }}" id="filterForm">
        <div class="filter-box">
            <div class="search-box">
                <input type="text" name="search" placeholder="Cari kode / nominal..."
                    value="{{ request('search') }}"
                    onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                <button type="button" class="btn-search" onclick="document.getElementById('filterForm').submit()">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>ID SPP</th>
                    <th>Tingkat</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spps as $spp)
                <tr>
                    <td>{{ $spp->kode }}</td>
                    <td>Kelas {{ $spp->tingkat }}</td>
                    <td>Rp {{ number_format($spp->nominal, 0, ',', '.') }}</td>
                    <td>
                        <div class="aksi-group">
                            <button class="btn-edit"
                                onclick="openEdit({{ $spp->id }}, '{{ $spp->kode }}', '{{ $spp->tingkat }}', '{{ $spp->nominal }}')">
                                <i class="fa fa-pen"></i> Edit
                            </button>
                            <button class="btn-delete"
                                onclick="openHapus({{ $spp->id }}, '{{ $spp->kode }}')">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:28px;color:#7b6a58;">
                        Tidak ada data SPP ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-box">
            <div class="pagination-left">
                @if($spps->onFirstPage())
                    <span class="page-nav disabled">‹ Previous</span>
                @else
                    <a href="{{ $spps->previousPageUrl() }}" class="page-nav">‹ Previous</a>
                @endif
                @foreach($spps->getUrlRange(1, $spps->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-num {{ $page == $spps->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($spps->hasMorePages())
                    <a href="{{ $spps->nextPageUrl() }}" class="page-nav">Next ›</a>
                @else
                    <span class="page-nav disabled">Next ›</span>
                @endif
            </div>
            <div>
                Menampilkan {{ $spps->firstItem() ?? 0 }} sampai {{ $spps->lastItem() ?? 0 }} dari {{ $spps->total() }} data
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah" onclick="overlayClose(event,'modalTambah')">
    <div class="modal-box">
        <h3 class="modal-title">Tambah SPP</h3>
        <p class="modal-sub">Lengkapi informasi SPP baru.</p>
        <form id="formTambah" onsubmit="submitTambah(event)">
            @csrf
            <div class="form-group">
                <label>ID SPP</label>
                <input type="text" id="tKode" name="kode" class="form-input" placeholder="Contoh: SPP001">
                <span class="ferr" id="eTKode"></span>
            </div>
            <div class="form-group">
                <label>Tingkat Kelas</label>
                <div class="custom-select-wrapper" id="wrapTingkatTambah">
                    <button type="button" class="custom-select-btn" onclick="toggleSelect('dropTingkatTambah')">
                        <span id="labelTingkatTambah">Pilih Tingkat</span>
                        <span>▼</span>
                    </button>
                    <div class="custom-select-dropdown" id="dropTingkatTambah">
                        <div class="custom-select-header">Pilih Tingkat</div>
                        <div class="custom-select-item" onclick="pilihTingkat('tambah','X')">Kelas X</div>
                        <div class="custom-select-item" onclick="pilihTingkat('tambah','XI')">Kelas XI</div>
                        <div class="custom-select-item" onclick="pilihTingkat('tambah','XII')">Kelas XII</div>
                    </div>
                    <input type="hidden" name="tingkat" id="tTingkat">
                </div>
                <span class="ferr" id="eTTingkat"></span>
            </div>
            <div class="form-group">
                <label>Nominal</label>
                <div class="input-nominal">
                    <span class="nominal-prefix">Rp</span>
                    <input type="number" id="tNominal" name="nominal" class="form-input input-with-prefix" placeholder="Contoh: 500000" min="0">
                </div>
                <span class="ferr" id="eTNominal"></span>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-modal-batal" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-modal-simpan">
                    <i class="fa fa-check"></i> Simpan SPP
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit" onclick="overlayClose(event,'modalEdit')">
    <div class="modal-box">
        <h3 class="modal-title">Edit SPP</h3>
        <p class="modal-sub">Ubah informasi SPP.</p>
        <form id="formEdit" onsubmit="submitEdit(event)">
            @csrf
            <input type="hidden" id="eId">
            <div class="form-group">
                <label>ID SPP</label>
                <input type="text" id="eKode" name="kode" class="form-input" placeholder="Contoh: SPP001">
                <span class="ferr" id="eEKode"></span>
            </div>
            <div class="form-group">
                <label>Tingkat Kelas</label>
                <div class="custom-select-wrapper" id="wrapTingkatEdit">
                    <button type="button" class="custom-select-btn" onclick="toggleSelect('dropTingkatEdit')">
                        <span id="labelTingkatEdit">Pilih Tingkat</span>
                        <span>▼</span>
                    </button>
                    <div class="custom-select-dropdown" id="dropTingkatEdit">
                        <div class="custom-select-header">Pilih Tingkat</div>
                        <div class="custom-select-item" onclick="pilihTingkat('edit','X')">Kelas X</div>
                        <div class="custom-select-item" onclick="pilihTingkat('edit','XI')">Kelas XI</div>
                        <div class="custom-select-item" onclick="pilihTingkat('edit','XII')">Kelas XII</div>
                    </div>
                    <input type="hidden" name="tingkat" id="eTingkat">
                </div>
                <span class="ferr" id="eETingkat"></span>
            </div>
            <div class="form-group">
                <label>Nominal</label>
                <div class="input-nominal">
                    <span class="nominal-prefix">Rp</span>
                    <input type="number" id="eNominal" name="nominal" class="form-input input-with-prefix" placeholder="Contoh: 500000" min="0">
                </div>
                <span class="ferr" id="eENominal"></span>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-modal-batal" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-modal-simpan">
                    <i class="fa fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal-overlay" id="modalHapus" onclick="overlayClose(event,'modalHapus')">
    <div class="modal-box modal-sm" style="text-align:center;">
        <div class="hapus-icon-wrap">
            <i class="fa fa-trash hapus-icon"></i>
        </div>
        <h3 class="modal-title" style="text-align:center;margin-bottom:8px;">Hapus Data SPP</h3>
        <p class="modal-sub" id="hapusMsg" style="text-align:center;margin-bottom:24px;"></p>
        <div class="modal-actions" style="justify-content:center;">
            <button type="button" class="btn-modal-batal" onclick="closeModal('modalHapus')">Batal</button>
            <button type="button" class="btn-modal-hapus" onclick="submitHapus()">
                <i class="fa fa-trash"></i> Hapus
            </button>
        </div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}', BASE = '{{ url("/") }}';
let hapusId = null;

function toggleSelect(id) {
    document.querySelectorAll('.custom-select-dropdown').forEach(d => {
        if (d.id !== id) d.classList.remove('open');
    });
    document.getElementById(id).classList.toggle('open');
}

function pilihTingkat(mode, nilai) {
    if (mode === 'tambah') {
        document.getElementById('labelTingkatTambah').textContent = 'Kelas ' + nilai;
        document.getElementById('tTingkat').value = nilai;
        document.getElementById('dropTingkatTambah').classList.remove('open');
    } else {
        document.getElementById('labelTingkatEdit').textContent = 'Kelas ' + nilai;
        document.getElementById('eTingkat').value = nilai;
        document.getElementById('dropTingkatEdit').classList.remove('open');
    }
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-select-wrapper')) {
        document.querySelectorAll('.custom-select-dropdown').forEach(d => d.classList.remove('open'));
    }
});

function openModal(id) { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
function overlayClose(e, id) { if (e.target.id === id) closeModal(id); }
function clearErr(ids) { ids.forEach(id => { const el = document.getElementById(id); if (el) el.textContent = ''; }); }

function openEdit(id, kode, tingkat, nominal) {
    document.getElementById('eId').value = id;
    document.getElementById('eKode').value = kode;
    document.getElementById('eTingkat').value = tingkat;
    document.getElementById('labelTingkatEdit').textContent = 'Kelas ' + tingkat;
    document.getElementById('eNominal').value = nominal;
    clearErr(['eEKode', 'eETingkat', 'eENominal']);
    openModal('modalEdit');
}

function openHapus(id, kode) {
    hapusId = id;
    document.getElementById('hapusMsg').textContent = `Data SPP "${kode}" akan dihapus dan tidak dapat dikembalikan.`;
    openModal('modalHapus');
}

async function submitTambah(e) {
    e.preventDefault();
    clearErr(['eTKode', 'eTTingkat', 'eTNominal']);
    const fd = new FormData(document.getElementById('formTambah'));
    try {
        const res = await fetch(`${BASE}/spp`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: fd });
        const json = await res.json();
        if (res.ok && json.success) { closeModal('modalTambah'); location.reload(); }
        else if (res.status === 422) {
            const err = json.errors || {};
            if (err.kode) document.getElementById('eTKode').textContent = err.kode[0];
            if (err.tingkat) document.getElementById('eTTingkat').textContent = err.tingkat[0];
            if (err.nominal) document.getElementById('eTNominal').textContent = err.nominal[0];
        }
    } catch (err) { console.error(err); }
}

async function submitEdit(e) {
    e.preventDefault();
    clearErr(['eEKode', 'eETingkat', 'eENominal']);
    const id = document.getElementById('eId').value;
    const fd = new FormData(document.getElementById('formEdit'));
    fd.append('_method', 'PUT');
    try {
        const res = await fetch(`${BASE}/spp/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: fd });
        const json = await res.json();
        if (res.ok && json.success) { closeModal('modalEdit'); location.reload(); }
        else if (res.status === 422) {
            const err = json.errors || {};
            if (err.kode) document.getElementById('eEKode').textContent = err.kode[0];
            if (err.tingkat) document.getElementById('eETingkat').textContent = err.tingkat[0];
            if (err.nominal) document.getElementById('eENominal').textContent = err.nominal[0];
        }
    } catch (err) { console.error(err); }
}

async function submitHapus() {
    if (!hapusId) return;
    try {
        const res = await fetch(`${BASE}/spp/${hapusId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ _method: 'DELETE' }) });
        const json = await res.json();
        if (json.success) { closeModal('modalHapus'); location.reload(); }
    } catch (err) { console.error(err); }
}

window.addEventListener('DOMContentLoaded', () => {
    const al = document.getElementById('alertMsg');
    if (al) setTimeout(() => al.style.display = 'none', 4000);
});
</script>
@endsection