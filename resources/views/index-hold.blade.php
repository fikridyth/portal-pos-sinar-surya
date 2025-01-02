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
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
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
            <div class="d-flex justify-content-between align-items-center mt-4 mb-2"
                style="color: white; border: 2px solid white">
                <p style="margin-left: 8%;"></p>
                <h4>SINAR SURYA</h4>
                <h6 class="mx-3">T = TOMBOL</h6>
            </div>
            <div class="d-flex justify-content-between">
                <h1 id="big-name" style="color: white;"></h1>
                <input type="text" id="barcodeInput" style="width: 1px; opacity: 0;" autofocus>
                <input type="text" id="barcodeInputVoid" style="width: 1px; opacity: 0;">
                <input type="text" id="barcodeInputReturn" style="width: 1px; opacity: 0;">

                <div id="passwordModal" class="modal-password" style="display:none;">
                    <div class="modal-content-password">
                        <h5>MASUKAN PASSWORD</h5>
                        <input type="password" id="passwordInput" />
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
                        <th style="width: 194px;">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="top">
                    @foreach (json_decode($hold->detail) as $detail)
                    <tr>
                        <td class="text-center" style="width: 75px;">{{ $detail->label }}</td>
                        <td class="text-center" style="width: 200px;">{{ $detail->kode_alternatif }}</td>
                        <td>{{ $detail->nama }}</td>
                        <td class="text-end" style="width: 100px;">{{ $detail->order }}</td>
                        <td class="text-end" style="width: 175px;">{{ number_format($detail->harga) }}</td>
                        <td class="text-end" style="width: 175px;">{{ number_format($detail->diskon) }}</td>
                        <td class="text-end" style="width: 175px;">{{ number_format($detail->grand_total) }}</td>
                    </tr>
                    @endforeach
                    <tr style="height: 550px;">
                        <td class="text-center" id="label-cell" style="width: 75px;">PLU</td>
                        <td class="text-center" style="width: 200px;"></td>
                        <td></td>
                        <td class="text-end" style="width: 100px;">
                            <p id="input-text">1</p>
                            <input type="text" hidden
                                style="background-color: black; color: white; text-align: right; border: 1px solid white;"
                                autocomplete="off" id="input-order" value="1" size="7"
                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                        </td>
                        <td class="text-end" style="width: 175px;">0</td>
                        <td class="text-end" id="input-diskon" style="width: 175px;">0</td>
                        <td class="text-end" id="value-total" style="width: 175px;">0</td>
                    </tr>
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
        // implementasi barcode
        const barcodeValues = [];
        const dataHold = @json($hold);
        let productDetails = JSON.parse(dataHold.detail);
        let inputTimeout;
        let grandTotal = 0;
        let grandDiskon = 0;
        let grandTotalDiskon = 0;
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
                        alert(data.error)
                    } else {
                        // implementasi harga sementara
                        const now = new Date();
                        const currentDate = now.toISOString().split('T')[0];
                        let hargaJual = data.product.harga_jual;
                        // if (data.product.harga_sementara && (data.product.tanggal_awal <= currentDate && data.product.tanggal_akhir >= currentDate)) {
                        //     hargaJual = data.product.harga_sementara;
                        // } else {
                        //     hargaJual = data.product.harga_jual;
                        // }
                        const displayHarga = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -hargaJual : hargaJual;
                        const displayOrder = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -inputOrder : inputOrder;

                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                                            <td class="text-center" style="width: 75px;">${scanLabel}</td>
                                            <td class="text-center" style="width: 200px;">${kode}</td>
                                            <td>${data.product.nama}/${data.product.unit_jual}</td>
                                            <td class="text-end" style="width: 100px;">${inputOrder.value}</td>
                                            <td class="text-end" style="width: 175px;">${number_format(displayHarga)}</td>
                                            <td class="text-end" id="input-diskon" style="width: 175px;">0</td>
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
                        grandTotalDiskon += displayHarga;
                        document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
                        document.getElementById("small-jumlah").innerHTML = number_format(grandTotal);
                        document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                        document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);

                        productDetails.push({
                            label: scanLabel,
                            kode: data.product.kode,
                            kode_alternatif: data.product.kode_alternatif,
                            nama: data.product.nama + '/' + data.product.unit_jual,
                            harga: displayHarga,
                            order: displayOrder,
                            total: inputOrder.value * displayHarga,
                            diskon: 0,
                            grand_total: displayHarga - 0
                        });

                        // Add the event listener in input
                        document.getElementById("big-name").innerHTML = data.product.nama + '/' + data.product
                        .unit_jual;
                        barcodeValues.length = 0;
                        document.getElementById('barcodeInput').focus();

                        localStorage.setItem('productDetails', productDetails);
                        localStorage.setItem('grandTotal', dataHold.total + displayHarga);
                        localStorage.setItem('grandDiskon', dataHold.diskon);
                        localStorage.setItem('grandTotalDiskon', dataHold.grand_total + displayHarga);
                        window.location.href = '/';
                    }
                });
        }
        // implementasi barcode

        // add otoritas, implementasi klik yang menggunakan otoritas
        let isPasswordVisible = false;
        const saPassword = @json($saPass);
        const myPassword = @json($myPass);
        const passwordModal = document.getElementById('passwordModal');
        const passwordInput = document.getElementById('passwordInput');

        document.addEventListener('keydown', function(event) {
            if (isPasswordVisible) {
                handlePasswordVisibility(event);
            } else {
                handleDefaultVisibility(event);
            }
        });

        function handlePasswordVisibility(event) {
            const password = passwordInput.value.toUpperCase();
            const topTbody = document.querySelector('tbody.top');

            if (event.key === 'Enter') {
                if (passwordInput.dataset.action === 'F4' && password === saPassword) {
                    document.getElementById('label-cell').innerText = 'VOD';
                    document.getElementById('barcodeInputVoid').focus();
                    closePasswordModal();
                } else if (passwordInput.dataset.action === 'F6' && password === saPassword) {
                    document.getElementById('label-cell').innerText = 'RTN';
                    document.getElementById('barcodeInputReturn').focus();
                    closePasswordModal();
                } else if ((passwordInput.dataset.action === 'p' || passwordInput.dataset.action === 'P') && password ===
                    saPassword) {
                    // console.log(topTbody.innerHTML)
                    // Ambil semua elemen <tr> yang memiliki <td> dengan ID input-diskon
                    const rowsWithDiskon = topTbody.querySelectorAll('tr td#input-diskon');
                    const lastInputDiskonTd = rowsWithDiskon.length > 1 ? rowsWithDiskon[rowsWithDiskon.length - 2] : null;
                    const rowsWithTotal = topTbody.querySelectorAll('tr td#value-total');
                    const lastInputTotalTd = rowsWithTotal.length > 1 ? rowsWithTotal[rowsWithTotal.length - 2] : null;

                    if (lastInputDiskonTd) {
                        // Ambil indeks baris yang bersangkutan
                        const rowIndex = productDetails.length - 1;

                        // Buat elemen input
                        const diskonInput = document.createElement('input');
                        diskonInput.type = 'text';
                        diskonInput.size = 15
                        diskonInput.style.backgroundColor = 'black';
                        diskonInput.style.color = 'white';
                        diskonInput.style.textAlign = 'right';
                        diskonInput.style.border = '1px solid white';
                        diskonInput.onkeypress = function(event) {
                            return event.charCode >= 48 && event.charCode <= 57; // Hanya angka
                        };

                        // Kosongkan isi <td> dan tambahkan input
                        lastInputDiskonTd.innerHTML = '';
                        lastInputDiskonTd.appendChild(diskonInput);
                        diskonInput.focus(); // Autofocus pada input
                        closePasswordModal();

                        let previousDiskonValue = productDetails[rowIndex].diskon || 0;
                        diskonInput.addEventListener('keydown', function(event) {
                            if (event.key === 'Enter') {
                                const diskonValue = parseFloat(diskonInput.value) || 0;
                                const total = productDetails[rowIndex].total;
                                const calculatedDiskon = (diskonValue / 100) * total;

                                if (diskonValue < 1 || diskonValue > 100) {
                                    alert("Value harus antara 1 dan 100.");
                                    lastInputDiskonTd.innerHTML = productDetails[rowIndex].diskon;
                                    return;
                                }

                                // Update productDetails
                                if (productDetails[rowIndex]) {
                                    productDetails[rowIndex].diskon = calculatedDiskon; // Update diskon
                                    productDetails[rowIndex].grand_total = productDetails[rowIndex].total -
                                        calculatedDiskon; // Update grand total
                                }

                                grandDiskon += (calculatedDiskon - previousDiskonValue);
                                grandTotalDiskon += (previousDiskonValue - calculatedDiskon);
                                document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
                                document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                                document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);

                                lastInputDiskonTd.innerHTML = number_format(calculatedDiskon);
                                lastInputTotalTd.innerHTML = number_format(productDetails[rowIndex].grand_total);
                            }
                        });
                    } else {
                        alert("Tidak ada data yang dirubah.");
                        passwordInput.value = '';
                    }
                } else if ((passwordInput.dataset.action === '=' || passwordInput.dataset.action === '+') && password ===
                    saPassword) {
                    // console.log(topTbody.innerHTML)
                    // Ambil semua elemen <tr> yang memiliki <td> dengan ID input-diskon
                    const rowsWithDiskon = topTbody.querySelectorAll('tr td#input-diskon');
                    const lastInputDiskonTd = rowsWithDiskon.length > 1 ? rowsWithDiskon[rowsWithDiskon.length - 2] : null;
                    const rowsWithTotal = topTbody.querySelectorAll('tr td#value-total');
                    const lastInputTotalTd = rowsWithTotal.length > 1 ? rowsWithTotal[rowsWithTotal.length - 2] : null;

                    if (lastInputDiskonTd) {
                        // Ambil indeks baris yang bersangkutan
                        const rowIndex = productDetails.length - 1;

                        // Buat elemen input
                        const diskonInput = document.createElement('input');
                        diskonInput.type = 'text';
                        diskonInput.size = 15
                        diskonInput.style.backgroundColor = 'black';
                        diskonInput.style.color = 'white';
                        diskonInput.style.textAlign = 'right';
                        diskonInput.style.border = '1px solid white';
                        diskonInput.onkeypress = function(event) {
                            return event.charCode >= 48 && event.charCode <= 57; // Hanya angka
                        };

                        // Kosongkan isi <td> dan tambahkan input
                        lastInputDiskonTd.innerHTML = '';
                        lastInputDiskonTd.appendChild(diskonInput);
                        diskonInput.focus(); // Autofocus pada input
                        closePasswordModal();

                        let previousDiskonValue = productDetails[rowIndex].diskon || 0;
                        diskonInput.addEventListener('keydown', function(event) {
                            if (event.key === 'Enter') {
                                const diskonValue = parseFloat(diskonInput.value) || 0; // Ambil nilai diskon

                                // Update productDetails
                                if (productDetails[rowIndex]) {
                                    productDetails[rowIndex].diskon = diskonValue; // Update diskon
                                    productDetails[rowIndex].grand_total = productDetails[rowIndex].total -
                                        diskonValue; // Update grand total
                                }

                                grandDiskon += (diskonValue - previousDiskonValue);
                                grandTotalDiskon += (previousDiskonValue - diskonValue);
                                document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
                                document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                                document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);

                                lastInputDiskonTd.innerHTML = number_format(diskonValue);
                                lastInputTotalTd.innerHTML = number_format(productDetails[rowIndex].grand_total);
                            }
                        });
                    } else {
                        alert("Tidak ada data yang dirubah.");
                        passwordInput.value = '';
                    }
                } else if (passwordInput.dataset.action === 'F7' && password === saPassword) {
                    event.preventDefault();
                    window.addEventListener('beforeunload', function() {
                        localStorage.removeItem('productDetails');
                        localStorage.removeItem('grandTotal');
                        localStorage.removeItem('grandDiskon');
                        localStorage.removeItem('grandTotalDiskon');
                    });
                    window.location.href = '/end-of-day';
                } else if (passwordInput.dataset.action === 'F12' && password === saPassword) {
                    event.preventDefault();
                    window.addEventListener('beforeunload', function() {
                        localStorage.removeItem('productDetails');
                        localStorage.removeItem('grandTotal');
                        localStorage.removeItem('grandDiskon');
                        localStorage.removeItem('grandTotalDiskon');
                    });
                    window.location.href = '/laporan-kasir';
                } else if ((passwordInput.dataset.action === 'y' || passwordInput.dataset.action === 'Y') && (password ===
                        saPassword || password === myPassword)) {
                    event.preventDefault();
                    window.addEventListener('beforeunload', function() {
                        localStorage.removeItem('productDetails');
                        localStorage.removeItem('grandTotal');
                        localStorage.removeItem('grandDiskon');
                        localStorage.removeItem('grandTotalDiskon');
                    });
                    window.location.href = '/list-supplier';
                } else if (passwordInput.dataset.action === 'F5' && password === saPassword) {
                    event.preventDefault();
                    window.addEventListener('beforeunload', function() {
                        localStorage.removeItem('productDetails');
                        localStorage.removeItem('grandTotal');
                        localStorage.removeItem('grandDiskon');
                        localStorage.removeItem('grandTotalDiskon');
                    });
                    location.reload();
                } else {
                    alert("Password salah. Silakan coba lagi.");
                    passwordInput.value = '';
                }
                event.preventDefault();
            } else if (event.key === 'Escape') {
                closePasswordModal();
            }
        }

        function handleDefaultVisibility(event) {
            if (event.key === 'Enter' || event.key === 'Escape') {
                document.getElementById('label-cell').innerText = 'PLU';
                document.getElementById('input-text').innerHTML = document.getElementById('input-order').value;
                if (document.getElementById('input-order').value === '' || document.getElementById('input-order').value ==
                    0) {
                    document.getElementById('input-order').value = 1;
                    document.getElementById('input-text').innerHTML = 1;
                }
                document.getElementById('input-text').hidden = false;
                document.getElementById('input-order').hidden = true;
                document.getElementById('barcodeInput').focus();
            } else if (event.key === 'F4') {
                event.preventDefault();
                passwordInput.value = ''; // Clear input when opening the modal
                handlePasswordModal('F4'); // Pass the action type
            } else if (event.key === 'F6') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('F6');
            } else if (event.key === 'p') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('p');
            } else if (event.key === 'P') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('P');
            } else if (event.key === '=') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('=');
            } else if (event.key === '+') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('+');
            } else if (event.key === 'y') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('y');
            } else if (event.key === 'Y') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('Y');
            } else if (event.key === 'F7') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('F7');
            } else if (event.key === 'F12') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('F12');
            } else if (event.key === 'F5') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('F5');
            }
        }

        function handlePasswordModal(action) {
            passwordModal.style.display = 'block';
            passwordInput.focus();
            isPasswordVisible = true;
            passwordInput.dataset.action = action; // Store the action in the input

            passwordInput.addEventListener('input', function() {
                passwordInput.value = passwordInput.value.toUpperCase();
            });
        }

        function closePasswordModal() {
            passwordModal.style.display = 'none';
            isPasswordVisible = false;
            passwordInput.value = '';
        }
        // add otoritas, implementasi void, return

        // get this detail product from manual search
        const storedProductDetails = localStorage.getItem('productDetails');
        if (storedProductDetails) {
            // Parse the JSON string into an object
            productDetails = JSON.parse(storedProductDetails);
            grandTotal = Number(localStorage.getItem('grandTotal'));
            grandDiskon = Number(localStorage.getItem('grandDiskon'));
            grandTotalDiskon = Number(localStorage.getItem('grandTotalDiskon'));

            // make table
            const topTbody = document.querySelector('tbody.top');
            const staticRow = topTbody.querySelector('tr:first-child');
            productDetails.forEach((data, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                                    <td class="text-center" style="width: 75px;">${data.label}</td>
                                    <td class="text-center" style="width: 200px;">${data.kode_alternatif !== null && data.kode_alternatif !== undefined ? data.kode_alternatif : ''}</td>
                                    <td>${data.nama}</td>
                                    <td class="text-end" style="width: 100px;">${data.order}</td>
                                    <td class="text-end" style="width: 175px;">${number_format(data.harga)}</td>
                                    <td class="text-end" style="width: 175px;" id="input-diskon">${number_format(data.diskon)}</td>
                                    <td class="text-end" style="width: 175px;" id="value-total">${number_format(data.grand_total)}</td>
                                `;
                topTbody.insertBefore(newRow, staticRow);
            });

            // update table
            document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
            document.getElementById("small-jumlah").innerHTML = number_format(grandTotal);
            document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
            document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);
        }
        // console.log(grandTotalDiskon, grandTotal)
        // get this detail product from manual search

        // implementasi klik yang tidak menggunakan otoritas
        document.addEventListener('keydown', function(event) {
            // focus on input qty
            if (event.key === 'Tab') {
                event.preventDefault();
                document.getElementById('input-text').hidden = true;
                document.getElementById('input-order').hidden = false;
                document.getElementById('input-order').value = '';
                document.getElementById('input-order').focus();
            }

            // lihat list tombol
            if (event.key === 't' || event.key === 'T') {
                const modal = new bootstrap.Modal(document.getElementById('myModal'));
                modal.show();

                // Fokus pada elemen scrollable
                const scrollableDiv = document.getElementById('scrollableDiv');
                scrollableDiv.focus(); // Fokus pada div untuk mengizinkan scroll

                // Event listener untuk scroll dengan keyboard
                scrollableDiv.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowUp') {
                        scrollableDiv.scrollBy(0, -20); // Scroll ke atas
                    } else if (e.key === 'ArrowDown') {
                        scrollableDiv.scrollBy(0, 20); // Scroll ke bawah
                    }
                });
            }

            // search product - menggunakan local storage untuk menyimpan data sementara
            if (event.key === 'F11') {
                event.preventDefault();
                localStorage.setItem('productDetails', dataHold.detail);
                localStorage.setItem('grandTotal', dataHold.total);
                localStorage.setItem('grandDiskon', dataHold.diskon);
                localStorage.setItem('grandTotalDiskon', dataHold.grand_total);
                localStorage.setItem('inputOrder', inputOrder.value);
                localStorage.setItem('scanLabel', document.getElementById('label-cell').innerText);
                window.location.href = '/list-barang';
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

            // buat fungsi untuk store hold
            if (event.key === 'F8') {
                event.preventDefault();
                submitDataHold();
            }

            // list hold
            if (event.key === 'F9') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                    localStorage.removeItem('grandDiskon');
                    localStorage.removeItem('grandTotalDiskon');
                });
                window.location.href = '/list-hold';
            }

            if (event.key === 'r' || event.key === 'R') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                    localStorage.removeItem('grandDiskon');
                    localStorage.removeItem('grandTotalDiskon');
                });
                window.location.href = '/list-pembelian';
            }

            // kembali ke pembayaran
            if (event.key === '>' || event.key === '.') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                    localStorage.removeItem('grandDiskon');
                    localStorage.removeItem('grandTotalDiskon');
                });
                window.location.href = '/dashboard';
            }

            // reload
            if (event.key === '-') {
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                    localStorage.removeItem('grandDiskon');
                    localStorage.removeItem('grandTotalDiskon');
                });
                location.reload();
            }

            // logout
            if (event.key === 'F2') {
                event.preventDefault();
                window.addEventListener('beforeunload', function() {
                    localStorage.removeItem('productDetails');
                    localStorage.removeItem('grandTotal');
                    localStorage.removeItem('grandDiskon');
                    localStorage.removeItem('grandTotalDiskon');
                });
                window.location.href = '/logout';
            }
        });
        // implementasi klik yang tidak menggunakan otoritas

        // store data ke tabel penjualan
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
                                            <td style="width: 75px;"></td><td style="width: 200px;"></td><td></td><td style="width: 100px;" class="text-end">1</td><td style="width: 175px;" class="text-end">0</td><td style="width: 175px;" class="text-end">0</td><td style="width: 175px;" class="text-end">0</td>
                                        `;
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                                            <td style="width: 75px;" class="text-center">STL</td>
                                            <td style="width: 200px;" class="text-center"></td>
                                            <td>SUBTOTAL</td>
                                            <td style="width: 100px;" class="text-end">1</td>
                                            <td style="width: 175px;" class="text-end">${number_format(data.penjualan.total)}</td>
                                            <td style="width: 175px;" class="text-end" id="input-diskon">${number_format(data.penjualan.diskon ?? 0)}</td>
                                            <td style="width: 175px;" class="text-end" id="value-total">${number_format(data.penjualan.total - (data.penjualan.diskon ?? 0))}</td>
                                        `;
                        topTbody.appendChild(newRow2);
                        topTbody.appendChild(newRow);

                        printReceipt(data.printData);

                        // Clear localStorage on page unload
                        window.addEventListener('beforeunload', function() {
                            localStorage.removeItem('productDetails');
                            localStorage.removeItem('grandTotal');
                            localStorage.removeItem('grandDiskon');
                            localStorage.removeItem('grandTotalDiskon');
                        });
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
        // store data ke tabel penjualan

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

        // progress - store data hold
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
                        window.addEventListener('beforeunload', function() {
                            localStorage.removeItem('productDetails');
                            localStorage.removeItem('grandTotal');
                            localStorage.removeItem('grandDiskon');
                            localStorage.removeItem('grandTotalDiskon');
                        });
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
        // progress - store data hold

        // number format
        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        // number format

        // window.addEventListener('beforeunload', function() {
        localStorage.setItem('productDetails', dataHold.detail);
        localStorage.setItem('grandTotal', dataHold.total);
        localStorage.setItem('grandDiskon', dataHold.diskon);
        localStorage.setItem('grandTotalDiskon', dataHold.grand_total);

        // Redirect ke halaman dashboard setelah data disimpan
        window.location.href = '/dashboard';
        // });
    </script>
@endsection
