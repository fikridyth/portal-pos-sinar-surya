<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\DataTables\SupplierDataTable;
use App\Models\HargaSementara;
use App\Models\Hold;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\ProductSecond;
use App\Models\SupplierSecond;
use App\Models\PengembalianSecond;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Beranda';
        $saPass = User::where('name', 'LO HARYANTO')->pluck('show_password')->first();
        $myPass = auth()->user()->show_password;

        $now = now()->format('Y-m-d');
        // $hargaSementara = HargaSementara::where('date_first', '>=' ,$now)->orderBy('date_first', 'asc')->get();
        $hargaSementara = HargaSementara::where('date_first', '<=', $now)->orderBy('date_first', 'desc')->get();

        return view('dashboard', compact('title', 'saPass', 'myPass', 'now', 'hargaSementara'));
    }

    // pembelian
    public function listPembelian()
    {
        $title = 'List Pembelian';
        $penjualans = Penjualan::whereNull('is_send')->get();

        return view('list-pembelian', compact('title', 'penjualans'));
    }

    public function getDetailProducts($kode)
    {
        $product = Product::where('kode_alternatif', $kode)->first();
        if (!isset($product)) {
            return response()->json(['error' => 'Barcode Tidak Ditemukan!']);
        }

        return response()->json(['product' => $product]);
    }

    public function storePenjualan(Request $request)
    {
        // dd($request->all());
        $products = $request->input('products');
        $sequence = '000001';
        $getLastPo = Penjualan::max("no");
        if ($getLastPo) {
            $sequence = (int) $getLastPo + 1;
        } else {
            (int) $sequence;
        }
        $getNomor = str_pad($sequence, 6, 0, STR_PAD_LEFT);

        $valueGrandTotal = $request->grandTotal - $request->grandDiskon;
        $dataPenjualan = [
            'tanggal' => Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'),
            'jam' => Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s'),
            'detail' => json_encode($products),
            'total' => $request->grandTotal,
            'diskon' => $request->grandDiskon,
            'grand_total' => $valueGrandTotal,
            'payment' => $request->payment ?? 0,
            'return' => $request->payment ? $request->payment - $valueGrandTotal : $valueGrandTotal,
            'no' => $getNomor,
            'created_by' => auth()->user()->name
        ];

        // dd($products);
        if ($products !== []) {
            Penjualan::create($dataPenjualan);
        } else {
            return response()->json(['error' => 'No data scanned!']);
        }

        $printData = $this->formatPrintData($dataPenjualan, $products);
        
        return response()->json(['penjualan' => $dataPenjualan, 'printData' => $printData]);
    }

    private function formatPrintData($penjualan, $products)
    {
        // dd($products);
        // <div class='header'>------- " . Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y') . " -------</div>";
        $output = "
            <html>
                <head>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            width: 58mm; 
                            margin: 0 auto; 
                        }
                        .header-item { 
                            display: flex; 
                            justify-content: space-between;
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: 10px; 
                            font-size: 10px; 
                        }
                        .products { 
                            margin-top: 5px; 
                        }
                        .product-item { 
                            display: flex; 
                            justify-content: space-between; 
                            margin-top: 2px; 
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .total { 
                            margin-top: 8px; 
                            font-weight: bold; 
                        }
                        .separator { 
                            margin-top: 5px; 
                            border-top: 1px dashed black; 
                        }
                        .separator-second {
                            margin-top: -5px; 
                            border-top: 1px dashed black; 
                        }
                    </style>
                </head>
                <body>";

        $output .= "<div class='header-item'>";
        $output .= "<span>***</span>";
        $output .= "<span>SINAR SURYA</span>";
        $output .= "<span>***</span>";
        $output .= "</div>";

        $output .= "<div class='header-item'>";
        $output .= "<span>*</span>";
        $output .= "<span>JL. PASAR BARU NO. 42</span>";
        $output .= "<span>*</span>";
        $output .= "</div>";

        $output .= "<div class='header-item'>";
        $output .= "<span>***</span>";
        $output .= "<span>BOGOR</span>";
        $output .= "<span>***</span>";
        $output .= "</div>";

        $output .= "<div class='header-item' style='margin-bottom: 15px;'>";
        $output .= "<span>TELP. (0251)8324647</span>";
        $output .= "<span>FAX. (0251) 835702</span>";
        $output .= "</div>";

        $output .= "<div class='header'>------- " . Carbon::parse($penjualan['tanggal'])->format('d/m/Y') . " ({$penjualan['jam']}) -------</div>";
        
        foreach ($products as $product) {
            // dd($products);
            $output .= "<div class='product-item'>";
            $output .= "<span>{$product['nama']}</span>";
            if ($product['order'] <= 1) {
                $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
                
            }
            $output .= "</div>";
            if ($product['order'] > 1) {
                $output .= "<div class='product-item'>";
                $output .= "<span style='margin-left: 20px;'>{$product['order']} X ". number_format($product['harga'], 0, ',', '.') . "</span>";
                $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
                $output .= "</div>";
            }
        }
        
        $output .= "<div class='separator'></div>";
        $output .= "<div class='product-item' style='margin-top: 7px;'>";
        $output .= "<span>TOTAL</span>";
        $output .= "<span>" . number_format($penjualan['total'], 0, ',', '.') . "</span>";
        $output .= "</div>";
        if ($penjualan['payment'] !== 0) {
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>DISKON</span>";
            $output .= "<span>" . number_format($penjualan['diskon'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>TENDER 01</span>";
            $output .= "<span>" . number_format($penjualan['payment'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>KEMBALI</span>";
            $output .= "<span>" . number_format($penjualan['return'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        } else {
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>DISKON</span>";
            $output .= "<span>" . number_format($penjualan['diskon'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>TENDER 01</span>";
            $output .= "<span>" . number_format($penjualan['grand_total'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        }
        $output .= "<div class='product-item'>";
        $output .= "<span class='total'>" . auth()->user()->name . "</span>";
        $output .= "<span class='total'>( NO#:{$penjualan['no']} )</span>";
        $output .= "<span class='total'>QTY: " . array_sum(array_column($products, 'order')) . "</span>";
        $output .= "</div>";
        $output .= "<div class='header total' style='margin-top: 10px;'>TERIMA KASIH ATAS KUNJUNGAN ANDA</div>";
        $output .= "<div class='separator'></div>";
        $output .= "<div class='separator-second'></div>";

        // Pisahkan data VOD dan RTN
        $vodItems = array_filter($products, fn($item) => $item['label'] === 'VOD');
        $rtnItems = array_filter($products, fn($item) => $item['label'] === 'RTN');

        if (!empty($vodItems) || !empty($rtnItems)) {
            $output .= "<div style='margin-top: 80px;'></div>";
            $output .= "<div class='header'>------- " . Carbon::parse($penjualan['tanggal'])->format('d/m/Y') . " ({$penjualan['jam']}) -------</div>";
            // VOD section
            if (!empty($vodItems)) {
                $output .= "<div style='margin-top: 8px; font-weight: bold;'>VOID</div>";
                foreach ($vodItems as $item) {
                    $output .= "<div class='product-item'>";
                    $output .= "<span>{$item['nama']}</span>";
                    $output .= "<span>" . number_format($item['grand_total'], 0, ',', '.') . "</span>";
                    $output .= "</div>";
                }
            }

            // RTN section
            if (!empty($rtnItems)) {
                $output .= "<div style='margin-top: 8px; font-weight: bold;'>RETURN</div>";
                foreach ($rtnItems as $item) {
                    $output .= "<div class='product-item'>";
                    $output .= "<span>{$item['nama']}</span>";
                    $output .= "<span>" . number_format($item['grand_total'], 0, ',', '.') . "</span>";
                    $output .= "</div>";
                }
            }
        }

        $output .= "</body>
            </html>";
        return $output;
    }

    public function storeVoidData(Request $request)
    {
        // dd($request->all());
        $products = $request->input('products');
        $sequence = '000001';
        $getLastPo = Penjualan::max("no");
        if ($getLastPo) {
            $sequence = (int) $getLastPo + 1;
        } else {
            (int) $sequence;
        }
        $getNomor = str_pad($sequence, 6, 0, STR_PAD_LEFT);

        $valueGrandTotal = $request->grandTotal - $request->grandDiskon;
        $dataPenjualan = [
            'tanggal' => Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'),
            'jam' => Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s'),
            'detail' => json_encode($products),
            'total' => $request->grandTotal,
            'diskon' => $request->grandDiskon,
            'grand_total' => $valueGrandTotal,
            'payment' => $request->payment ?? 0,
            'return' => $request->payment ? $request->payment - $valueGrandTotal : $valueGrandTotal,
            'no' => $getNomor,
            'created_by' => auth()->user()->name
        ];

        // dd($products);
        if ($products == []) {
            return response()->json(['error' => 'No data scanned!']);
        }

        $printData = $this->formatPrintDataVoid($dataPenjualan, $products);
        
        return response()->json(['penjualan' => $dataPenjualan, 'printData' => $printData]);
    }
    private function formatPrintDataVoid($penjualan, $products)
    {
        // <div class='header'>------- " . Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y') . " -------</div>";
        $output = "
            <html>
                <head>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            width: 58mm; 
                            margin: 0 auto; 
                        }
                        .header-item { 
                            display: flex; 
                            justify-content: space-between;
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: 10px; 
                            font-size: 10px; 
                        }
                        .products { 
                            margin-top: 5px; 
                        }
                        .product-item { 
                            display: flex; 
                            justify-content: space-between; 
                            margin-top: 2px; 
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .total { 
                            margin-top: 8px; 
                            font-weight: bold; 
                        }
                        .separator { 
                            margin-top: 5px; 
                            border-top: 1px dashed black; 
                        }
                        .separator-second {
                            margin-top: -5px; 
                            border-top: 1px dashed black; 
                        }
                    </style>
                </head>
                <body>";

        $output .= "<div class='header-item'>";
        $output .= "<span>***</span>";
        $output .= "<span>SINAR SURYA</span>";
        $output .= "<span>***</span>";
        $output .= "</div>";

        $output .= "<div class='header-item'>";
        $output .= "<span>*</span>";
        $output .= "<span>JL. PASAR BARU NO. 42</span>";
        $output .= "<span>*</span>";
        $output .= "</div>";

        $output .= "<div class='header-item'>";
        $output .= "<span>***</span>";
        $output .= "<span>BOGOR</span>";
        $output .= "<span>***</span>";
        $output .= "</div>";

        $output .= "<div class='header-item' style='margin-bottom: 15px;'>";
        $output .= "<span>TELP. (0251)8324647</span>";
        $output .= "<span>FAX. (0251) 835702</span>";
        $output .= "</div>";

        $output .= "<div class='header'>------- " . Carbon::parse($penjualan['tanggal'])->format('d/m/Y') . " ({$penjualan['jam']}) -------</div>";
        
        foreach ($products as $product) {
            // dd($products);
            $output .= "<div class='product-item'>";
            $output .= "<span>{$product['nama']}</span>";
            if ($product['order'] <= 1) {
                $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
                
            }
            $output .= "</div>";
            if ($product['order'] > 1) {
                $output .= "<div class='product-item'>";
                $output .= "<span style='margin-left: 20px;'>{$product['order']} X ". number_format($product['harga'], 0, ',', '.') . "</span>";
                $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
                $output .= "</div>";
            }
        }
        
        $output .= "<div class='separator'></div>";
        $output .= "<div class='product-item' style='margin-top: 7px;'>";
        $output .= "<span>TOTAL</span>";
        $output .= "<span>" . number_format($penjualan['total'], 0, ',', '.') . "</span>";
        $output .= "</div>";
        if ($penjualan['payment'] !== 0) {
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>DISKON</span>";
            $output .= "<span>" . number_format($penjualan['diskon'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>TENDER 01</span>";
            $output .= "<span>" . number_format($penjualan['payment'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>KEMBALI</span>";
            $output .= "<span>" . number_format($penjualan['return'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        } else {
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>DISKON</span>";
            $output .= "<span>" . number_format($penjualan['diskon'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>TENDER 01</span>";
            $output .= "<span>" . number_format($penjualan['grand_total'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        }
        $output .= "<div class='product-item'>";
        $output .= "<span class='total'>" . auth()->user()->name . "</span>";
        $output .= "<span class='total'>( NO#:{$penjualan['no']} )</span>";
        $output .= "<span class='total'>QTY: " . array_sum(array_column($products, 'order')) . "</span>";
        $output .= "</div>";
        $output .= "<div class='header total' style='margin-top: 10px;'>(** ALL VOID **)</div>";
        $output .= "<div class='separator'></div>";
        $output .= "<div class='separator-second'></div>
                </body>
            </html>";
        return $output;
    }

    public function ReprintPembelian(Request $request)
    {
        $penjualan = Penjualan::find($request->id);
        $products = json_decode($request->input('detail'), true);
        $printData = $this->formatReprintData($penjualan, $products);

        return response()->json(['printData' => $printData]);
    }

    public function ReprintPembelianKarton(Request $request)
    {
        $penjualan = Penjualan::find($request->id);
        $products = json_decode($request->input('detail'), true);
        $productCartons = [];
        foreach ($products as $product) {
            $nama = $product['nama'];
            $parts = explode('P', $nama);
            $getNumber = end($parts);

            if ((int)$getNumber > 1) {
                $productCartons[] = $product;
            }
        }
        $printData = $this->formatReprintData($penjualan, $productCartons);

        return response()->json(['printData' => $printData]);
    }

    private function formatReprintData($penjualan, $products)
    {
        // <div class='header'>------- " . Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y') . " -------</div>";
        $output = "
            <html>
                <head>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            width: 58mm; 
                            margin: 0 auto; 
                        }
                        .header-item { 
                            display: flex; 
                            justify-content: space-between;
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: 10px; 
                            font-size: 10px; 
                        }
                        .products { 
                            margin-top: 5px; 
                        }
                        .product-item { 
                            display: flex; 
                            justify-content: space-between; 
                            margin-top: 2px; 
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .total { 
                            margin-top: 8px; 
                            font-weight: bold; 
                        }
                        .separator { 
                            margin-top: 5px; 
                            border-top: 1px dashed black; 
                        }
                        .separator-second {
                            margin-top: -5px; 
                            border-top: 1px dashed black; 
                        }
                    </style>
                </head>
                <body>";

        $output .= "<div class='header-item'>";
        $output .= "<span>***</span>";
        $output .= "<span>SINAR SURYA</span>";
        $output .= "<span>***</span>";
        $output .= "</div>";

        $output .= "<div class='header-item'>";
        $output .= "<span>*</span>";
        $output .= "<span>JL. PASAR BARU NO. 42</span>";
        $output .= "<span>*</span>";
        $output .= "</div>";

        $output .= "<div class='header-item'>";
        $output .= "<span>***</span>";
        $output .= "<span>BOGOR</span>";
        $output .= "<span>***</span>";
        $output .= "</div>";

        $output .= "<div class='header-item' style='margin-bottom: 15px;'>";
        $output .= "<span>TELP. (0251)8324647</span>";
        $output .= "<span>FAX. (0251) 835702</span>";
        $output .= "</div>";

        $output .= "<div class='header'>------- " . Carbon::parse($penjualan['tanggal'])->format('d/m/Y') . " ({$penjualan['jam']}) -------</div>";
        
        foreach ($products as $product) {
            // dd($product['order']);
            $output .= "<div class='product-item'>";
            $output .= "<span>{$product['nama']}</span>";
            if ($product['order'] <= 1) {
                $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
            }
            $output .= "</div>";
            if ($product['order'] > 1) {
                $output .= "<div class='product-item'>";
                $output .= "<span style='margin-left: 20px;'>{$product['order']} X ". number_format($product['harga'], 0, ',', '.') . "</span>";
                $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
                $output .= "</div>";
            }
        }
        
        $output .= "<div class='separator'></div>";
        $output .= "<div class='product-item' style='margin-top: 7px;'>";
        $output .= "<span>TOTAL</span>";
        $output .= "<span>" . number_format($penjualan['total'], 0, ',', '.') . "</span>";
        $output .= "</div>";
        if ($penjualan['payment'] !== 0) {
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>DISKON</span>";
            $output .= "<span>" . number_format($penjualan['diskon'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>TENDER 01</span>";
            $output .= "<span>" . number_format($penjualan['payment'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>KEMBALI</span>";
            $output .= "<span>" . number_format($penjualan['return'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        } else {
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>DISKON</span>";
            $output .= "<span>" . number_format($penjualan['diskon'], 0, ',', '.') . "</span>";
            $output .= "</div>";
            $output .= "<div class='product-item' style='margin-top: 2px;'>";
            $output .= "<span>TENDER 01</span>";
            $output .= "<span>" . number_format($penjualan['grand_total'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        }
        $output .= "<div class='product-item'>";
        $output .= "<span class='total'>" . auth()->user()->name . "</span>";
        $output .= "<span class='total'>( NO#:{$penjualan['no']} )</span>";
        $output .= "<span class='total'>QTY: " . array_sum(array_column($products, 'order')) . "</span>";
        $output .= "</div>";
        $output .= "<div class='header total' style='margin-top: 10px;'>(** REPRINT **)</div>";
        $output .= "<div class='separator'></div>";
        $output .= "<div class='separator-second'></div>";

        // Pisahkan data VOD dan RTN
        $vodItems = array_filter($products, fn($item) => $item['label'] === 'VOD');
        $rtnItems = array_filter($products, fn($item) => $item['label'] === 'RTN');

        if (!empty($vodItems) || !empty($rtnItems)) {
            $output .= "<div style='margin-top: 80px;'></div>";
            $output .= "<div class='header'>------- " . Carbon::parse($penjualan['tanggal'])->format('d/m/Y') . " ({$penjualan['jam']}) -------</div>";
            // VOD section
            if (!empty($vodItems)) {
                $output .= "<div style='margin-top: 8px; font-weight: bold;'>VOID</div>";
                foreach ($vodItems as $item) {
                    $output .= "<div class='product-item'>";
                    $output .= "<span>{$item['nama']}</span>";
                    $output .= "<span>" . number_format($item['grand_total'], 0, ',', '.') . "</span>";
                    $output .= "</div>";
                }
            }

            // RTN section
            if (!empty($rtnItems)) {
                $output .= "<div style='margin-top: 8px; font-weight: bold;'>RETURN</div>";
                foreach ($rtnItems as $item) {
                    $output .= "<div class='product-item'>";
                    $output .= "<span>{$item['nama']}</span>";
                    $output .= "<span>" . number_format($item['grand_total'], 0, ',', '.') . "</span>";
                    $output .= "</div>";
                }
            }
        }

        $output .= "</body>
            </html>";
        return $output;
    }    

    // hold
    public function holdPenjualan(Request $request)
    {
        $products = $request->input('products');

        // dd($products);
        if ($products !== []) {
            Hold::create([
                'tanggal' => Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                'jam' => Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s'),
                'detail' => json_encode($products),
                'total' => $request->grandTotal,
                'diskon' => $request->grandDiskon,
                'grand_total' => $request->grandTotal - $request->grandDiskon,
                'created_by' => auth()->user()->name
            ]);
        } else {
            return response()->json(['error' => 'No data scanned!']);
        }
        
        return response()->json(['success' => 'Data Berhasil di Hold!']);
    }

    public function listHold()
    {
        $title = 'List Hold';
        $penjualans = Hold::whereNull('is_view')->get();

        return view('list-hold', compact('title', 'penjualans'));
    }

    public function indexHold($id)
    {
        $title = 'Index Hold';
        $hold = Hold::find($id);
        $hold->update(['is_view' => 1]);
        $saPass = User::where('name', 'LO HARYANTO')->pluck('show_password')->first();
        $myPass = auth()->user()->show_password;

        return view('index-hold', compact('title', 'hold', 'saPass', 'myPass'));
    }

    // list product for cash
    public function listBarang(ProductDataTable $dataTable)
    {
        $title = 'List Barang';
        $now = now()->format('Y-m-d');
        $hargaSementara = HargaSementara::where('date_first', '<=', $now)->orderBy('date_first', 'desc')->get();
        // dd($hargaSementara);

        return $dataTable->render('list-barang', compact('title', 'now', 'hargaSementara'));
    }

    // list product for supplier
    public function listBarangSupplier(ProductDataTable $dataTable, $id)
    {
        $title = 'List Barang';
        $now = now()->format('Y-m-d');
        $hargaSementara = HargaSementara::where('date_first', '<=', $now)->orderBy('date_first', 'desc')->get();

        return $dataTable->render('list-barang-supplier', compact('title', 'id', 'now', 'hargaSementara'));
    }

    // supplier
    public function listSupplier(SupplierDataTable $dataTable)
    {
        $title = 'List Supplier';

        return $dataTable->render('list-supplier', compact('title'));
    }

    public function indexSupplier($id)
    {
        // $id = dekrip($id);
        $title = 'Kembali Barang';
        $supplier = Supplier::find($id);

        $saPass = User::where('name', 'LO HARYANTO')->pluck('show_password')->first();
        $myPass = auth()->user()->show_password;

        $now = now()->format('Y-m-d');
        $hargaSementara = HargaSementara::where('date_first', '>=' ,$now)->orderBy('date_first', 'asc')->get();

        return view('index-supplier', compact('title', 'supplier', 'id', 'saPass', 'myPass', 'now', 'hargaSementara'));
    }

    // return
    public function storeReturnData(Request $request)
    {
        // get nomor return
        // $sequence = '0001';
        // $dateNow = now()->format('ym');
        // $getLastReturn = PengembalianSecond::max("nomor_return");
        // if ($getLastReturn) {
        //     $explodeLastReturn = explode('-', $getLastReturn);
        //     if ($explodeLastReturn[1] == $dateNow) {
        //         $sequence = (int) $explodeLastReturn[2] + 1;
        //     } else {
        //         (int) $sequence;
        //     }
        // } else {
        //     (int) $sequence;
        // }
        // $getNomorReturn = 'RR-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        
        // dd($request->all());
        date_default_timezone_set('Asia/Bangkok');
        $pengembalian = PengembalianSecond::create([
            'id_supplier' => $request->supplier,
            'nomor_return' => null,
            'date' => now()->format('Y-m-d'),
            'jam' => now()->format('H:i:s'),
            'total' => $request->grandTotal,
            'created_by' => auth()->user()->name,
            'detail' => json_encode($request->detail)
        ]);

        $detail = json_encode($request->detail);
        $products = json_decode($detail, true);

        $printData = $this->formatPrintReturnData($pengembalian, $products);

        return response()->json(['printData' => $printData]);
    }

    private function formatPrintReturnData($pengembalian, $products)
    {
        $output = "
            <html>
                <head>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            width: 58mm; 
                            margin: 0 auto; 
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: 10px; 
                            font-size: 12px; 
                        }
                        .products { 
                            margin-top: 5px; 
                        }
                        .product-item { 
                            display: flex; 
                            justify-content: space-between; 
                            margin-top: 2px; 
                            margin-bottom: 2px; 
                            font-size: 10px; 
                        }
                        .total { 
                            margin-top: 5px; 
                            font-weight: bold; 
                        }
                        .separator { 
                            margin-top: 5px; 
                            border-top: 1px dashed black; 
                        }
                    </style>
                </head>
                <body>
                    <div class='header'>------- " . Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y') . " -------</div>";
    
        foreach ($products as $product) {
            $output .= "<div class='product-item'>";
            $output .= "<span>{$product['nama']}</span>";
            if ($product['order'] == 1) {
                $output .= "<span>" . number_format($product['field_total'], 0, ',', '.') . "</span>";
            }
            $output .= "</div>";
            if ($product['order'] > 1) {
                $output .= "<div class='product-item'>";
                $output .= "<span style='margin-left: 20px;'>{$product['order']} X ". number_format($product['price'], 0, ',', '.') . "</span>";
                $output .= "<span>" . number_format($product['field_total'], 0, ',', '.') . "</span>";
                $output .= "</div>";
            }
        }
        
        $output .= "<div class='separator'></div>";
        $output .= "<div class='product-item'>";
        $output .= "<span>TOTAL</span>";
        $output .= "<span>" . number_format($pengembalian->total, 0, ',', '.') . "</span>";
        $output .= "</div>";
        $output .= "<div class='product-item'>";
        $output .= "<span class='total'>" . auth()->user()->name . "</span>";
        // $output .= "<span class='total'>( NO#:{$pengembalian->nomor_return} )</span>";
        $output .= "<span class='total'>( RETURN )</span>";
        $output .= "<span class='total'>QTY: " . count($products) . "</span>";
        $output .= "</div>";
        $output .= "<div class='header total' style='margin-top: 10px;'>TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
                </body>
            </html>";
        return $output;
    }

    public function laporanKasir()
    {
        $title = 'Laporan Kasir';

        return view('laporan-kasir', compact('title'));
    }

    public function indexLaporanKasir()
    {
        $title = 'Detail Laporan Kasir';
        $penjualans = Penjualan::get()
            ->groupBy('tanggal')
            ->map(function($items) {
                return [
                    'tanggal' => $items->pluck('tanggal')->first(),
                    'created_by' => $items->pluck('created_by')->first(),
                    'grand_total' => $items->sum('grand_total'),
                    'jam' => $items->max('jam'),
                    'transaksi' => count($items),
                ];
            });
        $totalHarga = $penjualans->sum('grand_total');

        return view('index-laporan-kasir', compact('title', 'penjualans', 'totalHarga'));
    }

    public function endOfDay()
    {
        $title = 'End Of Day';

        return view('end-of-day', compact('title'));
    }

    public function indexEndOfDay()
    {
        $title = 'Detail End Of Day';
        $penjualans = Penjualan::all();
        // dd($penjualans);

        return view('index-end-of-day', compact('title'));
    }
}