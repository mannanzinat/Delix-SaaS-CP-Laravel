<?php

namespace App\DataTables\Merchant;

use App\Models\Parcel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Yajra\DataTables\Services\DataTable;

class ParcelsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('options', function ($parcel) {
            return view('merchant.parcel.column.action', compact('parcel'));
        })->addColumn('no', function ($parcel) {
            return view('merchant.parcel.column.no', compact('parcel'));
        })->addColumn('merchant', function ($parcel) {
            return view('merchant.parcel.column.merchant', compact('parcel'));
        })->addColumn('customer', function ($parcel) {
            return view('merchant.parcel.column.customer', compact('parcel'));
        })->addColumn('status', function ($parcel) {
            return view('merchant.parcel.column.status', compact('parcel'));
        })->setRowId('id');
    }

    public function query(Parcel $model, $pn = ''): QueryBuilder
    {

        $query = $model->query();

        if (Sentinel::getUser()->user_type == 'merchant_staff') {
            $query->where('merchant_id', Sentinel::getUser()->merchant_id)
                ->when(!hasPermission('all_parcel'), function ($query) {
                    return $query->whereHas('shop', function ($q) {
                        $q->whereIn('id', \Sentinel::getUser()->shops);
                    });
                });
        }


        if (Sentinel::getUser()->user_type == 'merchant') {
            $query->where('merchant_id', Sentinel::getUser()->merchant->id)
                ->where('parcel_no', 'like', '%' . $pn . '%');
        }

        $query->when($this->request->customer_name ?? false, function ($query, $customer_name) {
            $query->where('customer_name', 'like', "%$customer_name%");
        })
        ->when($this->request->customer_invoice_no ?? false, function ($query, $customer_invoice_no) {
            $query->where('customer_invoice_no', $customer_invoice_no);
        })
        ->when($this->request->phone_number ?? false, function ($query, $phone_number) {
            $query->where('customer_phone_number', $phone_number);
        })
        ->when($this->request->created_from ?? false, function ($query, $created_from) {
            $created_from = \Carbon\Carbon::parse($created_from)->format('Y-m-d');
            $query->whereDate('created_at', '>=', $created_from);
            if ($this->request->created_to != "") {
                $created_to = \Carbon\Carbon::parse($this->request->created_to)->format('Y-m-d');
                $query->whereDate('created_at', '<=', $created_to);
            }
        })
        ->when($this->request->pickup_date ?? false, function ($query, $pickup_date) {
            $formattedDate = \Carbon\Carbon::parse($pickup_date)->format('Y-m-d');
            $query->whereDate('pickup_date', $formattedDate);
        })
        ->when($this->request->delivery_date ?? false, function ($query, $delivery_date) {
            $formattedDate = \Carbon\Carbon::parse($delivery_date)->format('Y-m-d');
            $query->whereDate('delivery_date', $formattedDate);
        })
        ->when($this->request->delivery_date ?? false, function ($query, $delivery_date) {
            $formattedDate = \Carbon\Carbon::parse($delivery_date)->format('Y-m-d');
            $query->whereDate('delivery_date', $formattedDate);
        })
        ->when($this->request->pickup_date ?? false, function ($query, $pickup_date) {
            $formattedDate = \Carbon\Carbon::parse($pickup_date)->format('Y-m-d');
            $query->whereDate('pickup_date', $formattedDate);
        })
        ->when($this->request->delivery_date ?? false, function ($query, $delivery_date) {
            $query->where('delivery_date', $delivery_date);
        })
        ->when($this->request->delivered_date ?? false, function ($query, $delivered_date) {
            $query->where('delivered_date', $delivered_date);
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
                ->orWhere('customer_phone_number', 'like', "%$search%");
                // ->orWhere('customer_address', 'like', "%$search%")
                // ->orWhere('parcel_no', 'like', "%$search%");
        })

        ->latest();

        return $query->newQuery();
    }

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


    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('options')->title(__('options'))->addClass('text-center'),
            Column::computed('no')->title(__('no')),
            Column::computed('merchant')->title(__('merchant')),
            Column::computed('customer')->title(__('customer')),
            Column::computed('status')->title(__('status'))->exportable(false)
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),

        ];
    }

    protected function filename(): string
    {
        return 'parcel'.date('YmdHis');
    }
}
