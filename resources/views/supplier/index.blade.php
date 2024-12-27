@extends('layouts.master')
@section('title', 'Supplier')

@section('breadcrumb')
    @parent
    <li class="active">Supplier</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button type="button" onclick="addForm('{{ route('supplier.store') }}')" class="btn btn-success btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>
                </div>

                <div class="box-body table-responsive">
                    <table id="supplier-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>nama Supplier</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @includeIf('supplier.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#supplier-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{{ route('supplier.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama_supplier'
                    },
                    {
                        data: 'telepon'
                    },
                    {
                        data: 'alamat'
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

        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_supplier]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama_supplier]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama_supplier]').val(response.nama_supplier);
                    $('#modal-form [name=telepon]').val(response.telepon);
                    $('#modal-form [name=alamat]').val(response.alamat);
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    $('#modal-form').modal('hide');
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
