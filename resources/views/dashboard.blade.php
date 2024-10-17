@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-2" style="color: white; border: 2px solid white">
                <p style="margin-left: 8%;"></p>
                <h4>SINAR SURYA</h4>
                <h6 class="mx-3">T = TOMBOL</h6>
            </div>
            <div class="d-flex justify-content-between">
                <h1 id="big-name" style="color: white;"></h1>
                <input type="text" id="barcodeInput" style="width: 1px; opacity: 0;" autofocus>
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
    <div class="modal" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> --}}
                <div class="modal-body">
                    <div class="d-flex justify-content-center mt-4">
                        <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                            <table class="table" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center" style="font-size: 20px;">TOMBOL</th>
                                        <th class="text-center" style="font-size: 20px;">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody class="align-items-center">
                                    <tr>
                                        <td class="text-center" style="font-size: 24px;"><b>></b></td>
                                        <td style="font-size: 24px;">CASH</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="font-size: 24px;"><b>?</b></td>
                                        <td style="font-size: 24px;">SUBTOTAL</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="font-size: 24px;"><b>-</b></td>
                                        <td style="font-size: 24px;">CLEAR</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="font-size: 24px;"><b>R</b></td>
                                        <td style="font-size: 24px;">LIST PEMBELIAN</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="font-size: 24px;"><b>F2</b></td>
                                        <td style="font-size: 24px;">SIGN OFF</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ESC - SELESAI</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                // console.log(data)
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    
                    <td class="text-center">PLU</td>
                    <td class="text-center">${kode}</td>
                    <td>${data.product.nama}/${data.product.unit_jual}</td>
                    <td class="text-end">
                        <input type="text" style="background-color: black; color: white; text-align: right; border: 1px solid white;" 
                            id="input-order" value="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </td>
                    <td class="text-end">${number_format(data.product.harga_jual)}</td>
                    <td class="text-end">
                        <input type="text" style="background-color: black; color: white; text-align: right; border: 1px solid white;" 
                            id="input-diskon" value="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </td>
                    <td class="text-end" id="value-total">${number_format(data.product.harga_jual)}</td>
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
                let totalHarga = 1 * data.product.harga_jual;
                inputOrder.addEventListener('input', function() {
                    const newValue = parseInt(this.value, 10);
                    if (!isNaN(newValue)) {
                        const updatedPrice = newValue * data.product.harga_jual;
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
                        const otherInput = this.id === 'input-order' ? newRow.querySelector('#input-diskon') : newRow.querySelector('#input-order');
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
                            kode: kode,
                            nama: data.product.nama+'/'+data.product.unit_jual,
                            harga: data.product.harga_jual,
                            order: totalHarga / data.product.harga_jual,
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
                document.getElementById("big-name").innerHTML = data.product.nama+'/'+data.product.unit_jual;
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

            // focus barcode
            if (event.key === 'F2') {
                window.location.href = '/logout';
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
                console.log(data);
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

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>
@endsection