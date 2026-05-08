<?php
namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::paginate(9);
        return view('admin.data-siswa', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'          => 'required|unique:siswas',
            'nama_lengkap' => 'required',
            'kelas'        => 'required',
            'jurusan'      => 'required',
        ]);
        Siswa::create($request->only('nis', 'nama_lengkap', 'kelas', 'jurusan'));
        return redirect()->route('data-siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $request->validate([
            'nis'          => 'required|unique:siswas,nis,' . $id,
            'nama_lengkap' => 'required',
            'kelas'        => 'required',
            'jurusan'      => 'required',
        ]);
        $siswa->update($request->only('nis', 'nama_lengkap', 'kelas', 'jurusan'));
        return redirect()->route('data-siswa')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return redirect()->route('data-siswa')->with('success', 'Siswa berhasil dihapus.');
    }
}