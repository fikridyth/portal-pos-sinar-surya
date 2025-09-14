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
        <div class="row w-100 mt-5">
            <h2 class="text-center mb-4" style="color: white;">LIST PEMBELIAN</h2>
            <div class="form-group col-4">
                <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc; background-color: white;">
                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th class="text-center text-black">KASIR</th>
                                <th class="text-center text-black">STRUK</th>
                                <th class="text-center text-black">JAM</th>
                                <th class="text-center text-black">PILIH</th>
                            </tr>
                        </thead>
                        <tbody id="preorderTableBody">
                            @foreach ($penjualans as $penjualan)
                                <tr data-id="{{ $penjualan->id }}">
                                    <td class="text-center text-black">{{ $penjualan->created_by }}</td>
                                    <td class="text-center text-black">{{ $penjualan->no }}</td>
                                    <td class="text-center text-black">{{ $penjualan->jam }}</td>
                                    <td class="text-center text-black"><input type="checkbox" class="preorder-checkbox" data-total="{{ $penjualan->grand_total }}" data-id ="{{ $penjualan->id }}" data-detail="{{ json_encode($penjualan->detail) }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group col-8">
                <div style="overflow-x: auto; height: 560px; border: 1px solid #ccc; background-color: white;">
                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th class="text-center text-black">NAMA BARANG</th>
                                <th class="text-center text-black" style="width: 100px;">JUMLAH</th>
                                <th class="text-center text-black" style="width: 100px;">HARGA</th>
                                <th class="text-center text-black" style="width: 100px;">DISKON</th>
                                <th class="text-center text-black" style="width: 100px;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="orderDetailTableBody">
                        </tbody>
                    </table>
                </div>
                <div style="height: 40px; border: 1px solid #ccc; margin-top: 0;">
                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                        <tbody>
                            <tr>
                                <th colspan="3" class="text-end" style="width: 75%;">TOTAL</th>
                                <th class="text-end value-total" style="width: 25%;">0</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-2">
        <a href="#" id="linkKarton" class="btn btn-danger mt-3 mx-3">V = CETAK KARTON</a>
        <a href="#" id="linkPilih" class="btn btn-danger mt-3 mx-3">C = CETAK</a>
        <button type="button" onclick="window.history.back()" class="btn btn-danger mt-3 mx-3">R = KEMBALI</button>
    </div>
@endsection

@section('scripts')
    <script>
        let activeCheckbox = null;
        let activeDetail = null;
        let activeDataId = null;

        document.querySelectorAll('.preorder-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const detail = JSON.parse(this.getAttribute('data-detail'));
                const dataId = JSON.parse(this.getAttribute('data-id'));
                const grandTotal = JSON.parse(this.getAttribute('data-total'));
                const tbody = document.getElementById('orderDetailTableBody');
                const totalCell = document.querySelector('.table tbody tr th.value-total');
                
                document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });

                if (this.checked) {
                    tbody.innerHTML = '';
                    let enableKarton = false;
                    JSON.parse(detail).forEach(item => {
                        // console.log(item);
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td class="text-black">${item.nama}</td>
                            <td class="text-end text-black" style="width: 100px;">${Math.abs(item.order)}</td>
                            <td class="text-end text-black" style="width: 100px;">${number_format(item.total)}</td>
                            <td class="text-end text-black" style="width: 100px;">${number_format(item.diskon)}</td>
                            <td class="text-end text-black" style="width: 100px;">${number_format(item.grand_total)}</td>
                        `;
                        tbody.appendChild(newRow);
                        const match = item.nama.match(/\/P(\d+)$/);
                        if (match && parseInt(match[1], 10) > 1) {
                            enableKarton = true;
                        }
                    });


                    const linkKarton = document.getElementById('linkKarton');
                    if (enableKarton) {
                        linkKarton.classList.remove('disabled');
                        linkKarton.style.pointerEvents = 'auto';
                        linkKarton.style.opacity = '1';
                    } else {
                        linkKarton.classList.add('disabled');
                        linkKarton.style.pointerEvents = 'none'; // Ini benar-benar memblok klik
                        linkKarton.style.opacity = '0.5'; // Untuk efek visual
                    }

                    totalCell.textContent = number_format(grandTotal);
                    const currentCheckbox = this;
                    const currentDetail = detail;
                    
                    activeCheckbox = this;
                    activeDetail = detail;
                    activeDataId = dataId;

                    document.addEventListener('keydown', function(event) {
                        if (!activeCheckbox) return;
                        if (event.key === 'c' || event.key === 'C') {
                            event.preventDefault();
                            const id = currentCheckbox.closest('tr').dataset.id;

                            // AJAX request to your server
                            fetch('/reprint-pembelian', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token if needed
                                },
                                body: JSON.stringify({ id: activeDataId, detail: activeDetail })
                            })
                            .then(response => response.json())
                            .then(data => {
                                printReceipt(data.printData);
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    }, { once: true });

                    document.getElementById('linkKarton').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default action of the link
                        if (!activeCheckbox) return;
                        const id = currentCheckbox.closest('tr').dataset.id;

                        // AJAX request to your server
                        fetch('/reprint-pembelian-karton', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token if needed
                            },
                            body: JSON.stringify({ id: activeDataId, detail: activeDetail })
                        })
                        .then(response => response.json())
                        .then(data => {
                            printReceipt(data.printData); // Assuming this function exists to handle printing the receipt
                        })
                        .catch(error => console.error('Error:', error));
                    });

                    document.addEventListener('keydown', function(event) {
                        if (!activeCheckbox) return;
                        if (event.key === 'v' || event.key === 'V') {
                            event.preventDefault();
                            const id = currentCheckbox.closest('tr').dataset.id;

                            // AJAX request to your server
                            fetch('/reprint-pembelian-karton', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token if needed
                                },
                                body: JSON.stringify({ id: activeDataId, detail: activeDetail })
                            })
                            .then(response => response.json())
                            .then(data => {
                                printReceipt(data.printData);
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    }, { once: true });

                    document.getElementById('linkPilih').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default action of the link
                        if (!activeCheckbox) return;
                        const id = currentCheckbox.closest('tr').dataset.id;

                        // AJAX request to your server
                        fetch('/reprint-pembelian', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token if needed
                            },
                            body: JSON.stringify({ id: activeDataId, detail: activeDetail })
                        })
                        .then(response => response.json())
                        .then(data => {
                            printReceipt(data.printData); // Assuming this function exists to handle printing the receipt
                        })
                        .catch(error => console.error('Error:', error));
                    });
                } else {
                    tbody.innerHTML = '';
                    totalCell.textContent = '0';

                    activeCheckbox = null;
                    activeDetail = null;
                    activeDataId = null;
                }
            });

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
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'r' || event.key === 'R') {
                event.preventDefault();
                window.location.href = '/dashboard';
            }

            if (event.key === '>' || event.key === '.') {
                event.preventDefault();
                window.location.href = '/dashboard';
            }
        });
    </script>
@endsection
