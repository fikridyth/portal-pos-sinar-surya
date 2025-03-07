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
            <div class="d-flex justify-content-center align-items-center mt-4 mb-2"
                style="color: white; border: 2px solid white">
                <h3>POINT OF SALES SYSTEM SINAR SURYA</h3>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center align-items-center"
                    style="width: 25%; color: white; margin-top: 100px;">
                    <h5>PEMBUKAAN TRANSAKSI POS</h5>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center align-items-center mb-2"
                    style="width: 25%; color: white; border: 2px solid white;">
                    <h5>TRANSAKSI TANGGAL {{ now()->format('d/m/Y') }}</h5>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center mb-2 p-2"
                    style="color: white; border: 2px solid white; border-bottom: none; margin-top: 25px; background-color: gray; width: 40%;">
                    {{-- <div class="d-flex justify-content-center" style="overflow-x: auto; height: 155px; border: 1px solid #ccc; background-color: white;">
                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th class="text-center text-black">JENIS PROGRAM</th>
                                    <th class="text-center text-black">PILIH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="color: black">KASIR TANPA SCAN DUDUK</td>
                                    <td class="text-center"><input type="checkbox"></td>
                                </tr>
                                <tr>
                                    <td style="color: black">KASIR DENGAN SCAN DUDUK</td>
                                    <td class="text-center"><input type="checkbox"></td>
                                </tr>
                                <tr>
                                    <td style="color: black">KASIR TANPA JARTECH</td>
                                    <td class="text-center"><input type="checkbox"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center align-items-center mt-n2 p-2"
                    style="color: white; border: 2px solid white; border-top: none; background-color: gray; width: 40%;">
                    <div class="mb-3">
                        <button id="process-button" class="btn btn-dark mx-2" style="width: 200px;">PROSES</button>
                        <a href="/" class="btn btn-dark mx-2" style="width: 200px;">SELESAI</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2 p-2"
                style="color: white; background-color: blue; border: 2px solid white; margin-top: 120px;">
                <h5 class="mx-2">POS NO <input class="mx-2" type="text" size="5" readonly value="01"></h5>
                <h5 class="mx-2">KASIR <input class="mx-2" type="text" size="20" readonly value="{{ auth()->user()->name }}"></h5>
                {{-- <h5 class="mx-2">DATA <input class="mx-2" type="text" size="5" value="0"></h5> --}}
                {{-- <button class="btn btn-sm btn-dark" style="width: 150px;">KOREKSI</button> --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // end-of-day
        document.getElementById('process-button').addEventListener('click', function() {
            fetch('/process-end-of-day', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'), // if using CSRF protection
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Handle success message
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Error:', error); // Handle error
                });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'F2') {
                event.preventDefault();
                window.location.href = '/';
            }
        });
    </script>
@endsection