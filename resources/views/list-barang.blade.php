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
            let grandTotal = Number(localStorage.getItem('grandTotal')) || 0;
            let grandDiskon = Number(localStorage.getItem('grandDiskon')) || 0;
            let grandTotalDiskon = Number(localStorage.getItem('grandTotalDiskon')) || 0;
            let inputOrder = Number(localStorage.getItem('inputOrder'));
            let scanLabel = localStorage.getItem('scanLabel');

            // Get data from table
            const productTable = document.querySelector('#product-table');
            productTable.addEventListener('click', function(event) {
                // Check if the clicked element is a product link
                if (event.target.classList.contains('product-link')) {
                    event.preventDefault(); // Prevent default link behavior
                    const link = event.target; // Reference to the clicked link
                    const now = new Date();
                    const currentDate = now.toISOString().split('T')[0];

                    // implementasi harga sementara
                    // let hargaJual;
                    // if (link.getAttribute('data-harga-sementara') && (link.getAttribute('data-tanggal-awal') <= currentDate && link.getAttribute('data-tanggal-akhir') >= currentDate)) {
                    //     hargaJual = parseFloat(link.getAttribute('data-harga-sementara'));
                    // } else {
                    //     hargaJual = parseFloat(link.getAttribute('data-harga-jual'));
                    // }
                    let hargaJual = parseFloat(link.getAttribute('data-harga-jual'));
                    let productId = link.getAttribute('data-id');
                    var dateNow = @json($now);
                    var hargaSementara = @json($hargaSementara);
                    var hargaPertama = hargaSementara.find(function(harga) {
                        return productId == harga.id_product;
                    });
                    if (hargaPertama) {
                        if (hargaPertama.date_first <= dateNow) {
                            hargaJual = parseFloat(hargaPertama.harga_sementara);
                        }
                    }

                    const displayHarga = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -hargaJual : hargaJual;
                    const displayOrder = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -inputOrder : inputOrder;

                    if (scanLabel == 'VOD') {
                        let kodeDitemukan = productDetails.some((data) => data.kode === link.getAttribute('data-kode'));
                        if (!kodeDitemukan) {
                            alert('DATA TIDAK DITEMUKAN!');
                        } else {
                            const newProductDetails = {
                                label: scanLabel,
                                kode: link.getAttribute('data-kode'),
                                kode_alternatif: link.getAttribute('data-kode-alternatif'),
                                nama: link.getAttribute('data-nama') + '/' + link.getAttribute('data-unit-jual'),
                                harga: displayHarga,
                                order: displayOrder,
                                total: displayHarga * inputOrder,
                                diskon: 0,
                                grand_total: displayHarga * inputOrder
                            };
                            productDetails.push(newProductDetails);
                            grandTotal += Number(displayHarga * inputOrder);
                            grandTotalDiskon += Number(displayHarga * inputOrder);
                        }
                    } else {
                        const newProductDetails = {
                            label: scanLabel,
                            kode: link.getAttribute('data-kode'),
                            kode_alternatif: link.getAttribute('data-kode-alternatif'),
                            nama: link.getAttribute('data-nama') + '/' + link.getAttribute('data-unit-jual'),
                            harga: displayHarga,
                            order: displayOrder,
                            total: displayHarga * inputOrder,
                            diskon: 0,
                            grand_total: displayHarga * inputOrder
                        };
                        productDetails.push(newProductDetails);
                        grandTotal += Number(displayHarga * inputOrder);
                        grandTotalDiskon += Number(displayHarga * inputOrder);
                    }

                    // Store product details in localStorage
                    localStorage.setItem('productDetails', JSON.stringify(productDetails));
                    localStorage.setItem('grandTotal', grandTotal);
                    localStorage.setItem('grandDiskon', grandDiskon);
                    localStorage.setItem('grandTotalDiskon', grandTotalDiskon);
                    localStorage.removeItem('inputOrder');
                    localStorage.removeItem('scanLabel');
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