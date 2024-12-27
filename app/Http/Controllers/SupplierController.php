<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    public function data()
    {
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                $editUrl = route('supplier.update', $supplier->id_supplier);
                $deleteUrl = route('supplier.destroy', $supplier->id_supplier); // Pastikan route sesuai
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

        $supplier = Supplier::create($request->all());
        return response()->json("Data Berhasil Disimpan", 200);
    }

    public function show(string $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        return response()->json($supplier);
    }

    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json("Data Tidak Ditemukan", 404);
        }
        $supplier->update($request->all());

        return response()->json("Data Berhasil Diupdate", 200);
    }

    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return response()->json("Data Berhasil Dihapus", 200);
    }
}
