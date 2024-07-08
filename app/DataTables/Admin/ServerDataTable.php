<?php
namespace App\DataTables\Admin;
use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('status', function ($server) {
                return view('backend.admin.cloud_server.status', compact('server'));
            })->addColumn('provider', function ($server) {
                return $server->provider;
            })->addColumn('ip', function ($server) {
                return $server->ip;
            })->addColumn('user_name', function ($server) {
                return $server->user_name;
            })->addColumn('action', function ($server) {
                return view('backend.admin.cloud_server.action', compact('server'));
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {

        $model = new Server();

        return $model->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('server_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('provider')->title(__('provider')),
            Column::computed('ip')->title(__('ip')),
            Column::computed('user_name')->title(__('user_name')),
            Column::computed('status')->title(__('status'))->searchable(false)->exportable(false)->printable(false),
            Column::computed('action')->addClass('action-card')->addClass('text-end')->title(__('action'))->searchable(false)->exportable(false)->printable(false),

        ];
    }

    protected function filename(): string
    {
        return 'server'.date('YmdHis');
    }
}
