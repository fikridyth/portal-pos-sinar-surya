@extends('main')

@section('styles')
    <style>
        .table tbody td {
            background-color: white;
            border: 3px solid #eee;
            border-bottom: none;
            border-top: none;
            color: #eee;
            font-size: 16px;
            padding: 5px; 
            line-height: 1.2; 
        }
        
        .table tbody.bottom td {
            background-color: white;
            border: 3px solid #eee;
            color: #eee;
            font-size: 16px;
            height: 5px;
            padding: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-2"
                style="color: white; border: 2px solid white">
                <p style="margin-left: 8%;"></p>
                <h4>SINAR SURYA</h4>
                <p style="margin-left: 8%;"></p>
                {{-- <h6 class="mx-3">T = TOMBOL</h6> --}}
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center align-items-center mb-2"
                    style="width: 25%; color: white; border: 2px solid white; margin-top: 25px;">
                    <h4>MONITOR KASIR</h4>
                </div>
            </div>
            <div class="d-flex justify-content-center mb-2 p-2"
                style="color: white; border: 2px solid white; border-bottom: none; margin-top: 25px; background-color: gray;">
                <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc; background-color: white;">
                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th class="text-center text-black">POS NO</th>
                                <th class="text-center text-black">NAMA KASIR</th>
                                <th class="text-center text-black">TANGGAL</th>
                                <th class="text-center text-black">TERAKHIR JAM</th>
                                <th class="text-center text-black">TRANSAKSI</th>
                                <th class="text-center text-black">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualans as $jual)
                                <tr>
                                    <td class="text-black">POS 01</td>
                                    <td class="text-black">{{ $jual['created_by'] }}</td>
                                    <td class="text-black text-center">{{ $jual['tanggal'] }}</td>
                                    <td class="text-black text-center">{{ $jual['jam'] }}</td>
                                    <td class="text-black text-end">{{ $jual['transaksi'] }}</td>
                                    <td class="text-black text-end">{{ number_format($jual['grand_total']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-n2 p-2"
                style="color: white; border: 2px solid white; border-top: none; margin-top: 25px; background-color: gray;">
                    <p style="margin-left: 8%;"></p>
                    <div>
                        <button class="btn btn-dark mx-2" style="width: 200px;">F12 - CETAK REKAP</button>
                        <button class="btn btn-dark mx-2" style="width: 200px;">F2 - SELESAI</button>
                    </div>
                    <input type="text" size="15" class="text-end" style="background-color: black; color: white; font-size: 18px;" value="{{ number_format($totalHarga) }}">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F2') {
                event.preventDefault();
                window.location.href = '/';
            }
        });
    </script>
@endsection