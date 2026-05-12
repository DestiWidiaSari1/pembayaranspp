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
                $q->where('kode', 'like', '%' . $search . '%')
                  ->orWhere('nominal', 'like', '%' . $search . '%');
            });
        }

        $spps = $query->orderBy('tingkat')->paginate(10)->withQueryString();

        return view('admin.data-spp', compact('spps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'    => 'required|string|max:20|unique:spps,kode',
            'tingkat' => 'required|in:X,XI,XII',
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
            'tingkat' => 'required|in:X,XI,XII',
            'nominal' => 'required|numeric|min:0',
        ]);

        $spp->update($validated);
        return response()->json(['success' => true, 'data' => $spp]);
    }

    public function destroy($id)
    {
        SPP::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}