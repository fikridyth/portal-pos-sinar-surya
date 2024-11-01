@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-2"
                style="color: white; border: 2px solid white">
                <p style="margin-left: 8%;"></p>
                <h4>SINAR SURYA</h4>
                <h6 class="mx-3">T = TOMBOL | F9 = BATAL</h6>
            </div>
            <div class="d-flex justify-content-between">
                <h1 id="big-name" style="color: white;"></h1>
                <input type="text" id="barcodeInput" style="width: 1px; opacity: 0;" autofocus>
                <input type="text" id="barcodeInputVoid" style="width: 1px; opacity: 0;">
                <input type="text" id="barcodeInputReturn" style="width: 1px; opacity: 0;">
            </div>
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th></th>
                        <th>KODE</th>
                        <th>NAMA BARANG</th>
                        <th>JUMLAH</th>
                        <th>HARGA</th>
                        <th>DISKON</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody class="top">
                    @foreach (json_decode($hold->detail) as $detail)
                    <tr>
                        <td class="text-center">{{ $detail->label }}</td>
                        <td class="text-center">{{ $detail->kode_alternatif }}</td>
                        <td>{{ $detail->nama }}</td>
                        <td class="text-end">{{ $detail->order }}</td>
                        <td class="text-end">{{ number_format($detail->harga) }}</td>
                        <td class="text-end">{{ number_format($detail->diskon) }}</td>
                        <td class="text-end">{{ number_format($detail->grand_total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tbody class="bottom">
                    <tr>
                        <td class="text-end" id="big-total" colspan="5" style="font-size: 56px;">{{ number_format($hold->grand_total) }}</td>
                        <td class="text-end" colspan="2">
                            <div class="d-flex justify-content-between">
                                <div>JUMLAH</div>
                                <div id="small-jumlah">{{ number_format($hold->total) }}</div>
                            </div>
                            <hr style="border: 1px solid white; opacity: 1; margin: 2px 0 4px 0">
                            <div class="d-flex justify-content-between">
                                <div>DISKON</div>
                                <div id="small-diskon">{{ number_format($hold->diskon) }}</div>
                            </div>
                            <hr style="border: 1px solid white; opacity: 1; margin: 2px 0 4px 0">
                            <div class="d-flex justify-content-between">
                                <div>TOTAL</div>
                                <div id="small-total">{{ number_format($hold->grand_total) }}</div>
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
        const barcodeValues = [];
        let productDetails = [];
        let inputTimeout;

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

        // Fungsi untuk menambahkan barcode ke tabel
        let grandTotal = 0;
        let grandDiskon = 0;

        function scanBarcode(barcodeValue) {
            const topTbody = document.querySelector('tbody.top');
            const kode = barcodeValues.join('');

            fetch(`/get-detail-products/${kode}`)
                .then(response => response.json())
                .then(data => {
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

                    <td class="text-center">${scanLabel}</td>
                    <td class="text-center">${kode}</td>
                    <td>${data.product.nama}/${data.product.unit_jual}</td>
                    <td class="text-end">
                        <input type="text" style="background-color: black; color: white; text-align: right; border: 1px solid white;"
                            id="input-order" value="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </td>
                    <td class="text-end">${number_format(displayHarga)}</td>
                    <td class="text-end">
                        <input type="text" style="background-color: black; color: white; text-align: right; border: 1px solid white;"
                            id="input-diskon" value="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </td>
                    <td class="text-end" id="value-total">${number_format(displayHarga)}</td>
                `;
                    topTbody.appendChild(newRow);

                    // focus di inputorder
                    const inputOrder = newRow.querySelector('#input-order');
                    const inputDiskon = newRow.querySelector('#input-diskon');
                    inputOrder.focus();

                    // mencegah klik diluar menghilangkan autofocus
                    document.addEventListener('click', function(event) {
                        if (!inputOrder.contains(event.target)) {
                            event.preventDefault(); // Prevent the default action
                            inputOrder.focus(); // Keep the focus on the input
                        }
                    });

                    // update the total
                    let totalHarga = 1 * displayHarga;
                    inputOrder.addEventListener('input', function() {
                        const newValue = parseInt(this.value, 10);
                        if (!isNaN(newValue)) {
                            const updatedPrice = newValue * displayHarga;
                            newRow.querySelector('#value-total').innerText = number_format(updatedPrice);
                            totalHarga = updatedPrice;
                        }
                    });

                    // update the diskon
                    let totalHargaDiskon = totalHarga;
                    let totalDiskon = 0;
                    inputDiskon.addEventListener('input', function() {
                        const newValue = parseInt(this.value, 10);
                        if (!isNaN(newValue)) {
                            const updatedPriceDiskon = totalHarga - newValue;
                            newRow.querySelector('#value-total').innerText = number_format(updatedPriceDiskon);
                            totalHargaDiskon = updatedPriceDiskon;
                            totalDiskon = newValue;
                        }
                    });

                    // fokus ke barcodeinput saat enter
                    function handleEnterKey(event) {
                        if (event.key === 'Enter') {
                            // change input to text
                            const value = this.value;
                            const textNode = document.createElement('span');
                            textNode.style.color = 'white'; // Adjust as needed
                            textNode.style.backgroundColor = 'black'; // Adjust as needed
                            textNode.style.textAlign = 'right';
                            textNode.innerText = number_format(value);

                            this.parentNode.replaceChild(textNode, this);

                            // change input to text to other
                            const otherInput = this.id === 'input-order' ? newRow.querySelector('#input-diskon') :
                                newRow.querySelector('#input-order');
                            if (otherInput) {
                                const otherValue = otherInput.value;
                                const otherTextNode = document.createElement('span');
                                otherTextNode.style.color = 'white'; // Adjust as needed
                                otherTextNode.style.backgroundColor = 'black'; // Adjust as needed
                                otherTextNode.style.textAlign = 'right';
                                otherTextNode.innerText = number_format(otherValue);

                                otherInput.parentNode.replaceChild(otherTextNode, otherInput);
                            }

                            // update total harga
                            grandTotal += totalHarga;
                            grandDiskon += totalDiskon;
                            document.getElementById("big-total").innerHTML = number_format(grandTotal - grandDiskon);
                            document.getElementById("small-jumlah").innerHTML = number_format(grandTotal);
                            document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                            document.getElementById("small-total").innerHTML = number_format(grandTotal - grandDiskon);

                            productDetails.push({
                                kode: data.product.kode,
                                nama: data.product.nama + '/' + data.product.unit_jual,
                                harga: displayHarga,
                                order: totalHarga / displayHarga,
                                total: totalHarga,
                                diskon: totalDiskon,
                                grand_total: totalHarga - totalDiskon
                            });

                            // kembalikan focus ke barcode
                            document.getElementById('barcodeInput').focus();
                        }
                    }

                    // Add the event listener in input
                    inputOrder.addEventListener('keydown', handleEnterKey);
                    inputDiskon.addEventListener('keydown', handleEnterKey);
                    document.getElementById("big-name").innerHTML = data.product.nama + '/' + data.product.unit_jual;
                    barcodeValues.length = 0;
                });
        }

        document.addEventListener('keydown', function(event) {
            // lihat list tombol
            if (event.key === 't' || event.key === 'T') {
                const modal = new bootstrap.Modal(document.getElementById('myModal'));
                modal.show();
            }

            if (event.key === 'r' || event.key === 'R') {
                window.location.href = '/list-pembelian';
            }

            // kembali ke pembayaran
            if (event.key === '>' || event.key === '.') {
                window.location.href = '/dashboard';
            }

            // kirim data dengan fetch
            if (event.key === '?' || event.key === '/') {
                event.preventDefault();
                submitData();
            }

            // reload
            if (event.key === '-') {
                location.reload();
            }

            // focus barcode
            if (event.key === 'Enter' || event.key === 'Escape') {
                document.getElementById('barcodeInput').focus();
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

            // kembali barang supplier
            if (event.key === 'y' || event.key === 'Y') {
                window.location.href = '/list-supplier';
            }

            // logout
            if (event.key === 'F2') {
                window.location.href = '/logout';
            }

            // focus void
            if (event.key === 'F4') {
                event.preventDefault();
                document.getElementById('barcodeInputVoid').focus();
            }

            // all void then print
            if (event.key === 'F5') {
                location.reload();
                // event.preventDefault();
                // allVoidData();
            }

            // focus return
            if (event.key === 'F6') {
                event.preventDefault();
                document.getElementById('barcodeInputReturn').focus();
            }

            // search product
            if (event.key === 'F11') {
                event.preventDefault();
                window.location.href = '/list-barang';
            }

            // buat fungsi untuk store hold
            if (event.key === 'F8') {
                event.preventDefault();
                submitDataHold();
                // window.location.href = '/dashboard';
            }

            // list hold
            if (event.key === 'F9') {
                event.preventDefault();
                window.location.href = '/list-hold';
            }
        });

        function submitData() {
            const detail = JSON.stringify({
                products: productDetails,
                grandTotal: grandTotal,
                grandDiskon: grandDiskon
            });
            fetch('{{ route('penjualan.store') }}', {
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
                    if (data.error) {
                        alert(data.error);
                    } else {
                        const topTbody = document.querySelector('tbody.top');
                        const newRow2 = document.createElement('tr');
                        newRow2.innerHTML = `
                            <td></td><td></td><td></td><td class="text-end">1</td><td class="text-end">0</td><td class="text-end">0</td><td class="text-end">0</td>
                        `;
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td class="text-center">STL</td>
                            <td class="text-center"></td>
                            <td>SUBTOTAL</td>
                            <td class="text-end">1</td>
                            <td class="text-end">${number_format(data.penjualan.grand_total)}</td>
                            <td class="text-end">0</td>
                            <td class="text-end" id="value-total">${number_format(data.penjualan.grand_total)}</td>
                        `;
                        topTbody.appendChild(newRow2);
                        topTbody.appendChild(newRow);

                        printReceipt(data.printData);
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        function printReceipt(printData) {
            // Open a new window or use a printing library to send the print data to the thermal printer
            const printWindow = window.open('', '', 'height=400,width=300');
            printWindow.document.write('<pre>' + printData + '</pre>');
            printWindow.document.close();
            printWindow.print();
        }

        function submitDataHold() {
            const detail = JSON.stringify({
                products: productDetails,
                grandTotal: grandTotal,
                grandDiskon: grandDiskon
            });
            fetch('{{ route('penjualan.hold') }}', {
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
                    if (data.error) {
                        alert(data.error);
                    } else {
                        alert(data.success);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>
@endsection
