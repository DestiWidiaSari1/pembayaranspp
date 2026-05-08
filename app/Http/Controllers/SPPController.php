<?php

namespace App\Http\Controllers;

use App\Models\SPP;
use Illuminate\Http\Request;

class SPPController extends Controller
{
    public function index(Request $request)
    {
        $query = SPP::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tahun', 'like', '%' . $search . '%')
                  ->orWhere('kode', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $spps      = $query->orderBy('tahun', 'desc')->paginate(10)->withQueryString();
        $tahunList = SPP::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.data-spp', compact('spps', 'tahunList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'    => 'required|string|max:20|unique:spps,kode',
            'tahun'   => 'required|integer|min:2000|max:2099',
            'nominal' => 'required|numeric|min:0',
        ]);

        $spp = SPP::create($validated);

        return response()->json(['success' => true, 'data' => $spp]);
    }

    public function update(Request $request, $id)
    {
        $spp = SPP::findOrFail($id);

        $validated = $request->validate([
            'kode'    => 'required|string|max:20|unique:spps,kode,' . $id,
            'tahun'   => 'required|integer|min:2000|max:2099',
            'nominal' => 'required|numeric|min:0',
        ]);

        $spp->update($validated);

        return response()->json(['success' => true, 'data' => $spp]);
    }

    public function destroy($id)
    {
        $spp = SPP::findOrFail($id);
        $spp->delete();

        return response()->json(['success' => true]);
    }
}