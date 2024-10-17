<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Beranda';
        return view('dashboard', compact('title'));
    }

    public function listPembelian()
    {
        $title = 'List Pembelian';
        $penjualans = Penjualan::all();

        return view('list-pembelian', compact('title', 'penjualans'));
    }

    public function getDetailProducts($kode)
    {
        $product = Product::where('kode_alternatif', $kode)->first();

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
                        body { font-family: Arial, sans-serif; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .products { margin-top: 5px; }
                        .product-item { display: flex; justify-content: space-between; margin-top: 5px; margin-bottom: 5px; }
                        .total { margin-top: 5px; }
                        .separator { margin-top: 5px; border-top: 1px dashed black; }
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
        $output .= "</div><div class='header total' style='margin-top: 20px;'>TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
                </body>
            </html>";
        return $output;
    }
}
