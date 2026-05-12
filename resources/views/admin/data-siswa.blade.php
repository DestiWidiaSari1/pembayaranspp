@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/data-siswa.css') }}">

<div class="page-header">
    <div class="header-left">
        <h2>Data Siswa</h2>
        <p>Kelola data siswa sekolah.</p>
    </div>
    <div class="header-right">
        <button class="btn-primary" onclick="openModal('modalTambah')">
            + Tambah Siswa
        </button>
    </div>
</div>

<div class="content-box">

   <!-- FILTER -->
<!-- FILTER -->
<div class="filter-box">
    <div class="search-box">
        <i class="fa fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari siswa..." oninput="filterTable()">
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

    <!-- DROPDOWN JURUSAN -->
    <div class="custom-dropdown" id="dropdownJurusan">
        <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dropdownJurusan')">
            <span id="labelJurusan">Semua Jurusan</span>
            <span class="dropdown-arrow">▼</span>
        </button>
        <div class="dropdown-menu">
            <div class="dropdown-item active" onclick="pilihJurusan('Semua Jurusan', this)">Semua Jurusan</div>
            <div class="dropdown-item" onclick="pilihJurusan('IPA', this)">IPA</div>
            <div class="dropdown-item" onclick="pilihJurusan('IPS', this)">IPS</div>
        </div>
    </div>
</div>

    <!-- TABLE -->
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($siswas as $siswa)
                <tr>
                    <td>{{ $siswa->nis }}</td>
                    <td>{{ $siswa->nama_lengkap }}</td>
                    <td>{{ $siswa->kelas }}</td>
                    <td>{{ $siswa->jurusan }}</td>
                    <td>{{ $siswa->email }}</td>
                    <td>
                        <button class="btn-edit" onclick="openEditModal({{ $siswa->id }}, '{{ $siswa->nis }}', '{{ $siswa->nama_lengkap }}', '{{ $siswa->kelas }}', '{{ $siswa->jurusan }}', '{{ $siswa->email }}')">
                            <i class="fa fa-pen"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="openHapusModal({{ $siswa->id }}, '{{ $siswa->nama_lengkap }}')">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="pagination-box">
            <div class="pagination-left">
                {{ $siswas->links() }}
            </div>
            <div class="pagination-right">
                Menampilkan {{ $siswas->firstItem() }} sampai {{ $siswas->lastItem() }} dari {{ $siswas->total() }} data
            </div>
        </div>
    </div>
