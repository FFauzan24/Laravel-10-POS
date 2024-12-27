<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        return view('pengeluaran.index');
    }

    public function data()
    {
        $pengeluaran = Pengeluaran::orderBy('id_pengeluaran', 'desc')->get();

        return datatables()
            ->of($pengeluaran)
            ->addIndexColumn()
            ->addColumn('created_at', function ($pengeluaran) {
                return tanggal_indonesia($pengeluaran->created_at, false);
            })
            ->addColumn('nominal', function ($pengeluaran) {
                return format_uang($pengeluaran->nominal);
            })
            ->addColumn('aksi', function ($pengeluaran) {
                $editUrl = route('pengeluaran.update', $pengeluaran->id_pengeluaran);
                $deleteUrl = route('pengeluaran.destroy', $pengeluaran->id_pengeluaran); // Pastikan route sesuai
                return '<div class="btn-group">
                <button type="button" onclick="editForm(`' . $editUrl . '`)" class="btn btn-xs btn-info btn-flat">
                    <i class="fa fa-edit"></i>
                </button> 
                <button type="button" onclick="deleteData(`' . $deleteUrl . '`)" class="btn btn-xs btn-danger btn-flat">
                    <i class="fa fa-trash"></i>
                </button>
                </div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $pengeluaran = Pengeluaran::create($request->all());
        return response()->json("Data Berhasil Disimpan", 200);
    }

    public function show(string $id)
    {
        $pengeluaran = Pengeluaran::find($id);
        if (!$pengeluaran) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        return response()->json($pengeluaran);
    }

    public function update(Request $request, string $id)
    {
        $pengeluaran = Pengeluaran::find($id);
        if (!$pengeluaran) {
            return response()->json("Data Tidak Ditemukan", 404);
        }
        $pengeluaran->update($request->all());

        return response()->json("Data Berhasil Diupdate", 200);
    }

    public function destroy(string $id)
    {
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->delete();

        return response()->json("Data Berhasil Dihapus", 200);
    }
}
