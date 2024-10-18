<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\ProductStockSecond;
use Exception;
use Illuminate\Http\Request;

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
                    // $totalOrder = $detail['order'];
                    $kode = $detail['kode'];
                    $nama = explode('/', $detail['nama']);
                    $getUnitJual = str_replace('P', '', $nama[1]);
    
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
            foreach ($allProducts as $product) {
                ProductStockSecond::create([
                    'tipe' => 'POS',
                    'tanggal' => $penjualans[0]->tanggal,
                    'kode' => $product['kode'],
                    'total' => -$product['order'],
                    'unit_jual' => $product['unit_jual']
                ]);
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
}