</div>
<!-- ===================== MODAL TAMBAH SISWA ===================== -->
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-text">
                <h3>Tambah Siswa</h3>
                <p class="modal-subtitle">Lengkapi data siswa baru.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        </div>
        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nis</label>
                    <input type="text" name="nis" placeholder="Masukan Nis" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" placeholder="Masukan Nama Lengkap Siswa" required>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <div class="modal-dropdown" id="modalDropdownKelas">
                        <button type="button" class="modal-dropdown-toggle" onclick="toggleModalDropdown('modalDropdownKelas')">
                            <span id="labelModalKelas">Pilih Kelas</span>
                            <span class="dropdown-arrow">▼</span>
                        </button>
                        <input type="hidden" name="kelas" id="inputModalKelas">
                        <div class="modal-dropdown-menu">
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('X IPA 1')">X IPA 1</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('X IPA 2')">X IPA 2</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('X IPA 3')">X IPA 3</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XI IPA 1')">XI IPA 1</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XI IPA 2')">XI IPA 2</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XI IPA 3')">XI IPA 3</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XII IPA 1')">XII IPA 1</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XII IPA 2')">XII IPA 2</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XII IPA 3')">XII IPA 3</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('X IPS 1')">X IPS 1</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('X IPS 2')">X IPS 2</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('X IPS 3')">X IPS 3</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XI IPS 1')">XI IPS 1</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XI IPS 2')">XI IPS 2</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XI IPS 3')">XI IPS 3</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XII IPS 1')">XII IPS 1</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XII IPS 2')">XII IPS 2</div>
                            <div class="modal-dropdown-item" onclick="pilihModalKelas('XII IPS 3')">XII IPS 3</div>
                        </div>
                    </div>
                </div>
            <div class="form-group">
                <label>Jurusan</label>
                <div class="modal-dropdown" id="modalDropdownJurusan">
                    <button type="button" class="modal-dropdown-toggle" onclick="toggleModalDropdown('modalDropdownJurusan')">
                        <span id="labelModalJurusan">Pilih Jurusan</span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <input type="hidden" name="jurusan" id="inputModalJurusan">
                    <div class="modal-dropdown-menu">
                        <div class="modal-dropdown-item" onclick="pilihModalJurusan('IPA')">IPA</div>
                        <div class="modal-dropdown-item" onclick="pilihModalJurusan('IPS')">IPS</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Email <span style="font-size:12px;color:#9a8c7e;">(opsional, untuk reset kata sandi)</span></label>
            <input type="email" name="email" placeholder="Masukan Email Siswa">
        </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-save">
                    <i class="fa fa-floppy-disk"></i> Simpan Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL EDIT SISWA ===================== -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-text">
                <h3>Edit Siswa</h3>
                <p class="modal-subtitle">Ubah data siswa.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Nis</label>
                    <input type="text" name="nis" id="editNis" placeholder="Masukan Nis" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="editNama" placeholder="Masukan Nama Lengkap Siswa" required>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <div class="modal-dropdown" id="editDropdownKelas">
                        <button type="button" class="modal-dropdown-toggle" onclick="toggleModalDropdown('editDropdownKelas')">
                            <span id="labelEditKelas">Pilih Kelas</span>
                            <span class="dropdown-arrow">▼</span>
                        </button>
                        <input type="hidden" name="kelas" id="inputEditKelas">
                        <div class="modal-dropdown-menu">
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('X IPA 1')">X IPA 1</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('X IPA 2')">X IPA 2</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('X IPA 3')">X IPA 3</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XI IPA 1')">XI IPA 1</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XI IPA 2')">XI IPA 2</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XI IPA 3')">XI IPA 3</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XII IPA 1')">XII IPA 1</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XII IPA 2')">XII IPA 2</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XII IPA 3')">XII IPA 3</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('X IPS 1')">X IPS 1</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('X IPS 2')">X IPS 2</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('X IPS 3')">X IPS 3</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XI IPS 1')">XI IPS 1</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XI IPS 2')">XI IPS 2</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XI IPS 3')">XI IPS 3</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XII IPS 1')">XII IPS 1</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XII IPS 2')">XII IPS 2</div>
                            <div class="modal-dropdown-item" onclick="pilihEditKelas('XII IPS 3')">XII IPS 3</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <div class="modal-dropdown" id="editDropdownJurusan">
                        <button type="button" class="modal-dropdown-toggle" onclick="toggleModalDropdown('editDropdownJurusan')">
                            <span id="labelEditJurusan">Pilih Jurusan</span>
                            <span class="dropdown-arrow">▼</span>
                        </button>
                        <input type="hidden" name="jurusan" id="inputEditJurusan">
                        <div class="modal-dropdown-menu">
                            <div class="modal-dropdown-item" onclick="pilihEditJurusan('IPA')">IPA</div>
                            <div class="modal-dropdown-item" onclick="pilihEditJurusan('IPS')">IPS</div>
                        </div>
                    </div>
                </div>
            </div> {{-- TUTUP modal-body DI SINI --}}
            <div class="form-group">
                <label>Email <span style="font-size:12px;color:#9a8c7e;">(opsional, untuk reset kata sandi)</span></label>
                <input type="email" name="email" id="editEmail" placeholder="Masukan Email Siswa">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">
                    <i class="fa fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL HAPUS KONFIRMASI ===================== -->
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box modal-sm">
        <div class="modal-body" style="text-align:center; padding: 32px 24px 24px;">
            
            <div style="
                width: 64px; height: 64px;
                background: #fde8e8;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 18px;
            ">
                <i class="fa fa-trash" style="color:#d64545; font-size:24px;"></i>
            </div>

            <h3 style="font-size:18px; font-weight:700; color:#4b3b2a; margin-bottom:10px;">Hapus Data Siswa</h3>
            <p id="deleteMessage" style="font-size:14px; color:#7a6d5c; line-height:1.6; margin-bottom:0;"></p>

        </div>
        <div class="modal-footer" style="justify-content:center; border-top: 1px solid #e8d9c5; padding: 16px 24px 20px;">
            <button type="button" class="btn-cancel" onclick="closeModal('modalHapus')">Batal</button>
            <form id="formHapus" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-hapus-confirm">
                    <i class="fa fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // ======= DROPDOWN =======
   function toggleDropdown(id) {
    const dd = document.getElementById(id);
    const isOpen = dd.classList.contains('open');
    
    // Tutup semua
    document.querySelectorAll('.custom-dropdown').forEach(d => {
        d.classList.remove('open');
        const m = d.querySelector('.dropdown-menu');
        m.removeAttribute('style');
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
        
        // Sejajar kiri tombol, tapi jika keluar layar kanan maka geser ke kiri
        const leftPos = rect.left;
        if (leftPos + menuW > window.innerWidth) {
            menu.style.left = 'auto';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
        } else {
            menu.style.left = leftPos + 'px';
            menu.style.right = 'auto';
        }
    }
}
// Tutup dropdown saat scroll di LUAR dropdown
document.addEventListener('scroll', function(e) {
    // Jika yang discroll bukan dropdown-menu, baru tutup
    if (!e.target.closest || !e.target.classList.contains('dropdown-menu')) {
        document.querySelectorAll('.custom-dropdown').forEach(d => {
            d.classList.remove('open');
            const m = d.querySelector('.dropdown-menu');
            m.removeAttribute('style');
        });
    }
}, true);

// Mouse wheel scroll di dalam dropdown
document.querySelectorAll('.dropdown-menu').forEach(menu => {
    menu.addEventListener('wheel', function(e) {
        e.stopPropagation();
    }, { passive: true });
});

// ======= MODAL DROPDOWN =======
function toggleModalDropdown(id) {
    const dd = document.getElementById(id);
    const isOpen = dd.classList.contains('open');
    document.querySelectorAll('.modal-dropdown').forEach(d => d.classList.remove('open'));
    if (!isOpen) dd.classList.add('open');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.modal-dropdown') && !e.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.modal-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('open'));
    }
});

