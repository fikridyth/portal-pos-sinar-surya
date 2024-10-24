<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\DataTables\SupplierDataTable;
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

        return view('dashboard', compact('title', 'saPass', 'myPass'));
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
        // $product = Product::where('kode_alternatif', $kode)->first();
        $product = Product::where('kode_alternatif', $kode)->first();
        if (!isset($product)) {
            return response()->json(['error' => 'Barcode Not Found!']);
        }

        return response()->json(['product' => $product]);
    }

    public function storePenjualan(Request $request)
    {
        $products = $request->input('products');

        $sequence = '000001';
        $getLastPo = Penjualan::max("no");
        // $dateNow = now()->format('Y-m-d');
        // if ($getLastPo) {
        //     $explodeLastPo = explode('-', $getLastPo);
        //     if ($explodeLastPo[1] == $dateNow) {
        //         $sequence = (int) $explodeLastPo[2] + 1;
        //     } else {
        //         (int) $sequence;
        //     }
        // } else {
        //     (int) $sequence;
        // } 
        if ($getLastPo) {
            $sequence = (int) $getLastPo + 1;
        } else {
            (int) $sequence;
        }
        $getNomor = str_pad($sequence, 6, 0, STR_PAD_LEFT);

        // dd($products);
        if ($products !== []) {
            $penjualan = Penjualan::create([
                'tanggal' => Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                'jam' => Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s'),
                'detail' => json_encode($products),
                'total' => $request->grandTotal,
                'diskon' => $request->grandDiskon,
                'grand_total' => $request->grandTotal - $request->grandDiskon,
                'no' => $getNomor,
                'created_by' => auth()->user()->name
            ]);
        } else {
            return response()->json(['error' => 'No data scanned!']);
        }

        $printData = $this->formatPrintData($penjualan, $products);
        
        return response()->json(['penjualan' => $penjualan, 'printData' => $printData]);
    }

    public function printPembelian(Request $request)
    {
        $penjualan = Penjualan::find($request->id);
        $products = json_decode($request->input('detail'), true);
        $printData = $this->formatPrintData($penjualan, $products);

        return response()->json(['printData' => $printData]);
    }

    private function formatPrintData($penjualan, $products)
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
                            font-size: 12px; 
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
            $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        }
        
        $output .= "<div class='separator'></div>";
        $output .= "<div class='product-item'>";
        $output .= "<span>TOTAL</span>";
        $output .= "<span>" . number_format($penjualan->grand_total, 0, ',', '.') . "</span>";
        $output .= "</div>";
        $output .= "<div class='product-item'>";
        $output .= "<span class='total'>" . auth()->user()->name . "</span>";
        $output .= "<span class='total'>( NO#:{$penjualan->no} )</span>";
        $output .= "<span class='total'>QTY: " . count($products) . "</span>";
        $output .= "</div>";
        $output .= "<div class='header total' style='margin-top: 10px;'>TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
                </body>
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
        $penjualans = Hold::whereNull('is_used')->get();

        return view('list-hold', compact('title', 'penjualans'));
    }

    public function indexHold($id)
    {
        $title = 'Index Hold';
        $hold = Hold::find($id);

        return view('index-hold', compact('title', 'hold'));
    }

    // product
    public function listBarang(ProductDataTable $dataTable)
    {
        $title = 'List Barang';

        return $dataTable->render('list-barang', compact('title'));
    }

    // supplier
    public function listSupplier(SupplierDataTable $dataTable)
    {
        $title = 'List Supplier';

        return $dataTable->render('list-supplier', compact('title'));
    }

    public function indexSupplier($id)
    {
        $title = 'Kembali Barang';
        $supplier = Supplier::find($id);

        return view('index-supplier', compact('title', 'supplier'));
    }

    // return
    public function storeReturnData(Request $request)
    {
        // get nomor return
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastReturn = PengembalianSecond::max("nomor_return");
        if ($getLastReturn) {
            $explodeLastReturn = explode('-', $getLastReturn);
            if ($explodeLastReturn[1] == $dateNow) {
                $sequence = (int) $explodeLastReturn[2] + 1;
            } else {
                (int) $sequence;
            }
        } else {
            (int) $sequence;
        }
        $getNomorReturn = 'RR-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        
        date_default_timezone_set('Asia/Bangkok');
        $pengembalian = PengembalianSecond::create([
            'id_supplier' => $request->supplier,
            'nomor_return' => $getNomorReturn,
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
                            font-size: 12px; 
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
            $output .= "<span>" . number_format($product['grand_total'], 0, ',', '.') . "</span>";
            $output .= "</div>";
        }
        
        $output .= "<div class='separator'></div>";
        $output .= "<div class='product-item'>";
        $output .= "<span>TOTAL</span>";
        $output .= "<span>" . number_format($pengembalian->grand_total, 0, ',', '.') . "</span>";
        $output .= "</div>";
        $output .= "<div class='product-item'>";
        $output .= "<span class='total'>" . auth()->user()->name . "</span>";
        $output .= "<span class='total'>( NO#:{$pengembalian->no} )</span>";
        $output .= "<span class='total'>QTY: " . count($products) . "</span>";
        $output .= "</div>";
        $output .= "<div class='header total' style='margin-top: 10px;'>TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
                </body>
            </html>";
        return $output;
    }
}