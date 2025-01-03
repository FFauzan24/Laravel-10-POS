<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', '=', 'produk.id_kategori')
            ->select('produk.*', 'kategori.nama_kategori')
            ->orderBy('kode_produk', 'asc')->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                <input type="checkbox" name="id_produk[]" value="' . $produk->id_produk . '">';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">' . $produk->kode_produk . '</span>';
            })
            ->addColumn('harga_beli', function ($produk) {
                return format_uang($produk->harga_beli);
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('aksi', function ($produk) {
                $editUrl = route('produk.update', $produk->id_produk);
                $deleteUrl = route('produk.destroy', $produk->id_produk); // Pastikan route sesuai
                return '<div class="btn-group">
            <button type="button" onclick="editForm(`' . $editUrl . '`)" class="btn btn-xs btn-info btn-flat">
                <i class="fa fa-edit"></i>
            </button> 
            <button type="button" onclick="deleteData(`' . $deleteUrl . '`)" class="btn btn-xs btn-danger btn-flat">
                <i class="fa fa-trash"></i>
            </button>
            </div>';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);;
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
        $produk = Produk::latest()->first();
        $request['kode_produk'] = 'P' . tambahkan_0((int)$produk->id_produk + 1, 6);
        $produk = Produk::create($request->all());

        return response()->json("Data Berhasil Disimpan", 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
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
        $produk = Produk::find($id);
        $produk->update($request->all());

        return response()->json("Data Berhasil Diupdate", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response()->json("Data Berhasil Dihapus", 200);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }
        return response()->json("Data Berhasil Dihapus", 200);
    }

    public function cetakBarcode(Request $request)
    {
        $produk = Produk::find($request->id_produk);

        $nomor = 1;
        $pdf = PDF::loadView('produk.barcode', compact('produk', 'nomor'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');

        // $dataProduk = [];
        // foreach ($request->id_produk as $id) {
        //     $produk = Produk::find($id);
        //     $dataProduk[] = $produk;
        // }
        // return $dataProduk;
    }
}
