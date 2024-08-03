<?php

namespace App\DataTables\Client;

use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\GroupSubscriber;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
class TelegramContactsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('name', function ($contacts) {
                return view('website.clienttelegram.contacts.name', compact('contacts'));
            })
            ->addColumn('username', function ($row) {
                return @$row->username;
            })
            ->addColumn('group', function ($row) {
                return @$row->group->name;
            })
            ->addColumn('is_block', function ($row) {
                return $row->is_blacklist == 1 ? 'Yes' : 'No';
            })
            ->addColumn('is_left', function ($row) {
                return $row->is_left_group == 1 ? 'Yes' : 'No';
            })
            ->addColumn('checkbox', function ($contacts) {
                return view('website.clienttelegram.contacts.checkbox', compact('contacts'));
            })
            ->rawColumns(['created_at'])
        ;
    }

    public function query(GroupSubscriber $model)
    {
        $query = $model->withPermission()->where('type', TypeEnum::TELEGRAM)->latest();
            $query->when($this->request->name, function ($query, $name) {
                $query->where('name', $name);
            })
            ->when($this->request->group_id, function ($query, $group_id) {
                $query->where('group_id', $group_id);
            })
            ->when(request('search')['value'] ?? false, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
            });

        return $query;
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
                    'lengthMenu'        => '_MENU_ ' . __('contacts_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('name')),
            Column::computed('username')->title(__('username')),
            Column::computed('group')->title(__('group')),
            Column::computed('is_block')->title(__('is_block')),
            Column::computed('is_left')->title(__('is_left'))->addClass('text-start'),
            
        ];
    }

    protected function filename(): string
    {
        return 'telegram_contact_' . date('YmdHis');
    }
}
