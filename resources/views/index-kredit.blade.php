@extends('main')

@section('styles')
    <style>
        #productChoiceModal {
            position: fixed;
            bottom: 30px;
            right: 0;
            width: 300px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            z-index: 1000;
        }

        #productChoiceTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        #productChoiceTable td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            font-size: 14px;
        }

        #productChoiceTable tr:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .modal-password {
            position: fixed;
            bottom: 30px;
            right: 0;
            width: 300px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            z-index: 1000;
        }

        .modal-content-password {
            display: flex;
            flex-direction: column;
        }

        h5 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        #passwordInput {
            padding: 8px;
            font-size: 14px;
            width: 100%;
            margin-top: 10px;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        input[type="number"] {
        -moz-appearance: textfield; /* Firefox */
        }
    </style>

    {{-- include modal style --}}
    @include('modal-style')
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-2"
                style="color: white; border: 2px solid white">
                <p class="mx-2 mt-n1 mb-n1" style="margin-top: 0; margin-bottom: 0;">PENJUALAN KREDIT <br>{{ $supplier->nama }}</p>
                <h4>SINAR SURYA</h4>
                <h6 class="mx-3">T = TOMBOL | F2 = BATAL</h6>
            </div>
            <div class="d-flex justify-content-between">
                <h1 id="big-name" style="color: white;"></h1>
                <input type="text" id="barcodeInput" autocomplete="off" style="width: 1px; opacity: 0;" autofocus>
                <input type="text" id="barcodeInputVoid" autocomplete="off" style="width: 1px; opacity: 0;">
                <input type="text" id="barcodeInputReturn" autocomplete="off" style="width: 1px; opacity: 0;">
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
                    {{-- <input type="number" style="background-color: black; color: white; text-align: right; border: 1px solid white;"
                        id="input-diskon" value="0"> --}}
                <tbody class="top">
                    <tr style="height: 550px;">
                        <td class="text-center" id="label-cell" style="width: 75px;">PLU</td>
                        <td class="text-center" style="width: 200px;"></td>
                        <td></td>
                        <td class="text-end" style="width: 100px;">
                            <p id="input-text">1</p>
                            <input type="number" hidden style="background-color: black; color: white; text-align: right; border: 1px solid white;" autocomplete="off"
                                id="input-order" value="1" size="7">
                        </td>
                        <td class="text-end" style="width: 175px;">0</td>
                        <td class="text-end" id="input-diskon" style="width: 175px;">0</td>
                        <td class="text-end" id="value-total" style="width: 175px;">0</td>
                    </tr>
                </tbody>
                <tbody class="bottom">
                    <tr>
                        <td class="text-end align-items-center" id="input-barcode" colspan="4" style="font-size: 48px; width: 58%">
                            <input type="number" id="barcode-input" style="display: none; font-size: 40px; width: 100%; background-color: black; color: white; border: 2px solid white; padding: 10px;" 
                            autocomplete="off" oninput="checkInput()" onkeydown="handleKeyDown(event)">
                            <input type="text" id="number-input" hidden style="font-size: 40px; width: 100%; background-color: black; color: white; border: 2px solid white; padding: 10px;">
                        </td>
                        <td class="text-end align-items-center" id="big-total" colspan="1" style="font-size: 48px;">0</td>
                        <td class="text-end" colspan="2" style=" width: 18%">
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

    {{-- pass modal --}}
    <div id="passwordModal" class="modal-password" style="display:none;">
        <div class="modal-content-password">
            <h5>MASUKAN PASSWORD</h5>
            <input type="password" id="passwordInput"/>
        </div>
    </div>

    <!-- Modal Pilihan Produk -->
    <div id="productChoiceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999;">
        <div style="position: absolute; top: 30%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 10px; width: 50%; max-width: 1000px;">
            <h4>PILIH PRODUK</h4>
            <table class="border" style="width: 100%; font-size: 14px; margin-top: 10px;" id="productChoiceTable">
                <!-- Isi dinamis dari JavaScript -->
            </table>
            <div class="d-flex justify-content-between">
                <button onclick="closeProductModal()" class="btn btn-sm btn-danger" style="margin-top: 20px; padding: 10px 35px;">TUTUP</button>
            </div>
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
    let grandTotal = 0;
    let grandDiskon = 0;
    let grandTotalDiskon = 0;
    const inputOrder = document.getElementById('input-order');
    
    // implementasi barcode
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
                    if (document.getElementById('label-cell').innerText == 'RTN') {
                        document.getElementById('label-cell').innerText = 'PLU';
                    } else {
                        document.getElementById('label-cell').innerText = 'RTN';
                    }
                    document.getElementById('barcodeInputReturn').focus();
                    closePasswordModal();
                } else if ((passwordInput.dataset.action === 'p' || passwordInput.dataset.action === 'P') && password === saPassword) {
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
                        isPasswordModalOpen = true;

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
                                    productDetails[rowIndex].grand_total = productDetails[rowIndex].total - calculatedDiskon; // Update grand total
                                }

                                grandDiskon += (calculatedDiskon - previousDiskonValue);
                                grandTotalDiskon += (previousDiskonValue - calculatedDiskon);
                                document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
                                document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                                document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);
                                
                                lastInputDiskonTd.innerHTML = number_format(calculatedDiskon);
                                lastInputTotalTd.innerHTML = number_format(productDetails[rowIndex].grand_total);
                                isPasswordModalOpen = false;
                            }
                        });
                    } else {
                        alert("Tidak ada data yang dirubah.");
                        passwordInput.value = '';
                        closePasswordModal();
                    }
                } else if ((passwordInput.dataset.action === '=' || passwordInput.dataset.action === '+') && password === saPassword) {
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
                        isPasswordModalOpen = true;

                        let previousDiskonValue = productDetails[rowIndex].diskon || 0;
                        diskonInput.addEventListener('keydown', function(event) {
                            if (event.key === 'Enter') {
                                const diskonValue = parseFloat(diskonInput.value) || 0; // Ambil nilai diskon
                                
                                // Update productDetails
                                if (productDetails[rowIndex]) {
                                    productDetails[rowIndex].diskon = diskonValue; // Update diskon
                                    productDetails[rowIndex].grand_total = productDetails[rowIndex].total - diskonValue; // Update grand total
                                }

                                grandDiskon += (diskonValue - previousDiskonValue);
                                grandTotalDiskon += (previousDiskonValue - diskonValue);
                                document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
                                document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
                                document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);
                                
                                lastInputDiskonTd.innerHTML = number_format(diskonValue);
                                lastInputTotalTd.innerHTML = number_format(productDetails[rowIndex].grand_total);
                                isPasswordModalOpen = false;
                            }
                        });
                    } else {
                        alert("Tidak ada data yang dirubah.");
                        passwordInput.value = '';
                        closePasswordModal();
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
                } else if ((passwordInput.dataset.action === 'y' || passwordInput.dataset.action === 'Y') && (password === saPassword || password === myPassword)) {
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
                } else if ((passwordInput.dataset.action === 'v' || passwordInput.dataset.action === 'V') && password === saPassword) {
                    event.preventDefault();
                    // transfer penjualan ke server
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
                            closePasswordModal();
                            alert(data.message); // Handle success message
                        })
                        .catch(error => {
                            console.error('Error:', error); // Handle error
                        });
                } else {
                    alert("Password salah. Silakan coba lagi.");
                    passwordInput.value = '';
                }
                event.preventDefault();
            } else if (event.key === 'Escape') {
                closePasswordModal();
            }
        }

        let isPasswordModalOpen = false;
        function handleDefaultVisibility(event) {
            if (event.key === 'Enter' || event.key === 'Escape') {
                // document.getElementById('label-cell').innerText = 'PLU';
                document.getElementById('input-text').innerHTML = document.getElementById('input-order').value;
                if (document.getElementById('input-order').value === '' || document.getElementById('input-order').value == 0) {
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
            } else if (event.key === 'v') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('v');
            } else if (event.key === 'V') {
                event.preventDefault();
                passwordInput.value = '';
                handlePasswordModal('V');
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
            isPasswordModalOpen = true;
        }

        function closePasswordModal() {
            passwordModal.style.display = 'none';
            isPasswordVisible = false;
            passwordInput.value = '';
            isPasswordModalOpen = false;
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
                    <td>${data.nama}/${data.unit_jual}</td>
                    <td class="text-end" style="width: 100px;">${data.order}</td>
                    <td class="text-end" style="width: 175px;">${number_format(data.price)}</td>
                    <td class="text-end" style="width: 175px;" id="input-diskon">${number_format(data.diskon1)}</td>
                    <td class="text-end" style="width: 175px;" id="value-total">${number_format(data.field_total)}</td>
                `;
                topTbody.insertBefore(newRow, staticRow);
                if (data.label == 'RTN') {
                    document.getElementById('label-cell').innerText = data.label;
                } else {
                    document.getElementById('label-cell').innerText = 'PLU';
                }
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
            const input = document.getElementById("barcode-input");
            const inputCode = document.getElementById("barcodeInput");
            
            // input barcode
            if (!isPasswordModalOpen) {
                if (event.key >= '0' && event.key <= '9') {
                    // Tampilkan input jika belum terlihat
                    if (input.style.display === "none") {
                        input.hidden = false;
                        input.style.display = "block";
                        document.getElementById('number-input').value = '';
                        document.getElementById('number-input').hidden = true;
                    }

                    // Fokuskan input
                    input.focus();
                }
            }
            
            if (event.key.toLowerCase() === 'x') {
                // Isi value input ke dalam elemen #input-text
                const inputValue = input.value; // Ambil nilai yang ada di input
                document.getElementById('input-text').innerHTML = inputValue; // Set value ke elemen input-text
                document.getElementById('input-order').value = inputValue;

                // Setelah itu, sembunyikan input dan reset nilainya
                input.style.display = "none";
                input.hidden = true;
                input.value = ''; // Reset input
                document.getElementById('number-input').hidden = false;
                document.getElementById('number-input').value = inputValue+' KALI';
            }

            if (event.key === 'Escape') {
                // Hapus nilai input dan sembunyikan input
                input.value = '';
                input.style.display = "none";
                inputCode.focus();
            }

            // focus on input qty
            // if (event.key === 'Tab') {
            //     event.preventDefault();
            //     document.getElementById('input-text').hidden = true;
            //     document.getElementById('input-order').hidden = false;
            //     document.getElementById('input-order').value = '';
            //     document.getElementById('input-order').focus();
            // }

            // lihat list tombol
            if (event.key === 't' || event.key === 'T') {
                const modal = new bootstrap.Modal(document.getElementById('myModal'));
                modal.show();

                // Fokus pada elemen scrollable
                const scrollableDiv = document.getElementById('scrollableDiv');
                scrollableDiv.focus();  // Fokus pada div untuk mengizinkan scroll

                // Event listener untuk scroll dengan keyboard
                scrollableDiv.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowUp') {
                        scrollableDiv.scrollBy(0, -20); // Scroll ke atas
                    } else if (e.key === 'ArrowDown') {
                        scrollableDiv.scrollBy(0, 20); // Scroll ke bawah
                    }
                });
            }

            // if (document.getElementById('label-cell').innerText == 'RTN') {
            //     if (event.key === 'F6') {
            //         event.preventDefault();
            //         document.getElementById('label-cell').innerText = 'PLU';
            //         closePasswordModal();
            //     }
            // }

            // search product - menggunakan local storage untuk menyimpan data sementara
            if (event.key === 'F11') {
                event.preventDefault();
                const id = {{ $id }};
                localStorage.setItem('productDetails', JSON.stringify(productDetails));
                localStorage.setItem('grandTotal', grandTotal);
                localStorage.setItem('grandDiskon', grandDiskon);
                localStorage.setItem('grandTotalDiskon', grandTotalDiskon);
                localStorage.setItem('inputOrder', inputOrder.value);
                localStorage.setItem('scanLabel', document.getElementById('label-cell').innerText);
                window.location.href = `/list-barang-kredit/${id}`;
            }

            // kirim data dengan fetch
            if (event.key === '?' || event.key === '/') {
                event.preventDefault();
                submitData();
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
                document.getElementById('label-cell').innerText = 'PLU';
                closePasswordModal();
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
                window.location.href = '/dashboard';
            }
        });

        // tampilkan input bila ada data
        function checkInput() {
            const input = document.getElementById("barcode-input");
            const barcode = input.value;
            
            // Jika ada input, tetap tampilkan input, jika tidak sembunyikan
            if (barcode.length > 0) {
                input.style.display = "block";
            } else {
                input.style.display = "none";
                document.getElementById('barcodeInput').focus();
            }
        }

        // Fungsi untuk menangani event ketika tombol ditekan
        function handleKeyDown(event) {
            const input = document.getElementById("barcode-input");
            const barcode = input.value;

            // Jika tombol 'Enter' ditekan dan ada nilai pada input
            if (event.key === 'Enter' && barcode.length > 0) {
                event.preventDefault();  // Mencegah form submit (jika berada dalam form)

                // Panggil route untuk mendapatkan data produk berdasarkan barcode
                fetch(`/get-detail-products/${barcode}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        document.getElementById('barcode-input').value = '';
                        document.getElementById('barcode-input').focus();
                    } else {
                        const kode = document.getElementById('barcode-input').value;

                        if (data.product.length > 1) {
                            showProductChoiceModal(data.product);
                        } else {
                            handleProductSelection(data.product[0]); // langsung isi row
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching product data:', error);
                });
            }
        }

        function showProductChoiceModal(products) {
            const table = document.getElementById("productChoiceTable");
            table.innerHTML = "";

            // Buat header tabel
            const thead = document.createElement("thead");
            thead.innerHTML = `
                <tr>
                    <th>NAMA</th>
                    <th>HARGA</th>
                </tr>
            `;
            table.appendChild(thead);

            // Buat tbody untuk data produk
            const tbody = document.createElement("tbody");
            
            products.forEach((prod, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${prod.nama}/${prod.unit_jual}</td>
                    <td>${number_format(prod.harga_jual)}</td>
                `;
                row.onclick = () => {
                    closeProductModal();
                    handleProductSelection(prod);
                };
                tbody.appendChild(row);
            });

            table.appendChild(tbody);

            document.getElementById("productChoiceModal").style.display = "block";
        }

        function closeProductModal() {
            document.getElementById("productChoiceModal").style.display = "none";
        }

        function handleProductSelection(product) {
            const kode = product.kode_alternatif;
            const nama = product.nama + '/' + product.unit_jual;
            const scanLabel = document.getElementById('label-cell').innerText;
            const inputOrder = document.getElementById("input-order");
            const orderValue = parseInt(inputOrder.value) || 1;
            const displayOrder = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -orderValue : orderValue;

            // Cek harga sementara
            let hargaJual = product.harga_jual;
            var dateNow = @json($now);
            var hargaSementara = @json($hargaSementara);
            var hargaPertama = hargaSementara.find(harga => product.id == harga.id_product);
            if (hargaPertama && hargaPertama.date_first <= dateNow) {
                hargaJual = hargaPertama.harga_sementara;
            }

            const displayHarga = (scanLabel === 'VOD' || scanLabel === 'RTN') ? -hargaJual : hargaJual;
            const topTbody = document.querySelector('tbody.top');
            const staticRow = topTbody.querySelector('tr:first-child');

            // === Handle khusus untuk label VOD ===
            if (scanLabel === 'VOD') {
                let namaDitemukan = productDetails.some((data) => data.nama === nama);
                let kodeDitemukan = productDetails.some((data) => data.kode_alternatif === kode);
                let jumlahKurang = productDetails.some((data) => data.order >= orderValue);

                if (!kodeDitemukan) {
                    alert('DATA TIDAK DITEMUKAN!');
                    resetBarcodeInput('VOD');
                    return;
                }

                if (!namaDitemukan) {
                    alert('DATA TIDAK DITEMUKAN!');
                    resetBarcodeInput('VOD');
                    return;
                }

                if (kodeDitemukan && !jumlahKurang) {
                    alert('JUMLAH TIDAK BOLEH LEBIH BANYAK DARI DATA!');
                    resetBarcodeInput('VOD');
                    return;
                }

                // Jika lolos validasi → lanjut tambah baris
            }

            // Tambah row baru
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="text-center" style="width: 75px;">${scanLabel}</td>
                <td class="text-center" style="width: 200px;">${kode}</td>
                <td>${product.nama}/${product.unit_jual}</td>
                <td class="text-end" style="width: 100px;">${orderValue}</td>
                <td class="text-end" style="width: 175px;">${number_format(displayHarga)}</td>
                <td class="text-end" id="input-diskon" style="width: 175px;">0</td>
                <td class="text-end" id="value-total" style="width: 175px;">${number_format(displayHarga * orderValue)}</td>
            `;

            const lastInsertedRow = topTbody.querySelector('tr:not(:first-child):last-child');
            if (lastInsertedRow) {
                topTbody.insertBefore(newRow, lastInsertedRow);
            } else {
                topTbody.insertBefore(newRow, staticRow);
            }

            // Update total harga
            grandTotal += displayHarga * orderValue;
            grandDiskon += 0;
            grandTotalDiskon += displayHarga * orderValue;
            document.getElementById("big-total").innerHTML = number_format(grandTotalDiskon);
            document.getElementById("small-jumlah").innerHTML = number_format(grandTotal);
            document.getElementById("small-diskon").innerHTML = number_format(grandDiskon);
            document.getElementById("small-total").innerHTML = number_format(grandTotalDiskon);

            productDetails.push({
                label: scanLabel,
                kode: product.kode,
                kode_alternatif: product.kode_alternatif,
                nama: product.nama,
                unit_jual: product.unit_jual,
                stok: product.stok,
                order: displayOrder,
                price: displayHarga,
                field_total: orderValue * displayHarga,
                kode_sumber: product.kode_sumber,
                diskon1: 0,
                diskon2: 0,
                diskon3: 0,
                penjualan_rata: 0,
                waktu_kunjungan: 0,
                stok_minimum: 0,
                stok_maksimum: 0,
                is_ppn: 0,
            });

            // Set nama besar dan reset input
            document.getElementById("big-name").innerHTML = product.nama + '/' + product.unit_jual;
            resetBarcodeInput(scanLabel);
        }

        function resetBarcodeInput(label) {
            barcodeValues.length = 0;
            document.getElementById("input-text").innerHTML = 1;
            document.getElementById("input-order").value = 1;
            document.getElementById('barcode-input').value = '';

            if (label === 'RTN') {
                document.getElementById('label-cell').innerText = label;
                document.getElementById('barcodeInputReturn').focus();
            } else {
                document.getElementById('label-cell').innerText = 'PLU';
            }
        }
    // implementasi klik yang tidak menggunakan otoritas

    // store data ke tabel pengembalian server
        function submitData() {
            const detail = JSON.stringify({
                detail: productDetails,
                supplier: {{ $supplier->id }},
                grandTotal: grandTotal,
                // grandDiskon: grandDiskon
            });
            fetch('{{ route('store-kredit-data') }}', {
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

        function printReceipt(printData) {
            const htmlContent = `
                <html>
                    <head>
                        <title>Struk Pembayaran</title>
                        <style>
                            body {
                                font-family: monospace;
                                font-size: 14px;
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
            `;

            const printWindow = window.open('', '', 'height=400,width=300');
            printWindow.document.write('<pre>' + printData + '</pre>');
            printWindow.document.close();
            printWindow.print();

            // qz.security.setSignaturePromise(function (toSign) {
            //     return function (resolve, reject) {
            //         console.log("🔐 Requesting signature for:", toSign); // Log toSign yang akan ditandatangani

            //         fetch("/sign-message", {
            //             method: "POST",
            //             headers: {
            //                 'Content-Type': 'text/plain'
            //             },
            //             body: toSign
            //         })
            //         .then(response => response.text())
            //         .then(signature => {
            //             console.log("✅ Signature received:", signature); // Log signature yang diterima
            //             resolve(signature);
            //         })
            //         .catch(err => {
            //             console.error("🔥 Error requesting signature:", err);
            //             reject(err);
            //         });
            //     };
            // });

            // qz.security.setCertificatePromise(function (resolve, reject) {
            //     console.log("🔑 Requesting certificate from /cert.pem...");

            //     fetch("/cert.pem")
            //         .then(response => {
            //             if (!response.ok) {
            //                 console.error("❌ Failed to fetch certificate, status:", response.status);
            //                 reject("Failed to fetch certificate with status " + response.status);
            //                 return;
            //             }
            //             return response.text();
            //         })
            //         .then(cert => {
            //             console.log("✅ Certificate received:", cert); // LOG sertifikat
            //             resolve(cert);
            //         })
            //         .catch(err => {
            //             console.error("🔥 Error fetching certificate:", err); // LOG error
            //             reject(err);
            //         });
            // });

            // // Menghubungkan dengan QZ Tray dan mengirimkan HTML untuk dicetak
            // qz.websocket.connect().then(() => {
            //     const config = qz.configs.create("EPSON TM-U220 Receipt"); // Ganti sesuai nama printer
            //     const payload = [
            //         {
            //             type: 'html',
            //             format: 'plain',
            //             data: htmlContent + "\n" // Tambah feed kertas
            //         }
            //     ];

            //     return qz.print(config, payload);
            // }).then(() => {
            //     console.log("Struk berhasil dicetak.");
            //     qz.websocket.disconnect(); // Tutup koneksi jika tidak ada cetakan berikutnya
            // }).catch(err => {
            //     console.error("Gagal cetak:", err);
            //     alert("Gagal mencetak: Pastikan QZ Tray sedang aktif.");
            // });
        }
    // cetak data

    // number format
        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    // number format
    </script>

    {{-- include script modal --}}
    @include('modal-script')
@endsection
