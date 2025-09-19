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
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // end-of-day
        document.getElementById('process-button').addEventListener('click', function() {
            const button = this;
            button.disabled = true;
            button.innerText = 'MEMPROSES...';
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
                    if (data.success) {
                        alert(data.message); // Tampilkan pesan sukses dari server
                        window.location.href = '/';
                    } else {
                        alert('Data gagal dikirim ke server, silahkan cek koneksi!'); // Tampilkan pesan gagal dari server
                        button.disabled = false;
                        button.innerText = 'PROSES';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghubungi server. Silakan coba lagi.');
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