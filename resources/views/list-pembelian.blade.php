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
                                    <td class="text-center text-black"><input type="checkbox" class="preorder-checkbox" data-detail="{{ json_encode($penjualan->detail) }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group col-8">
                <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc; background-color: white;">
                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th class="text-center text-black">NAMA BARANG</th>
                                <th class="text-center text-black">JUMLAH</th>
                                <th class="text-center text-black">HARGA</th>
                                <th class="text-center text-black">DISKON</th>
                                <th class="text-center text-black">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="orderDetailTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-2">
        <button type="button" onclick="window.history.back()" class="btn btn-danger mt-3 mx-3">C = CETAK</button>
        <button type="button" onclick="window.history.back()" class="btn btn-danger mt-3 mx-3">R = KEMBALI</button>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.preorder-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const detail = JSON.parse(this.getAttribute('data-detail'));
                const tbody = document.getElementById('orderDetailTableBody');
                
                if (this.checked) {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.disabled = true;
                        }
                    });

                    tbody.innerHTML = ''; // Clear existing rows before adding new ones
                    JSON.parse(detail).forEach(item => {
                        // console.log(item);
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td class="text-black">${item.nama}</td>
                            <td class="text-center text-black">${item.order}</td>
                            <td class="text-end text-black">${number_format(item.harga)}</td>
                            <td class="text-end text-black">${number_format(item.diskon)}</td>
                            <td class="text-end text-black">${number_format(item.grand_total)}</td>
                        `;
                        tbody.appendChild(newRow);
                    });

                    const currentCheckbox = this;
                    const currentDetail = detail;

                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'c' || event.key === 'C') {
                            event.preventDefault();
                            const id = currentCheckbox.closest('tr').dataset.id;

                            // AJAX request to your server
                            fetch('/print-pembelian', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token if needed
                                },
                                body: JSON.stringify({ id: id, detail: currentDetail })
                            })
                            .then(response => response.json())
                            .then(data => {
                                printReceipt(data.printData);
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    }, { once: true });
                } else {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        otherCheckbox.disabled = false;
                    });

                    tbody.innerHTML = ''; // Clear the table if checkbox is unchecked
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
