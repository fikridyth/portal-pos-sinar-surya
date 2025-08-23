<?php

namespace App\Http\Controllers;

use App\Models\Hold;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\ProductSecond;
use App\Models\ProductStockSecond;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function sendPenjualan()
    {
        try {
            $penjualans = Penjualan::whereNull('is_send')->get();
            $productOrders = [];
            foreach ($penjualans as $penjualan) {
                $details = json_decode($penjualan->detail, true);
                foreach ($details as $detail) {
                    $kode = $detail['kode'];
                    $order = $detail['order'];
                    $nama = explode('/', $detail['nama']);
                    $getUnitJual = str_replace('P', '', $nama[1]);
    
                    // update stok product di db pos dan server
                    $productPos = Product::where('kode', $kode)->first();
                    $productPos->update(['stok' => $productPos->stok - $order]);
    
                    $productServer = ProductSecond::where('kode', $kode)->first();
                    $productServer->update(['stok' => $productServer->stok - $order]);

                    // buat array untuk update product stock
                    if (isset($productOrders[$kode])) {
                        // If it exists, add to the total order
                        $productOrders[$kode]['order'] += $detail['order'];
                    } else {
                        // If it does not exist, initialize it
                        $productOrders[$kode] = [
                            'kode' => $kode,
                            'order' => (int) $detail['order'],
                            'unit_jual' => (int) $getUnitJual
                        ];
                    }
                }
            }
    
            $allProducts = array_values($productOrders);
            if ($allProducts == []) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data yang dikirim!']);
            } else {
                foreach ($allProducts as $product) {
                    if ($product['order'] > 0) {
                        ProductStockSecond::create([
                            'tipe' => 'POS',
                            'tanggal' => $penjualans[0]->tanggal,
                            'kode' => $product['kode'],
                            'total' => -$product['order'],
                            'unit_jual' => $product['unit_jual']
                        ]);
                    } else {
                        ProductStockSecond::create([
                            'tipe' => 'POS',
                            'tanggal' => $penjualans[0]->tanggal,
                            'kode' => $product['kode'],
                            'total' => abs($product['order']),
                            'unit_jual' => $product['unit_jual']
                        ]);
                    }
                }
            }
    
            // update is_send
            foreach ($penjualans as $penjualan) {
                $penjualan->update(['is_send' => 1]);
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil dikirim ke server!', 'data' => $allProducts], 200);
        } catch (Exception $e) {
            // Log the exception message
            \Log::error('An error occurred: ' . $e->getMessage());
        
            // Optionally, you can return a response or handle the error as needed
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function endOfDay()
    {
        try {
            $penjualans = Penjualan::all();
            $holds = Hold::all();
            $productOrders = [];
            foreach ($penjualans as $penjualan) {
                $details = json_decode($penjualan->detail, true);
                foreach ($details as $detail) {
                    $kode = $detail['kode'];
                    $order = $detail['order'];
                    $nama = explode('/', $detail['nama']);
                    $getUnitJual = str_replace('P', '', $nama[1]);
    
                    
                    try {
                        // Cek koneksi ke database client
                        DB::connection('mysql_second')->getPdo();

                        // update stok product di db pos dan server
                        $productPos = Product::where('kode', $kode)->first();
                        $productPos->update(['stok' => $productPos->stok - $order]);

                        // update juga di server
                        $productServer = ProductSecond::where('kode', $kode)->first();
                        $productServer->update(['stok' => $productServer->stok - $order]);
                    } catch (\PDOException $e) {
                        return response()->json(['success' => false, 'message' => 'Data gagal dikirim ke server!', 'data' => $allProducts], 200);
                    }

                    // buat array untuk update product stock
                    if (isset($productOrders[$kode])) {
                        // If it exists, add to the total order
                        $productOrders[$kode]['order'] += $detail['order'];
                    } else {
                        // If it does not exist, initialize it
                        $productOrders[$kode] = [
                            'kode' => $kode,
                            'order' => (int) $detail['order'],
                            'unit_jual' => (int) $getUnitJual
                        ];
                    }
                }
            }
    
            try {
                // Cek koneksi ke database client
                DB::connection('mysql_second')->getPdo();

                $allProducts = array_values($productOrders);
                if ($allProducts == []) {
                    return response()->json(['success' => false, 'message' => 'Tidak ada data yang dikirim!']);
                } else {
                    foreach ($allProducts as $product) {
                        if ($product['order'] > 0) {
                            ProductStockSecond::create([
                                'tipe' => 'POS',
                                'tanggal' => $penjualans[0]->tanggal,
                                'kode' => $product['kode'],
                                'total' => -$product['order'],
                                'unit_jual' => $product['unit_jual']
                            ]);
                        } else {
                            ProductStockSecond::create([
                                'tipe' => 'POS',
                                'tanggal' => $penjualans[0]->tanggal,
                                'kode' => $product['kode'],
                                'total' => abs($product['order']),
                                'unit_jual' => $product['unit_jual']
                            ]);
                        }
                    }
                }
            } catch (\PDOException $e) {
                return response()->json(['success' => false, 'message' => 'Data gagal dikirim ke server!', 'data' => $allProducts], 200);
            }
    
            // update is_send
            foreach ($penjualans as $penjualan) {
                $penjualan->delete();
            }
            
            foreach ($holds as $hold) {
                $hold->delete();
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil dikirim ke server!', 'data' => $allProducts], 200);
        } catch (Exception $e) {
            // Log the exception message
            \Log::error('An error occurred: ' . $e->getMessage());
        
            // Optionally, you can return a response or handle the error as needed
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
}
