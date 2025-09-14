<div class="modal" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body">
                <div class="d-flex justify-content-center mt-4">
                    <div id="scrollableDiv" style="overflow-x: auto; height: 565px; border: 1px solid #ccc;">
                        <table class="table" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr style="border: 1px solid black; font-size: 12px;">
                                    <th class="text-center" style="font-size: 20px;">TOMBOL</th>
                                    <th class="text-center" style="font-size: 20px;">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody class="align-items-center">
                                <tr>
                                    <td class="text-center" id="modal-cash-cell" data-bs-dismiss="modal"><b>></b></td>
                                    <td id="modal-cash-cell2" data-bs-dismiss="modal">CASH</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-search-cell" data-bs-dismiss="modal"><b>F11</b></td>
                                    <td id="modal-search-cell2" data-bs-dismiss="modal">SEARCH</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-void-cell" data-bs-dismiss="modal"><b>F4</b></td>
                                    <td id="modal-void-cell2" data-bs-dismiss="modal">VOID</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-all-void-cell" data-bs-dismiss="modal"><b>F5</b></td>
                                    <td id="modal-all-void-cell2" data-bs-dismiss="modal">ALL VOID</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-return-cell" data-bs-dismiss="modal"><b>F6</b></td>
                                    <td id="modal-return-cell2" data-bs-dismiss="modal">RETURN</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-subtotal-cell" data-bs-dismiss="modal"><b>?</b></td>
                                    <td id="modal-subtotal-cell2" data-bs-dismiss="modal">SUBTOTAL</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-percent-cell" data-bs-dismiss="modal"><b>P</b></td>
                                    <td id="modal-percent-cell2" data-bs-dismiss="modal">DISKON PERSEN</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-rupiah-cell" data-bs-dismiss="modal"><b>+</b></td>
                                    <td id="modal-rupiah-cell2" data-bs-dismiss="modal">DISKON RUPIAH</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-end-day-cell" data-bs-dismiss="modal"><b>F7</b></td>
                                    <td id="modal-end-day-cell2" data-bs-dismiss="modal">END OF DAY</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-hold-cell" data-bs-dismiss="modal"><b>F8</b></td>
                                    <td id="modal-hold-cell2" data-bs-dismiss="modal">HOLD</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-list-hold-cell" data-bs-dismiss="modal"><b>F9</b></td>
                                    <td id="modal-list-hold-cell2" data-bs-dismiss="modal">LIST HOLD</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-clear-cell" data-bs-dismiss="modal"><b>-</b></td>
                                    <td id="modal-clear-cell2" data-bs-dismiss="modal">CLEAR</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-pembelian-cell" data-bs-dismiss="modal"><b>R</b></td>
                                    <td id="modal-pembelian-cell2" data-bs-dismiss="modal">LIST PEMBELIAN</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-kasir-cell" data-bs-dismiss="modal"><b>F12</b></td>
                                    <td id="modal-kasir-cell2" data-bs-dismiss="modal">LAPORAN KASIR</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-transfer-cell" data-bs-dismiss="modal"><b>V</b></td>
                                    <td id="modal-transfer-cell2" data-bs-dismiss="modal">TRANSFER KE SERVER</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-kembali-cell" data-bs-dismiss="modal"><b>Y</b></td>
                                    <td id="modal-kembali-cell2" data-bs-dismiss="modal">KEMBALI BARANG SUPPLIER</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-kredit-cell" data-bs-dismiss="modal"><b>U</b></td>
                                    <td id="modal-kredit-cell2" data-bs-dismiss="modal">PENJUALAN KREDIT</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" id="modal-signoff-cell" data-bs-dismiss="modal"><b>F2</b></td>
                                    <td id="modal-signoff-cell2" data-bs-dismiss="modal">SIGN OFF</td>
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
