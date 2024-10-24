<?php

namespace App\DataTables;

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
        return (new EloquentDataTable($query->whereNotNull('kode_alternatif')->where('kode_alternatif', '!=', '')->orderBy('created_at', 'desc')))
        ->addIndexColumn()
        ->editColumn('created_at', function ($row) {
            return $row->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s');
        })
        ->editColumn('nama', function ($row) {
            return '<a href="#" 
            data-kode="' . $row->kode . '" 
            data-kode-alternatif="' . $row->kode_alternatif . '" 
            data-nama="' . $row->nama . '" 
            data-unit-jual="' . $row->unit_jual . '" 
            data-harga-jual="' . $row->harga_jual . '" 
            class="product-link">' . $row->nama . '/' . $row->unit_jual . '</a>';
        })
        ->editColumn('harga_jual', function ($row) {
            return number_format($row->harga_jual);
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
