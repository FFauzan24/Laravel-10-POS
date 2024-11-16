<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" id="form" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="form-group row">
                    <label for="nama_produk" class="col-md-2 col-md-offset-1 control-label">Nama Produk</label>
                    <div class="col-md-6">
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value=""
                            required autofocus>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_katetegori" class="col-md-2 col-md-offset-1 control-label">Kategori</label>
                    <div class="col-md-6">
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="brand" class="col-md-2 col-md-offset-1 control-label">Merk</label>
                    <div class="col-md-6">
                        <input type="text" name="brand" id="brand" class="form-control" value=""required>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga_beli" class="col-md-2 col-md-offset-1 control-label">Harga beli</label>
                    <div class="col-md-6">
                        <input type="number" name="harga_beli" id="harga_beli" class="form-control" value=""
                            required>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga_jual" class="col-md-2 col-md-offset-1 control-label">Harga Jual</label>
                    <div class="col-md-6">
                        <input type="number" name="harga_jual" id="harga_jual" class="form-control"
                            value=""required>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="diskon" class="col-md-2 col-md-offset-1 control-label">Diskon</label>
                    <div class="col-md-6">
                        <input type="number" name="diskon" id="diskon" class="form-control" value="0"
                            required>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="stok" class="col-md-2 col-md-offset-1 control-label">Stok</label>
                    <div class="col-md-6">
                        <input type="number" name="stok" id="stok" class="form-control" value="0"
                            required>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary">Simpan</button>
                    <button class="btn btn-sm btn-flat btn-default" data-dismiss="modal">batal</button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
