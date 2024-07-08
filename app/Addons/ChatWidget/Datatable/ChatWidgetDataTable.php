<?php 
namespace App\Addons\ChatWidget\Datatable;
use App\Models\ChatWidget;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ChatWidgetDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return $query->name;
            })
            ->addColumn('unique_id', function ($query) {
                return $query->unique_id;
            })
            ->addColumn('box_position', function ($query) {
                return @$query->box_position;
            })
            ->addColumn('total_hit', function ($query) {
                return @$query->total_hit;
            })
            ->addColumn('status', function ($query) {
                return view('addon:ChatWidget::partials.status', compact('query'));
            })
            ->addColumn('action', function ($query) {
                return view('addon:ChatWidget::partials.action', compact('query'));
            })
            ->setRowId('id');
    }



    public function query(): QueryBuilder
    {
        return ChatWidget::with('client')
            ->when($this->request->search['value'] ?? false, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('unique_id', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
                });
            })
            ->withPermission()            
            ->latest('id')->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('chatwidget_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::make('name')->title(__('name')),
            Column::make('unique_id')->title(__('unique_id')),
            Column::make('box_position')->title(__('box_position')),
            Column::make('total_hit')->title(__('total_hit')),
            Column::computed('status')->title(__('status'))->width(10),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'chatwidget_'.date('YmdHis');
    }
}
