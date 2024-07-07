<?php
namespace App\DataTables\Admin;
use Carbon\Carbon;
use App\Models\Parcel;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ParcelDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()

            ->addColumn('action', function ($parcel) {
                return view('admin.parcel.column.action', compact('parcel'));
            })
            ->addColumn('no_date', function ($parcel) {
                return view('admin.parcel.column.order_info', compact('parcel'));
            })

            ->addColumn('merchant_info', function ($parcel) {
                return view('admin.parcel.column.merchant_info', compact('parcel'));
            })

            ->addColumn('customer_info', function ($parcel) {
                return view('admin.parcel.column.customer_info', compact('parcel'));
            })
            ->addColumn('status', function ($parcel) {
                return view('admin.parcel.column.status', compact('parcel'));
            });
    }

    public function getTotalCount(): int
    {
        return Parcel::count();
    }


    public function query(Parcel $model): QueryBuilder
    {
        $query = $model->with([
            'merchant', 'shop',
            'events',
        ])
        ->withPermission()
        ->when($this->request->order[0]['dir'] ?? false, function ($query, $orderBy) {
            $query->orderBy('created_at', $orderBy);
        })
        ->when($this->request->merchant_id ?? false, function ($query, $merchant_id) {
            $query->where('merchant_id', $merchant_id);
        })
        ->when($this->request->customer_invoice_no ?? false, function ($query, $customer_invoice_no) {
            $query->where('customer_invoice_no', $customer_invoice_no);
        })
        ->when($this->request->pickup_man_id ?? false, function ($query, $pickup_man_id) {
            $query->where('pickup_man_id', $pickup_man_id);
        })
        ->when($this->request->delivery_man_id ?? false, function ($query, $delivery_man_id) {
            $query->where('delivery_man_id', $delivery_man_id);
        })
        ->when($this->request->status ?? false, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($this->request->weight ?? false, function ($query, $weight) {
            $query->where('weight', $weight);
        })
        ->when($this->request->parcel_type ?? false, function ($query, $parcel_type) {
            $query->where('parcel_type', $parcel_type);
        })
        ->when($this->request->location ?? false, function ($query, $location) {
            $query->where('location', $location);
        })
        ->when($this->request->branch_id ?? false, function ($query, $branch_id) {
            $query->where('branch_id', $branch_id);
        })
        ->when($this->request->pickup_branch_id ?? false, function ($query, $pickup_branch_id) {
            $query->where('pickup_branch_id', $pickup_branch_id);
        })

        ->when($this->request->third_party_id ?? false, function ($query, $third_party_id) {
            $query->where('third_party_id', $third_party_id);
        })

        ->when($this->request->pickup_date ?? false, function ($query, $pickup_date) {
            $formattedDate = \Carbon\Carbon::parse($pickup_date)->format('Y-m-d');
            $query->whereDate('pickup_date', $formattedDate);
        })

        ->when($this->request->delivery_date ?? false, function ($query, $delivery_date) {
            $formattedDate = \Carbon\Carbon::parse($delivery_date)->format('Y-m-d');
            $query->whereDate('delivery_date', $formattedDate);
        })

        ->when($this->request->delivered_date ?? false, function ($query, $delivered_date) {
            $formattedDate = \Carbon\Carbon::parse($delivered_date)->format('Y-m-d');
            $query->whereDate('delivered_date', $formattedDate);
        })

        ->when($this->request->phone_number ?? false, function ($query, $phone_number) {
            $query->where('customer_phone_number','LIKE', '%'.$phone_number);
        })
        ->when($this->request->created_at ?? false, function ($query, $created_at) {
            $dateRange = $this->parseDate($created_at);
                $query->whereBetween('created_at', $dateRange);
        })
        ->when($this->request->search['value'] ?? false, function ($query, $search) {
            $query->where('customer_name', 'like', "%$search%")
                ->orWhere('customer_phone_number', 'like', "%$search%")
                ->orWhere('parcel_no', 'like', "%$search%");
        })->latest()
        ->newQuery();

        return $query;
    }




    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->setTableAttribute('style', 'width:99.8%')
            ->footerCallback('function (row, data, start, end, display ) {
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
                    'lengthMenu'        => '_MENU_ '.__('parcel_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    // order_info,merchant_info,customer_info,status,action
    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('action')
            ->title(__('action'))
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ,
            Column::computed('no_date')->title(__('no_date')),
            Column::computed('merchant_info')->title(__('merchant_info')),
            Column::computed('customer_info')->title(__('customer_info')),
            Column::computed('status')->addClass('text-center')->title(__('status')),


        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Parcel_' . date('YmdHis');
    }

    private function parseDate($date_range)
    {
        $dates      = explode('to', $date_range);

        if (count($dates) == 1) {
            $dates[1] = $dates[0];
        }

        $start_date = trim($dates[0]);
        $end_date   = trim($dates[1]);

        $start_date = $start_date . ' 00:00:00';
        $end_date   = $end_date . ' 23:59:59';

        return [
            Carbon::parse($start_date)->format('Y-m-d H:s:i'),
            Carbon::parse($end_date)->format('Y-m-d H:s:i'),
        ];
    }
}
