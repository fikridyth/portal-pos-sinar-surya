<div class="modal" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body">
                <div class="d-flex justify-content-center mt-4">
                    <div style="overflow-x: auto; height: 565px; border: 1px solid #ccc;">
                        <table class="table" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr style="border: 1px solid black; font-size: 12px;">
                                    <th class="text-center" style="font-size: 20px;">TOMBOL</th>
                                    <th class="text-center" style="font-size: 20px;">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody class="align-items-center">
                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>></b></td>
                                    <td style="font-size: 24px;">CASH</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F11</b></td>
                                    <td style="font-size: 24px;">SEARCH</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F4</b></td>
                                    <td style="font-size: 24px;">VOID</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F5</b></td>
                                    <td style="font-size: 24px;">ALL VOID</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F6</b></td>
                                    <td style="font-size: 24px;">RETURN</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>?</b></td>
                                    <td style="font-size: 24px;">SUBTOTAL</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>P</b></td>
                                    <td style="font-size: 24px;">DISKON PERSEN</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>+</b></td>
                                    <td style="font-size: 24px;">DISKON RUPIAH</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F7</b></td>
                                    <td style="font-size: 24px;">END OF DAY</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F8</b></td>
                                    <td style="font-size: 24px;">HOLD</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F9</b></td>
                                    <td style="font-size: 24px;">LIST HOLD</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>-</b></td>
                                    <td style="font-size: 24px;">CLEAR</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>R</b></td>
                                    <td style="font-size: 24px;">LIST PEMBELIAN</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>V</b></td>
                                    <td style="font-size: 24px;">TRANSFER KE SERVER</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>Y</b></td>
                                    <td style="font-size: 24px;">KEMBALI BARANG SUPPLIER</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 2px solid white; margin-top: 3px;"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="font-size: 24px;"><b>F2</b></td>
                                    <td style="font-size: 24px;">SIGN OFF</td>
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
