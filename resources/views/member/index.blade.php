@extends('layouts.master')
@section('title', 'Member')

@section('breadcrumb')
    @parent
    <li class="active">Member</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>

                    <button onclick="cetakMember('{{ route('member.cetak-member') }}')" class="btn btn-info btn-flat">
                        <i class="fa fa-id-card"></i> Cetak Member
                    </button>
                </div>

                <div class="box-body table-responsive">
                    <form action="" method="POST" class="form-member">
                        @csrf
                        <table id="category-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="select_all" id="select_all">
                                    </th>
                                    <th width="5%">No</th>
                                    <th>Kode Member</th>
                                    <th>nama Member</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
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
    @includeIf('member.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{{ route('member.data') }}',
                columns: [{
                        data: 'select_all',
                        searchable: false,
                        sortable: false,
                        width: '5%'
                    }, {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_member'
                    },
                    {
                        data: 'nama_member'
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

            $('[name=select_all]').on('click', function(e) {
                $(':checkbox').prop('checked', this.checked);
            });

        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_member]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama_member]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama_member]').val(response.nama_member);
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

        function cetakMember(url) {
            if ($('input:checked').length < 1) {
                alert("Pilih data terlebih dahulu");
                return;
            } else {
                $('.form-member').attr('action', url).attr('target', '_blank').submit();
            }
        }
    </script>
@endpush
