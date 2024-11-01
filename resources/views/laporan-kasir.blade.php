@extends('main')

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
            <div class="d-flex justify-content-center align-items-center mb-2 p-5"
                style="color: white; border: 2px solid white; margin-top: 200px;">
                <h1>PILIH TIPE LAPORAN KASIR (L = LOKAL, N = NETWORK)</h1>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'l' || event.key === 'L' || event.key === 'n' || event.key === 'N') {
                event.preventDefault();
                window.location.href = '/index-laporan-kasir';
            }
        });
    </script>
@endsection
