<?php

namespace App\DataTables;

use App\Models\HargaSementara;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // return (new EloquentDataTable($query->whereNotNull('kode_alternatif')->where('kode_alternatif', '!=', '')->orderBy('created_at', 'desc')))
        if (request()->has('search') && request()->input('search.value') != '') {
            $search = request()->input('search.value');
        
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', $search . '%');
            });
        }
        return (new EloquentDataTable($query->where('status', 1)->orderBy('id', 'asc')))
        ->addIndexColumn()
        ->editColumn('created_at', function ($row) {
            return $row->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s');
        })
        ->editColumn('nama', function ($row) {
            return '<a href="#" 
            data-id="' . $row->id . '" 
            data-kode="' . $row->kode . '" 
            data-kode-sumber="' . $row->kode_sumber . '" 
            data-kode-alternatif="' . $row->kode_alternatif . '" 
            data-nama="' . $row->nama . '" 
            data-unit-jual="' . $row->unit_jual . '" 
            data-harga-jual="' . $row->harga_jual . '" 
            data-stok="' . $row->stok . '" 
            data-harga-pokok="' . $row->harga_pokok . '" 
            data-harga-sementara="' . $row->harga_sementara . '" 
            data-tanggal-awal="' . $row->tanggal_awal . '" 
            data-tanggal-akhir="' . $row->tanggal_akhir . '" 
            class="product-link">' . $row->nama . '/' . $row->unit_jual . '</a>';
        })
        ->editColumn('harga_pokok', function ($row) {
            return number_format($row->harga_pokok);
        })
        ->editColumn('harga_jual', function ($row) {
            $hargaJual = $row->harga_jual;
            $now = now()->format('Y-m-d');
            $hargaSementara = HargaSementara::where('date_first', '<=', $now)
                ->where('id_product', $row->id)
                ->orderBy('date_first', 'desc')
                ->first();
            if ($hargaSementara) {
                $hargaJual = (float) $hargaSementara->harga_sementara;
            }
            // dd($hargaSementara);
            return number_format($hargaJual);
        })
        ->rawColumns(['nama']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1, 'asc')
            ->language(['processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'])
            ->parameters([
                "lengthMenu" => [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                'pageLength' => 100
            ])
            ->buttons([''])
            ->addTableClass('table align-middle table-rounded table-striped table-row-gray-300 fs-6 gy-5');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('kode_alternatif')->title('KODE')->addClass('text-center text-black'),
            Column::make('nama')->title('NAMA BARANG')->addClass('text-black'),
            // Column::make('harga_pokok')->title('HARGA BELI')->addClass('text-end text-black'),
            Column::make('harga_jual')->title('HARGA')->addClass('text-end text-black'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
