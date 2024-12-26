@extends('layouts.master')
@section('title', 'Daftar Produk')

@section('breadcrumb')
    @parent
    <li class="active">Produk</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">

                    <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>
                    <button onclick="deleteSelected('{{ route('produk.delete-selected') }}')" class="btn btn-danger btn-flat">
                        <i class="fa fa-trash"></i> Hapus
                    </button>
                    <button onclick="cetakBarcode('{{ route('produk.cetak-barcode') }}')" class="btn btn-info btn-flat">
                        <i class="fa fa-barcode"></i> Cetak Barcode
                    </button>
                </div>

                <div class="box-body table-responsive">
                    <form action="" method="post" class="form-produk">
                        @csrf
                        <table id="produk-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="select_all" id="select_all">
                                    </th>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Merk</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Diskon</th>
                                    <th>Stok</th>
                                    <th width="15%"><i class="fa fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @includeIf('produk.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#produk-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{{ route('produk.data') }}',
                columns: [{
                        data: 'select_all',
                        orderable: false,
                        searchable: false,
                        sortable: false,
                        width: '5%'
                    }, {
                        data: 'DT_RowIndex',
                        searchable: false,
                    },

                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'nama_kategori'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'harga_beli'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#modal-form').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    $.ajax({
                        type: $('#modal-form form').attr('method'),
                        url: $('#modal-form form').attr('action'),
                        data: $('#modal-form form').serialize(),
                        success: function(response) {
                            $('#modal-form').modal('hide'); // Tutup modal
                            console.log('Update berhasil:', response); // Log respon
                            table.ajax.reload(null,
                                false); // Reload data pada tabel tanpa mereset pagination
                        },
                        error: function(errors) {
                            alert(
                                `Error: ${errors.responseJSON?.message || 'Gagal menyimpan data'}`
                            );
                        }
                    });
                    return false; // Mencegah form submission default
                }
            });

            $('[name=select_all]').on('click', function(e) {
                $(':checkbox').prop('checked', this.checked);
            });


        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Produk');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_produk]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Produk');

            $('#modal-form form')[0].reset(); // Reset form sebelum mengisi data baru
            $('#modal-form form').attr('action', url); // Set URL form untuk PUT
            $('#modal-form [name=_method]').val('put'); // Gunakan metode PUT
            $('#modal-form [name=nama_produk]').focus();

            $.get(url) // Lakukan GET request ke URL untuk mendapatkan data produk
                .done((response) => {
                    // Isi data yang didapatkan ke dalam form
                    $('#modal-form [name=nama_produk]').val(response.nama_produk);
                    $('#modal-form [name=id_kategori]').val(response.id_kategori);
                    $('#modal-form [name=brand]').val(response.brand);
                    $('#modal-form [name=harga_beli]').val(response.harga_beli);
                    $('#modal-form [name=harga_jual]').val(response.harga_jual);
                    $('#modal-form [name=diskon]').val(response.diskon);
                    $('#modal-form [name=stok]').val(response.stok);
                    table.ajax.reload(); // Reload data setelah edit selesai
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    $('#modal-form').modal('hide'); // Tutup modal jika ada kesalahan
                    return;
                });
        }

        function deleteData(url) {
            $.post(url, {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                })
        }

        function deleteSelected(url) {
            if ($('input:checked').length > 0) {
                if (confirm('Apakah Anda yakin ingin menghapus data?')) {
                    $.post(url, $('.form-produk').serialize())
                        .done((response) => {
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            (alert('Tidak dapat menghapus data'));
                            return
                        })
                }
            } else {
                alert("Pilh data yang akan dihapus");
                return;
            }
        }

        function cetakBarcode(url) {
            if ($('input:checked').length < 1) {
                alert("Pilih data terlebih dahulu");
                return;
            } else if ($('input:checked').length < 3) {
                alert("Pilih minimal 3 data");
                return;
            } else {
                $('.form-produk').attr('action', url).attr('target', '_blank').submit();
            }
        }
    </script>
@endpush
