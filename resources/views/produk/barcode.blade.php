<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>
    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            @foreach ($produk as $key => $prod)
                <td class="text-center" style="border: 1px solid black">
                    <p>{{ $prod->nama_produk }} - Rp {{ format_uang($prod->harga_jual) }}</p>
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($prod->kode_produk, 'C39') }}"
                        alt="{{ $prod->kode_produk }}" width="180" height="60">
                    <br>
                    {{ $prod->kode_produk }}
                </td>
                @if ($nomor++ % 3 == 0)
        <tr></tr>
        @endif
        @endforeach
        </tr>
    </table>
</body>

</html>
