<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
        $member = Member::orderBy('kode_member')->get();

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($member) {
                return '
                <input type="checkbox" name="id_member[]" value="' . $member->id_member . '">';
            })
            ->addColumn('kode_member', function ($member) {
                return '<span class="label label-success">' . $member->kode_member . '</span>';
            })
            ->addColumn('aksi', function ($member) {
                $editUrl = route('member.update', $member->id_member);
                $deleteUrl = route('member.destroy', $member->id_member); // Pastikan route sesuai
                return '<div class="btn-group">
                <button onclick="editForm(`' . $editUrl . '`)" class="btn btn-xs btn-info btn-flat">
                    <i class="fa fa-edit"></i>
                </button> 
                <button onclick="deleteData(`' . $deleteUrl . '`)" class="btn btn-xs btn-danger btn-flat">
                    <i class="fa fa-trash"></i>
                </button>
                </div>';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
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
        $member = Member::latest()->first();
        $kode_member = $member ? (int) filter_var($member->kode_member, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        $request['kode_member'] = 'M' . tambahkan_0($kode_member, 5);
        $member = Member::create($request->all());
        return response()->json("Data Berhasil Disimpan", 200);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_member' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:255',
        ]);
        $member = Member::find($id);
        if (!$member) {
            return response()->json("Data Tidak Ditemukan", 404);
        }
        $member->update($request->all());
        return response()->json("Data Berhasil Diupdate", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $member = member::find($id);
        if (!$member) {
            return response()->json("Data Tidak Ditemukan", 404);
        }

        $member->delete();
        return response()->json("Data Berhasil Dihapus", 200);
    }

    public function cetakMember(Request $request)
    {
        $member = Member::find($request->id_member);
        $member = $member->chunk(2);
        $nomor = 1;
        $pdf = PDF::loadView('member.cetak', compact('member', 'nomor'));
        $pdf->setPaper(array(0, 0, 566.933, 850.394), 'potrait');
        return $pdf->stream('member.pdf');

        // $dataProduk = [];
        // foreach ($request->id_produk as $id) {
        //     $produk = Produk::find($id);
        //     $dataProduk[] = $produk;
        // }
        // return $dataProduk;
    }
}
