@extends('layouts.master')
@section('title', 'Kategori')

@section('breadcrumb')
    @parent
    <li class="active">Kategori</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('kategori.store') }}')" class="btn btn-success btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>
                </div>

                <div class="box-body table-responsive">
                    <table id="category-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @includeIf('kategori.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{{ route('kategori.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama_kategori'
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#modal-form').validator().on('submit', function(e) {
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


        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Kategori');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_kategori]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Kategori');

            $('#modal-form form')[0].reset(); // Reset form sebelum mengisi data baru
            $('#modal-form form').attr('action', url); // Set URL form untuk PUT
            $('#modal-form [name=_method]').val('put'); // Gunakan metode PUT
            $('#modal-form [name=nama_kategori]').focus();

            $.get(url) // Lakukan GET request ke URL untuk mendapatkan data kategori
                .done((response) => {
                    // Isi data yang didapatkan ke dalam form
                    $('#modal-form [name=nama_kategori]').val(response.nama_kategori);
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
    </script>
@endpush