function pilihModalKelas(nilai) {
    document.getElementById('labelModalKelas').textContent = nilai;
    document.getElementById('inputModalKelas').value = nilai;
    document.getElementById('modalDropdownKelas').classList.remove('open');
}

function pilihModalJurusan(nilai) {
    document.getElementById('labelModalJurusan').textContent = nilai;
    document.getElementById('inputModalJurusan').value = nilai;
    document.getElementById('modalDropdownJurusan').classList.remove('open');
}

function pilihEditKelas(nilai) {
    document.getElementById('labelEditKelas').textContent = nilai;
    document.getElementById('inputEditKelas').value = nilai;
    document.getElementById('editDropdownKelas').classList.remove('open');
}

function pilihEditJurusan(nilai) {
    document.getElementById('labelEditJurusan').textContent = nilai;
    document.getElementById('inputEditJurusan').value = nilai;
    document.getElementById('editDropdownJurusan').classList.remove('open');
}
    // ======= PILIH KELAS =======
    let selectedKelas = 'Semua Kelas';
    function pilihKelas(nilai, el) {
        selectedKelas = nilai;
        document.getElementById('labelKelas').textContent = nilai;
        document.querySelectorAll('#dropdownKelas .dropdown-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('dropdownKelas').classList.remove('open');
        filterTable();
    }

    // ======= PILIH JURUSAN =======
    let selectedJurusan = 'Semua Jurusan';
    function pilihJurusan(nilai, el) {
        selectedJurusan = nilai;
        document.getElementById('labelJurusan').textContent = nilai;
        document.querySelectorAll('#dropdownJurusan .dropdown-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('dropdownJurusan').classList.remove('open');
        filterTable();
    }

    // ======= FILTER TABLE =======
    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const nis = cells[0]?.textContent.toLowerCase() || '';
            const nama = cells[1]?.textContent.toLowerCase() || '';
            const rowKelas = cells[2]?.textContent.trim() || '';
            const rowJurusan = cells[3]?.textContent.trim() || '';

            const matchSearch = nama.includes(search) || nis.includes(search);
            const matchKelas = selectedKelas === 'Semua Kelas' || rowKelas === selectedKelas;
            const matchJurusan = selectedJurusan === 'Semua Jurusan' || rowJurusan === selectedJurusan;

            row.style.display = matchSearch && matchKelas && matchJurusan ? '' : 'none';
        });
    }

    // ======= MODAL =======
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    function openEditModal(id, nis, nama, kelas, jurusan, email) {
    document.getElementById('formEdit').action = '/data-siswa/update/' + id;  // ← sesuaikan route
    document.getElementById('editNis').value = nis;
    document.getElementById('editNama').value = nama;
    document.getElementById('labelEditKelas').textContent = kelas || 'Pilih Kelas';
    document.getElementById('inputEditKelas').value = kelas;
    document.getElementById('labelEditJurusan').textContent = jurusan || 'Pilih Jurusan';
    document.getElementById('inputEditJurusan').value = jurusan;
    document.getElementById('editEmail').value = email;
    openModal('modalEdit'); 
}

   function openHapusModal(id, nama) {
    document.getElementById('deleteMessage').innerHTML = 
        'Data siswa <strong>"' + nama + '"</strong> akan dihapus dan tidak dapat dikembalikan.';
    document.getElementById('formHapus').action = '/data-siswa/delete/' + id;
    openModal('modalHapus');
}
</script>
@endsection