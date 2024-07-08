<?php

namespace App\DataTables;

use App\Models\WebsiteUniqueFeature;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WebsiteUniqueFeatureDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($feature) {
                return view('backend.admin.website.unique_feature.column.action', compact('feature'));
            })->addColumn('title', function ($feature) {
                return @$feature->language->title;
            })->addColumn('icon', function ($feature) {
                return view('backend.admin.website.unique_feature.column.icon', compact('feature'));
            })->addColumn('status', function ($feature) {
                return view('backend.admin.website.unique_feature.column.status', compact('feature'));
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        $model = WebsiteUniqueFeature::with('language');
        return $model->latest()->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->setTableAttribute('style', 'width:99.8%')
            ->footerCallback('function ( row, data, start, end, display ) {

                $(".dataTables_length select").addClass("form-select form-select-lg without_search mb-3");
                selectionFields();
            }')
            ->parameters([
                'dom'        => 'Blfrtip',
                'buttons'    => [
                    [],
                ],
                'lengthMenu' => [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
                'language'   => [
                    'searchPlaceholder' => __('search'),
                    'lengthMenu'        => '_MENU_ '.__('unique_feature_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('title')->title(__('title')),
            Column::make('icon')->title(__('icon')),
            Column::computed('status')->title(__('status'))->exportable(false)
                ->printable(false),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),

        ];
    }

    protected function filename(): string
    {
        return 'client_'.date('YmdHis');
    }
}
