<?php

namespace App\DataTables\Client;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TicketDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($ticket) {
                return view('website.clientticket.action', compact('ticket'));
            })->addColumn('status', function ($ticket) {
                return view('website.clientticket.status', compact('ticket'));
            })->addColumn('created_at', function ($ticket) {
                return Carbon::parse($ticket->created_at)->format('M d, Y h:i A');
            })->addColumn('department', function ($ticket) {
                return @$ticket->department->title;
            })->addColumn('ticket_id', function ($ticket) {
                return $ticket->ticket_id;
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        $model = Ticket::where('client_staff', auth()->user()->id);

        return $model->when(request('order')[0]['dir'] ?? false, function ($query, $orderBy) {
            $query->orderBy('id', $orderBy);
        })
            ->when($this->request->search['value'] ?? false, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('ticket_id', 'like', "%$search%");
                });
            })
            ->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('subscriber_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false),
            Column::make('ticket_id')->title(__('ticket_id')),
            Column::make('subject')->title(__('subject')),
            Column::computed('department')->title(__('department')),
            Column::computed('priority')->title(__('priority'))->addClass('text-capitalize'),
            Column::computed('created_at')->title(__('created')),
            Column::computed('status')->title(__('status'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false),
            Column::computed('action')->addClass('action-card')->title(__('Option'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false),

        ];
    }

    protected function filename(): string
    {
        return 'subscriber_'.date('YmdHis');
    }
}
