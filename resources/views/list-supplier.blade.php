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
    <div class="container">
        <h2 class="text-center mt-3 mb-4" style="color: white;">KEMBALI BARANG</h2>
        {{-- <div class="form-group col-8"> --}}
            <div class="p-2" style="overflow-x: auto; height: 600px; border: 1px solid #ccc; background-color: white;">
                {{ $dataTable->table() }}
            </div>
        {{-- </div> --}}
    </div>
    
    <div class="text-center mt-2">
        <button type="button" onclick="window.history.back()" class="btn btn-danger mt-3 mx-3">F2 = KEMBALI</button>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}

    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F2') {
                event.preventDefault();
                window.location.href = '/dashboard';
            }

            if (event.key === 'Tab') {
                event.preventDefault(); // Prevent default tab behavior

                // Focus on the DataTable search input
                const searchInput = document.querySelector('#supplier-table_filter input');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    </script>
@endsection