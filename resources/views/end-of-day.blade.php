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
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div style="width: 55%;">
            <div class="d-flex flex-column mb-2 p-5"
                style="color: white; border: 2px solid white; margin-top: 200px;">
                <h1>PROSES END OF DAY TANGGAL {{ now()->format('d/m/Y') }}</h1>
                <h1>PROSES END OF DAY ( Y=YA, T=TIDAK )</h1>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'y' || event.key === 'Y') {
                event.preventDefault();
                window.location.href = '/index-end-of-day';
            }
            if (event.key === 't' || event.key === 'T') {
                event.preventDefault();
                window.location.href = '/';
            }
        });
    </script>
@endsection
