<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>

    <style>
        .box {
            position: relative;
        }

        .card {
            width: 85.60mm
        }

        .logo {
            position: absolute;
            top: 3pt;
            right: 0pt;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .logo p {
            text-align: right;
            margin-right: 14pt;
        }

        .logo img {
            position: absolute;
            margin-top: -5px;
            width: 60px;
            height: 60px;
            right: 16pt;
        }

        .nama {
            position: absolute;
            top: 90pt;
            right: 16pt;
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .telepon {
            position: absolute;
            top: 100pt;
            right: 16pt;
            font-size: 10pt;
            color: #fff !important;
        }

        .barcode {
            position: absolute;
            top: -10pt;
            left: .860rem;
            border: 1px solid #fff;
            background: #fff;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <section style="border: 1px solid #fff">
        <table width="100%">
            @foreach ($member as $key => $data)
                <tr>
                    @foreach ($data as $item)
                        <td class="text-center" width="50%">
                            <div class="box">
                                <img src="{{ public_path('img/member.png') }}" alt="card" width="100%">
                                <div class="logo">
                                    <p>{{ config('app.name') }}</p>
                                    <img src="{{ public_path('img/logo3.png') }}" alt="logo">
                                </div>
                                <div class="nama">{{ $item->nama_member }}</div>
                                <div class="telepon">{{ $item->telepon }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($item->kode_member, 'QRCODE') }}"
                                        alt="qrcode" width="45" height="45">
                                </div>
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
</body>

</html>
