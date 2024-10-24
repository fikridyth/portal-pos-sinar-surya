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
        <h2 class="text-center mt-3 mb-4" style="color: white;">LIST BARANG</h2>
        {{-- <div class="form-group col-8"> --}}
            <div class="p-2" style="overflow-x: auto; height: 600px; border: 1px solid #ccc; background-color: white;">
                {{ $dataTable->table() }}
            </div>
        {{-- </div> --}}
    </div>
    
    <div class="text-center mt-2">
        <button type="button" onclick="window.history.back()" class="btn btn-danger mt-3 mx-3">F11 = KEMBALI</button>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Retrieve the productDetails from localStorage
            const oldProductDetails = localStorage.getItem('productDetails');
            let productDetails = JSON.parse(oldProductDetails);
            console.log(productDetails)

            // Get data from table
            const productTable = document.querySelector('#product-table');
            productTable.addEventListener('click', function(event) {
                // Check if the clicked element is a product link
                if (event.target.classList.contains('product-link')) {
                    event.preventDefault(); // Prevent default link behavior

                    const link = event.target; // Reference to the clicked link

                    const newProductDetails = {
                        label: 'PLU',
                        kode: link.getAttribute('data-kode'),
                        kode_alternatif: link.getAttribute('data-kode-alternatif'),
                        nama: link.getAttribute('data-nama') + '/' + link.getAttribute('data-unit-jual'),
                        harga: parseFloat(link.getAttribute('data-harga-jual')),
                        order: 1,
                        total: parseFloat(link.getAttribute('data-harga-jual')), // Assuming quantity is 1
                        diskon: 0,
                        grand_total: parseFloat(link.getAttribute('data-harga-jual'))
                    };
                    productDetails.push(newProductDetails);
                    console.log(productDetails)

                    // Store product details in localStorage
                    localStorage.setItem('productDetails', JSON.stringify(productDetails));
                    window.location.href = '/';
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'F11') {
                event.preventDefault();
                window.location.href = '/dashboard';
            }

            if (event.key === 'Tab') {
                event.preventDefault(); // Prevent default tab behavior

                // Focus on the DataTable search input
                const searchInput = document.querySelector('#product-table_filter input');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    </script>
@endsection