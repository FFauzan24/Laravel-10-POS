<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('kategori.index');
    }

    public function data()
    {
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();

        return datatables()
            ->of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $editUrl = route('kategori.update', $kategori->id_kategori);
                $deleteUrl = route('kategori.destroy', $kategori->id_kategori); // Pastikan route sesuai
                return '<div class="btn-group">
                <button onclick="editForm(`' . $editUrl . '`)" class="btn btn-xs btn-info btn-flat">
                    <i class="fa fa-edit"></i>
                </button> 
                <button onclick="deleteData(`' . $deleteUrl . '`)" class="btn btn-xs btn-danger btn-flat">
                    <i class="fa fa-trash"></i>
                </button>
                </div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori = new Kategori();
        $kategori->nama_kategori = $request->input('nama_kategori');
        $kategori->save();

        return response()->json("Data Berhasil Disimpan", 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        return response()->json($kategori);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        $kategori->nama_kategori = $request->input('nama_kategori');
        $kategori->save();

        return response()->json("Data Berhasil Diupdate", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        $kategori->delete();
        return response()->json("Data Berhasil Dihapus", 200);
    }
}
