@extends('main')

@section('styles')
    <style>
        .modal-password {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content-password {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 15%; 
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-2"
                style="color: white; border: 2px solid white">
                <p class="mx-2 mt-n1 mb-n1" style="margin-top: 0; margin-bottom: 0;">KEMBALI BARANG SUPPLIER <br>{{ $supplier->nama }}</p>
                <h4>SINAR SURYA</h4>
                <h6 class="mx-3">T = TOMBOL | F2 = BATAL</h6>
            </div>
            <div class="d-flex justify-content-between">
                <h1 id="big-name" style="color: white;"></h1>
                <input type="text" id="barcodeInput" style="width: 1px; opacity: 0;" autofocus>
                <input type="text" id="barcodeInputVoid" style="width: 1px; opacity: 0;">
                <input type="text" id="barcodeInputReturn" style="width: 1px; opacity: 0;">

                <div id="passwordModal" class="modal-password" style="display:none;">
                    <div class="modal-content-password">
                        <h5>MASUKAN PASSWORD</h5>
                        <input type="password" id="passwordInput"/>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th style="width: 75px;"></th>
                        <th style="width: 200px;">KODE</th>
                        <th>NAMA BARANG</th>
                        <th style="width: 100px;">JUMLAH</th>
                        <th style="width: 175px;">HARGA</th>
                        <th style="width: 175px;">DISKON</th>
                        <th style="width: 175px;">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="top">
                    <tr>
                        <td class="text-center" id="label-cell" style="width: 75px;">PLU</td>
                        <td class="text-center" style="width: 200px;"></td>
                        <td></td>
                        <td class="text-end" style="width: 100px;">
                            <input type="text" style="background-color: black; color: white; text-align: right; border: 1px solid white;" autocomplete="off"
                                id="input-order" value="1" size="7" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                        </td>
                        <td class="text-end" style="width: 175px;">0</td>
                        <td class="text-end" style="width: 175px;">0</td>
                        <td class="text-end" id="value-total" style="width: 175px;">0</td>
                    </tr>
                </tbody>
                <tbody class="bottom">
                    <tr>
                        <td class="text-end" id="big-total" colspan="5" style="font-size: 56px;">0</td>
                        <td class="text-end" colspan="2">
                            <div class="d-flex justify-content-between">
                                <div>JUMLAH</div>
                                <div id="small-jumlah">0</div>
                            </div>
                            <hr style="border: 1px solid white; opacity: 1; margin: 2px 0 4px 0">
                            <div class="d-flex justify-content-between">
                                <div>DISKON</div>
                                <div id="small-diskon">0</div>
                            </div>
                            <hr style="border: 1px solid white; opacity: 1; margin: 2px 0 4px 0">
                            <div class="d-flex justify-content-between">
                                <div>TOTAL</div>
                                <div id="small-total">0</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @include('modal')
@endsection

@section('scripts')
    <script>
    // implementasi barcode
        const barcodeValues = [];
        let productDetails = [];
        let inputTimeout;
        let grandTotal = 0;
        let grandDiskon = 0;
        const inputOrder = document.getElementById('input-order');

        document.getElementById('barcodeInput').addEventListener('input', function(event) {
            const barcodeValueInit = event.target.value;
            const barcodeValue = barcodeValueInit.replace(/\D/g, '');

            // Pastikan input bukan kosong dan sudah memiliki cukup panjang
            if (barcodeValue.length > 0) {
                barcodeValues.push(barcodeValue);
                event.target.value = '';
                scanLabel = 'PLU';
                clearTimeout(inputTimeout);
                inputTimeout = setTimeout(scanBarcode, 200);
            }
        });

        document.getElementById('barcodeInputVoid').addEventListener('input', function(event) {
            const barcodeValueInit = event.target.value;
            const barcodeValue = barcodeValueInit.replace(/\D/g, '');

            // Pastikan input bukan kosong dan sudah memiliki cukup panjang
            if (barcodeValue.length > 0) {
                barcodeValues.push(barcodeValue);
                event.target.value = '';
                scanLabel = 'VOD';
                clearTimeout(inputTimeout);
                inputTimeout = setTimeout(scanBarcode, 200);
            }
        });

        document.getElementById('barcodeInputReturn').addEventListener('input', function(event) {
            const barcodeValueInit = event.target.value;
            const barcodeValue = barcodeValueInit.replace(/\D/g, '');

            // Pastikan input bukan kosong dan sudah memiliki cukup panjang
            if (barcodeValue.length > 0) {
                barcodeValues.push(barcodeValue);
                event.target.value = '';
                scanLabel = 'RTN';
                clearTimeout(inputTimeout);
                inputTimeout = setTimeout(scanBarcode, 200);
            }
        });

        function scanBarcode(barcodeValue) {
            const topTbody = document.querySelector('tbody.top');
            const staticRow = topTbody.querySelector('tr:first-child');
            const kode = barcodeValues.join('');

            fetch(`/get-detail-products/${kode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert (data.error)
                    } else {
                        // implementasi harga sementara
                        const now = new Date();
                        const currentDate = now.toISOString().split('T')[0];
                        let hargaJual;
                        if (data.product.harga_sementara && (data.product.tanggal_awal <= currentDate && data.product.tanggal_akhir >= currentDate)) {
                            hargaJual = data.product.harga_sementara;
                        } else {
                            hargaJual = data.product.harga_jual;
                        }
                        const displayHarga = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -hargaJual : hargaJual;

                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td class="text-center" style="width: 75px;">${scanLabel}</td>
                            <td class="text-center" style="width: 200px;">${kode}</td>
                            <td>${data.product.nama}/${data.product.unit_jual}</td>
                            <td class="text-end" style="width: 100px;">${inputOrder.value}</td>
                            <td class="text-end" style="width: 175px;">${number_format(displayHarga)}</td>
                            <td class="text-end" style="width: 175px;">0</td>
                            <td class="text-end" id="value-total" style="width: 175px;">${number_format(displayHarga * inputOrder.value)}</td>
                        `;
                        const lastInsertedRow = topTbody.querySelector('tr:not(:first-child):last-child');
                        if (lastInsertedRow) {
                            topTbody.insertBefore(newRow, lastInsertedRow);
                        } else {
                            topTbody.insertBefore(newRow, staticRow);
                        }

                        // update total harga
                        grandTotal += displayHarga;
                        grandDiskon += 0;
                        document.getElementById("big-total").innerHTML = number_format(grandTotal - grandDiskon);
                        document.getElementById("small-jumlah").innerHTML = number_format(grandTotal);
                        document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                        document.getElementById("small-total").innerHTML = number_format(grandTotal - grandDiskon);

                        productDetails.push({
                            kode: data.product.kode,
                            kode_alternatif: data.product.kode_alternatif,
                            nama: data.product.nama,
                            unit_jual: data.product.unit_jual,
                            stok: data.product.stok,
                            order: inputOrder.value,
                            price: data.product.harga_pokok,
                            field_total: inputOrder.value * data.product.harga_pokok,
                            kode_sumber: data.product.kode_sumber,
                            diskon1: 0,
                            diskon2: 0,
                            diskon3: 0,
                            penjualan_rata: 0,
                            waktu_kunjungan: 0,
                            stok_minimum: 0,
                            stok_maksimum: 0,
                            is_ppn: 0,
                        });
                        
                        // Add the event listener in input
                        document.getElementById("big-name").innerHTML = data.product.nama + '/' + data.product.unit_jual;
                        barcodeValues.length = 0;
                        document.getElementById('barcodeInput').focus();
                    }
                });
        }
    // implementasi barcode

    // get this detail product from manual search
        const storedProductDetails = localStorage.getItem('productDetails');
        if (storedProductDetails) {
            // Parse the JSON string into an object
            productDetails = JSON.parse(storedProductDetails);
            grandTotal = Number(localStorage.getItem('grandTotal'));

            // make table
            const topTbody = document.querySelector('tbody.top');
            const staticRow = topTbody.querySelector('tr:first-child');
            productDetails.forEach((data, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="text-center" style="width: 75px;">PLU</td>
                    <td class="text-center" style="width: 200px;">${data.kode_alternatif}</td>
                    <td>${data.nama}/${data.unit_jual}</td>
                    <td class="text-end" style="width: 100px;">${data.order}</td>
                    <td class="text-end" style="width: 175px;">${number_format(data.price)}</td>
                    <td class="text-end" style="width: 175px;">0</td>
                    <td class="text-end" style="width: 175px;" id="value-total">${number_format(data.field_total)}</td>
                `;
                topTbody.insertBefore(newRow, staticRow);
            });

            // update table
            document.getElementById("big-total").innerHTML = number_format(grandTotal - grandDiskon);
            document.getElementById("small-jumlah").innerHTML = number_format(grandTotal);
            document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
            document.getElementById("small-total").innerHTML = number_format(grandTotal - grandDiskon);
        }
        // console.log(productDetails)
        // console.log(grandTotal)
    // get this detail product from manual search
    
    // implementasi klik yang tidak menggunakan otoritas
        document.addEventListener('keydown', function(event) {
            // focus on input qty
            if (event.key === 'Tab') {
                event.preventDefault();
                document.getElementById('input-order').value = '';
                document.getElementById('input-order').focus();
            }

            // handle enter
            if (event.key === 'Enter' || event.key === 'Escape') {
                document.getElementById('label-cell').innerText = 'PLU';
                if (document.getElementById('input-order').value === '' || document.getElementById('input-order').value == 0) {
                    document.getElementById('input-order').value = 1;
                }
                document.getElementById('barcodeInput').focus();
            }

            // lihat list tombol
            if (event.key === 't' || event.key === 'T') {
                const modal = new bootstrap.Modal(document.getElementById('myModal'));
                modal.show();
            }

            // search product - menggunakan local storage untuk menyimpan data sementara
            if (event.key === 'F11') {
                event.preventDefault();
                localStorage.setItem('productDetails', JSON.stringify(productDetails));
                localStorage.setItem('grandTotal', grandTotal);
                localStorage.setItem('inputOrder', inputOrder.value);
                const id = {{ $id }};
                window.location.href = `/list-barang-supplier/${id}`;
            }

            // kirim data dengan fetch
            if (event.key === '?' || event.key === '/') {
                event.preventDefault();
                submitData();
            }

            // transfer penjualan ke server
            if (event.key === 'v' || event.key === 'V') {
                fetch('/send-penjualan', {
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
                    })
                    .catch(error => {
                        console.error('Error:', error); // Handle error
                    });
            }
            
            // all void then print
            if (event.key === 'F5') {
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                location.reload();
            }

            // buat fungsi untuk store hold
            // if (event.key === 'F8') {
            //     event.preventDefault();
            //     submitDataHold();
            // }

            // list hold
            if (event.key === 'F9') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                window.location.href = '/list-hold';
            }

            if (event.key === 'r' || event.key === 'R') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                window.location.href = '/list-pembelian';
            }

            // kembali barang supplier
            if (event.key === 'y' || event.key === 'Y') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                window.location.href = '/list-supplier';
            }

            // kembali ke pembayaran
            if (event.key === '>' || event.key === '.') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                window.location.href = '/';
            }

            // reload
            if (event.key === '-') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                location.reload();
            }

            // logout
            if (event.key === 'F2') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                });
                window.location.href = '/';
            }
        });
    // implementasi klik yang tidak menggunakan otoritas

    // store data ke tabel pengembalian server
        function submitData() {
            const detail = JSON.stringify({
                detail: productDetails,
                supplier: {{ $supplier->id }},
                grandTotal: grandTotal,
                // grandDiskon: grandDiskon
            });
            fetch('{{ route('store-return-data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                    },
                    body: detail, // Send the collected product details
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // or response.text() based on your response
                })
                .then(data => {
                    // alert(data)
                    printReceipt(data.printData);

                    // Clear localStorage on page unload
                    window.addEventListener('beforeunload', function() {
                        localStorage.removeItem('productDetails');
                        localStorage.removeItem('grandTotal');
                    });
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    // store data ke tabel pengembalian server

    // cetak data
        function printReceipt(printData) {
            // Membuka jendela baru untuk pencetakan
            const printWindow = window.open('', '', 'height=400,width=300');
            
            // Menyusun isi struk dalam format HTML
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Struk Pembayaran</title>
                        <style>
                            body {
                                font-family: monospace;
                                font-size: 12px;
                                margin: 0;
                                padding: 10px;
                            }
                            pre {
                                white-space: pre-wrap; /* Mengatur spasi */
                            }
                        </style>
                    </head>
                    <body>
                        <pre>${printData}</pre>
                    </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
        }
    // cetak data

    // number format
        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    // number format
    </script>
@endsection
