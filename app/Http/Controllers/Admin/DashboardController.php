<?php
namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use App\Models\Notice;
use App\Models\Parcel;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Account\FundTransfer;
use App\Models\Notification;
use App\Models\ParcelEvent;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Account\GovtVat;
use App\Traits\ShortenLinkTrait;
use App\Traits\RandomStringTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Account\CompanyAccount;
use App\Models\StaffAccount;
use App\Models\Account\MerchantAccount;
use Illuminate\Support\Facades\Artisan;
use App\Models\Account\MerchantWithdraw;
use App\Models\Account\DeliveryManAccount;
use App\Services\ProfitService;
use App\Models\DeliveryMan;
use  App\Services\CodService;
use App\Models\Branch;
use  App\Services\MerchantService;
use  App\Services\IncomeService;
use  App\Services\ExpenseService;
use  App\Services\EarningService;
use  App\Services\CashFromMerchantService;
use  App\Services\VasService;
use  App\Services\ChargeService;
use  App\Services\ParcelService;
use  App\Services\NewParcelService;
use  App\Services\ProcessingParcelService;
use  App\Services\DeliveredParcelService;
use  App\Services\DeliverymanService;
use  App\Services\BranchService;
use App\Repositories\NotificationRepository;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class DashboardController extends Controller
{
    use RandomStringTrait, ShortenLinkTrait;

    protected $merchants;

    public function __construct(MerchantInterface $merchants)
    {
        $this->merchants     = $merchants;
    }

    public function index(NotificationRepository $notification, Request $request)
    {

        $today                              = date('Y-m-d');
        $yesterday                          = date('Y-m-d', strtotime('-1 day'));
        $last7days                          = date('Y-m-d', strtotime('-7 days'));
        $last14days                         = date('Y-m-d', strtotime('-14 days'));
        $lastMonthFirstDay                  = date('Y-m-01', strtotime('last month'));
        $lastMonthLastDay                   = date('Y-m-t', strtotime('last month'));
        $last6MonthsFirstDay                = date('Y-m-01', strtotime('-6 months'));
        $last6MonthsLastDay                 = date('Y-m-t', strtotime('-6 months'));
        $last12MonthsFirstDay               = date('Y-m-01', strtotime('-12 months'));
        $last12MonthsLastDay                = date('Y-m-t', strtotime('-12 months'));
        $start_date_one_month_ago           = date('Y-m-d', strtotime('-1 month'));
        $start_date_one_year_ago            = date('Y-m-d', strtotime('-1 year'));
        $now                                = Carbon::now();
        $start_date                         = '2000-04-01';
        $end_date                           = date('Y-m-d');
        $filter                             = $request->input('filter');
        $custom_start_date                  = $request['startDate'];
        $custom_end_date                    = $request->input('endDate');

        switch ($filter) {
            case 'yesterday':
                $filter_start_date                          = $yesterday;
                $filter_end_date                            = $today;
                break;
            case 'last_7_day':
                $filter_start_date                          = $last7days;
                $filter_end_date                            = $today;
                break;
            case 'last_14_day':
                $filter_start_date                          = $last14days;
                $filter_end_date                            = $today;
                break;
            case 'last_month':
                $filter_start_date                          = $lastMonthFirstDay;
                $filter_end_date                            = $lastMonthLastDay;
                break;
            case 'last_6_month':
                $filter_start_date                          = $last6MonthsFirstDay;
                $filter_end_date                            = $last6MonthsLastDay;
                break;
            case 'this_year':
                $filter_start_date                          = $start_date_one_year_ago;
                $filter_end_date                            = $today;
                break;
            case 'last_12_month':
                $filter_start_date                          = $last12MonthsFirstDay;
                $filter_end_date                            = $last12MonthsLastDay;
                break;
            case 'custom':
                $filter_start_date                          = $custom_start_date;
                $filter_end_date                            = $custom_end_date;
                break;
            default:
                $filter_start_date                          = $today;
                $filter_end_date                            = $today;
                break;
        }


        //parcel report admin and finance
        $new_parcel                                         = $this->newParcel($filter_start_date, $filter_end_date);
        $new_parcel_total                                   = $new_parcel->withPermission()->get();
        $new_parcel_cod                                     = $new_parcel_total->sum('price');
        $processing_parcel                                  = $this->processedParcel($filter_start_date, $filter_end_date);
        $processing_parcel_total                            = $processing_parcel->withPermission()->get();
        $processing_parcel_cod                              = $processing_parcel_total->sum('price');
        $delivered_parcel                                   = $this->deliveredParcel($filter_start_date, $filter_end_date);
        $delivered_parcel_total                             = $delivered_parcel->withPermission()->get();
        $delivered_parcel_cod                               = $delivered_parcel_total->sum('price');
        $profit                                             = $this->profits($start_date, $end_date);
        $monthly_profit                                     = $this->profits($start_date_one_month_ago, $end_date);

        //branch manager
        $branch_wise_new_parcel                             = $this->newParcel($filter_start_date, $filter_end_date)->withPermission();
        $branch_wise_new_parcel_total                       = $branch_wise_new_parcel->get();
        $branch_wise_new_parcel_cod                         = $branch_wise_new_parcel_total->sum('price');
        $branch_wise_processing_parcel                      = $this->processedParcel($filter_start_date, $filter_end_date)->withPermission();
        $branch_wise_processing_parcel_total                = $branch_wise_processing_parcel->get();
        $branch_wise_processing_parcel_cod                  = $branch_wise_processing_parcel_total->sum('price');
        $branch_wise_delivered_parcel                       = $this->deliveredParcel($filter_start_date, $filter_end_date)->withPermission();
        $branch_wise_delivered_parcel_total                 = $branch_wise_delivered_parcel->get();
        $branch_wise_delivered_parcel_cod                   = $branch_wise_delivered_parcel_total->sum('price');
        $branch_wise_parcel_added                           = Parcel::withPermission();
        $branch_wise_parcel_delivered                       = $this->deliveredParcel($start_date, $end_date)->withPermission();


        //life time earning
        $life_time_income                                   = $this->income($start_date, $end_date);
        $life_time_total_income                             = $life_time_income->sum('amount');
        $life_time_expense                                  = $this->expense($start_date, $end_date);
        $life_time_total_expense                            = $life_time_expense->sum('amount');
        $life_time_total_profit                             = $life_time_total_income - $life_time_total_expense;


        //earning report for admin
        $income                                             = $this->income($filter_start_date, $filter_end_date);
        $total_income                                       = $income->sum('amount');
        $expense                                            = $this->expense($filter_start_date, $filter_end_date);
        $total_expense                                      = $expense->sum('amount');
        $profit                                             = $total_income - $total_expense;

        //income report for finance
        $cashFromMerchant                                   = $this->cashFromMerchant($filter_start_date, $filter_end_date);

        //merchant
        $total_merchant                                     = $this->totalMerchant($start_date, $end_date);
        $monthly_total_merchant                             = $this->totalMerchant($start_date_one_month_ago, $end_date);
        $yearly_total_merchant                              = $this->totalMerchant($start_date_one_year_ago, $end_date);
        $yearly_total_parcel                                = Parcel::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date);
        //parcel
        $total_parcel                                       = $this->totalParcel($start_date, $end_date);
        $monthly_total_parcel                               = $this->totalParcel($start_date_one_month_ago, $end_date);
        $total_parcel_count                                 = $total_parcel->count();
        $monthly_total_parcel_count                         = $monthly_total_parcel->count();
        $yearly_total_parcel_count                          = $yearly_total_parcel->count();
        //cod
        $total_cod                                          = $total_parcel->sum('price');
        $monthly_total_cod                                  = $monthly_total_parcel->sum('price');
        $yearly_total_cod                                   = $yearly_total_parcel->sum('price');
        $branchId                                           = \Sentinel::getUser()->branch_id;

        $cash_collect                                       = DeliveryManAccount::select('*')
                                                                ->leftJoin('delivery_men as dm','dm.id','=','delivery_man_accounts.delivery_man_id')
                                                                ->leftJoin('users','users.id','=','dm.user_id')
                                                                ->where('users.branch_id','=',\Sentinel::getUser()->branch_id)
                                                                ->where('source', 'cash_collection')
                                                                    ->whereNotIn('parcel_id', function ($query) {
                                                                        $query->select('parcel_id')
                                                                            ->from('delivery_man_accounts')
                                                                            ->where('source', 'cash_given_to_staff');
                                                                    })
                                                                    ->groupBy('parcel_id')
                                                                    ->get()->sum('amount');

        $cash_collection                                        = $cash_collect;


        $users                                                  = User::where('branch_id', \Sentinel::getUser()->branch_id)
                                                                ->where('user_type', 'staff')
                                                                ->pluck('id');

        $branch_account                                         = Account::whereIn('user_id', $users)->get();

        $branch_balance                                         = $branch_account->sum(function ($account) {
                                                                    return $account->incomes()->sum('amount')
                                                                        + $account->fundReceives()->sum('amount')
                                                                        - $account->expenses()->sum('amount')
                                                                        - $account->fundTransfers()->sum('amount');
                                                                });

        $current_time                                           = Carbon::now()->format('Y-m-d H:i:s');
        $notices                                                = Notice::where('status', true)->where('staff', true)->where('start_time', '<=', $current_time)->where('end_time', '>=', $current_time)->get();



        $data             = [
            'charts'                   => [
                'total_cod'                                 => app(CodService::class)->totalCod(),
                'merchant'                                  => app(MerchantService::class)->totalMerchant(),
                'parcel'                                    => app(ParcelService::class)->totalParcel($yearly_total_parcel),
                'life_time_profit'                          => app(ProfitService::class)->totalProfit($start_date, $end_date),
                'life_time_income'                          => app(IncomeService::class)->totalIncome($life_time_income),
                'life_time_expense'                         => app(ExpenseService::class)->totalExpense($life_time_expense),
                'delivery_man_list'                         => app(DeliverymanService::class)->totalDeliveryman(),
                'branch_list'                               => app(BranchService::class)->totalBranch(),

                //parcel report
                'new_parcel'                                => app(NewParcelService::class)->totalParcel($new_parcel),
                'processing_parcel'                         => app(ProcessingParcelService::class)->totalParcel($processing_parcel),
                'delivered_parcel'                          => app(DeliveredParcelService::class)->totalParcel($delivered_parcel),

                //earning report
                'profit'                                   => app(ProfitService::class)->totalProfit($filter_start_date, $filter_end_date),
                'income'                                   => app(IncomeService::class)->totalIncome($income),
                'expense'                                  => app(ExpenseService::class)->totalExpense($expense),

                //branch manager dashboard
                'branch_wise_new_parcel'                                => app(NewParcelService::class)->totalParcel($branch_wise_new_parcel),
                'branch_wise_processing_parcel'                         => app(ProcessingParcelService::class)->totalParcel($branch_wise_processing_parcel),
                'branch_wise_delivered_parcel'                          => app(DeliveredParcelService::class)->totalParcel($branch_wise_delivered_parcel),

                'branch_wise_added_parcel'                              => app(NewParcelService::class)->totalParcel($branch_wise_parcel_added),
                'branch_wise_delivery_parcel'                           => app(DeliveredParcelService::class)->totalParcel($branch_wise_parcel_delivered),

                //Finance Income Report
                'cashFromMerchant'                                  => app(CashFromMerchantService::class)->totalCash($cashFromMerchant),
                'vas'                                               => app(VasService::class)->totalVas($delivered_parcel),
                'charge'                                            => app(ChargeService::class)->totalCharge($delivered_parcel),


            ],

            'life_time_total_cod'                           => $total_cod,
            'monthly_total_cod'                             => $monthly_total_cod,
            'life_time_total_profit'                        => $this->income($start_date, $end_date)->sum('amount') - $this->expense($start_date, $end_date)->sum('amount'),
            'monthly_profit'                                => $this->income($start_date_one_month_ago, $end_date)->sum('amount') - $this->expense($start_date_one_month_ago, $end_date)->sum('amount'),
            'life_time_total_merchant'                      => $total_merchant->get()->count(),
            'monthly_total_merchant'                        => $monthly_total_merchant->count(),
            'life_time_total_parcel_count'                  => $total_parcel_count,
            'monthly_total_parcel_count'                    => $monthly_total_parcel_count,
            'life_time_total_delivery_man'                  => $this->totalDeliveryMan()->get()->count(),
            'life_time_total_branch'                        => $this->totalBranch()->count(),

            //parcel report
            'new_parcel'                                    => $new_parcel_total->count(),
            'new_parcel_cod'                                => $new_parcel_cod,
            'processing_parcel'                             => $processing_parcel_total->count(),
            'delivered_parcel_cod'                          => $delivered_parcel_cod,
            'delivered_parcel'                              => $delivered_parcel_total->count(),
            'processing_parcel_cod'                         => $processing_parcel_cod,

            //earning report
            'life_time_income'                                 => $this->income($start_date, $end_date)->sum('amount'),
            'life_time_expense'                                => $this->expense($start_date, $end_date)->sum('amount'),
            'total_income'                                     => $total_income,
            'total_expense'                                    => $total_expense,
            'total_profit'                                     => $profit,



            //parcel overview
            'life_time_delivered_parcel'                    => $this->deliveredParcel($start_date, $end_date)->count(),
            'life_time_partially_delivered_parcel'          => $this->partiallyDeliveredParcel($start_date, $end_date)->count(),
            'life_time_new_parcel'                          => $this->newParcel($start_date, $end_date)->count(),
            'life_time_processing_parcel'                   => $this->processedParcel($start_date, $end_date)->count(),
            'life_time_return_parcel'                       => $this->returnParcel($start_date, $end_date)->count(),
            'life_time_cancel_parcel'                       => $this->cancelParcel($start_date, $end_date)->count(),
            'life_time_deleted_parcel'                      => $this->deletedParcel($start_date, $end_date)->count(),

            //branch manager
            'branch_wise_total_new_parcel'                  => $branch_wise_new_parcel_total->count(),
            'branch_wise_total_processing_parcel'           => $branch_wise_processing_parcel_total->count(),
            'branch_wise_total_delivered_parcel'            => $branch_wise_delivered_parcel_total->count(),
            'branch_wise_total_new_parcel_cod'              => $branch_wise_new_parcel_cod,
            'branch_wise_total_processing_parcel_cod'       => $branch_wise_processing_parcel_cod,
            'branch_wise_total_delivered_parcel_cod'        => $branch_wise_delivered_parcel_cod,
            'branch_wise_total_merchant'                    => Merchant::whereHas('shops', function($query) {
                                                                    $query->where('pickup_branch_id', \Sentinel::getUser()->branch_id);
                                                                })
                                                                ->count(),
            'total_delivery_man'                            => $this->totalDeliveryMan()
                                                                ->whereHas('user', function($query) {
                                                                    $query->where('branch_id', \Sentinel::getUser()->branch_id);
                                                                })
                                                                ->count(),
            'pending_pickup'                                => $this->newParcel($start_date, $end_date)->withPermission()->count(),
            'cash_in'                                       => $cash_collection,
            'branch_balance'                                => $branch_balance,
        ];


        //finance
        $data['deliveryman_balance']            = $this->deliverymanBalance();
        $data['staff_balance']                  = $this->staffBalance();
        $data['total_charge']                   = $this->deliveredParcel($start_date, $end_date)->get()->sum('total_delivery_charge');
        $data['pending_payout']                 = $this->totalPendingPayout();
        $data['merchant_balance']               = $this->merchantBalance();
        $data['finance_self_wallet']            = Account::where('user_id', \Sentinel::getUser()->id)->get()->sum('balance');
        $data['fund_transfers']                 = FundTransfer::with('fromAccount')->get()->take(5);
        $data['cash_collections']               = CompanyAccount::where('type', 'income')->where('source', 'cash_receive_from_delivery_man')
                                                    ->where('create_type', 'user_defined')->get()->take(5);


        $data['lifetime_profit']                = $this->profits($start_date, $today);

        $data['latest_merchants']               = Merchant::with('user.image', 'parcels', 'defaultAccount.paymentAccount')
                                                    ->when(!hasPermission('read_all_merchant'), function ($query) {
                                                    $query->whereHas('user', function ($q) {
                                                        $q->where('branch_id', \Sentinel::getUser()->branch_id)
                                                            ->orWhereNull('branch_id');
                                                    });
                                                })->latest()->take(3)->get();


        $data['top_rank_merchants']             = Merchant::with('user.image', 'parcels', 'defaultAccount.paymentAccount')
                                                    ->when(!hasPermission('read_all_merchant'), function ($query) {
                                                        $query->whereHas('user', function ($q) {
                                                            $q->where('branch_id', \Sentinel::getUser()->branch_id)
                                                                ->orWhereNull('branch_id');
                                                        });
                                                    })
                                                    ->withCount(['parcels' => function ($query) {
                                                        $query->where(function ($query) {
                                                            $query->whereIn('status', ['delivered', 'delivered-and-verified'])
                                                                ->orWhere('is_partially_delivered', true);
                                                        });
                                                    }])
                                                    ->orderByDesc('parcels_count')
                                                    ->orderByDesc('id')
                                                    ->latest()
                                                    ->take(3)
                                                    ->get();

        $data['notices']                         = $notices;

        $data['latest_parcels']                  = Parcel::withPermission()->latest()->take(5)->get();

        $data['latest_delivery_parcels']         = Parcel::where('status', 'delivered')->withPermission()->latest()->take(5)->get();


        if(@Sentinel::getUser()->dashboard == 'admin') {
            if($request->ajax()){
                return response()->json(['data' => $data]);
            }else{
                return view('admin.dashboard', $data);

            }
        }elseif(@Sentinel::getUser()->dashboard == 'finance') {
            if($request->ajax()){
                return response()->json(['data' => $data]);
            }else{
                return view('admin.finance-dashboard', $data);

            }
        }else{
            if($request->ajax()){
                return response()->json(['data' => $data]);
            }else{
                return view('admin.branch-manager-dashboard', $data);

            }
        }

    }


    public function totalMerchant($start_date, $end_date)
    {
        $data = Merchant::where('status', 'active')->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);

        return $data;
    }

    public function totalDeliveryMan()
    {
        $data = DeliveryMan::where('status', 'active');


        return $data;
    }
    public function totalBranch()
    {
        $data = Branch::where('status', 'active')->get();

        return $data;
    }

    public function totalParcel($start_date, $end_date)
    {
        $data = Parcel::whereIn('status', ['partially-delivered', 'delivered'])->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->withPermission()->get();

        return $data;
    }

    public function processedParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->whereNotIn('status', ['pending', 'delivered', 'returned-to-merchant', 'partially-delivered', 'deleted']);
        return $data;
    }

    public function newParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 'pending');

        return $data;
    }

    public function deliveredParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 'delivered');

        return $data;
    }

    public function partiallyDeliveredParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 'partially-delivered');

        return $data;
    }

    public function returnParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 'returned-to-merchant');

        return $data;
    }

    public function cancelParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 'cancel');

        return $data;
    }

    public function deletedParcel($start_date, $end_date)
    {
        $data = Parcel::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 'deleted');

        return $data;
    }

    public function income($start_date, $end_date)
    {
        $data = CompanyAccount::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->whereIn('source', ['delivery_charge_receive_from_merchant', 'cash_receive_from_delivery_man'])
                ->where('type', 'income')
                ->where('create_type', 'user_defined');

        return $data;

    }

    public function expense($start_date, $end_date)
    {
        $data = CompanyAccount::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('type', 'expense')
                ->where('create_type', 'user_defined');
        return $data;

    }


    public function cashFromMerchant($start_date, $end_date)
    {
        $data = StaffAccount::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->where('details', 'delivery_charge_receive_from_merchant');
        return $data;
    }

    public function get_counts($parcels)
    {
        $delivered_cod              = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->sum('price');
        $data['total_cod']          = format_price($parcels->where('is_partially_delivered', true)->sum('price') + $delivered_cod);
        $data['parcels_count']      = $parcels->count();
        $data['processing_count']   = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        $data['cancelled_count']    = $parcels->where('status', 'cancel')->count();
        $data['deleted_count']      = $parcels->where('status', 'deleted')->count();
        $data['partial_delivered_count'] = $parcels->where('is_partially_delivered', true)->count();
        $data['returned_count']     = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();

        return $data;
    }

    public function report(Request $request)
    {
        $report_type = $request->report_type;

        if ($report_type == 'today') :
            $today          = date('Y-m-d');
            $parcels    = Parcel::where('created_at', '>=', $today . ' 00:00:00')
                ->where('created_at', '<=', $today . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::whereDate('created_at', $today . ' 00:00:00')
                ->whereDate('created_at', '<=', $today . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();


            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for ($i = 0; $i <= 11; $i++) {

                $j = $i * 2;

                $j = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start = date('Y-m-d ') . $j . ':00:00';
                $end = date('Y-m-d ') . $in . ':59:59';

                $merchant_parcels             = $parcels->where('created_at', '>=', $start);
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', $today)->sum('amount');

            $profits = $this->profits($today, $today);

        elseif ($report_type == 'yesterday') :
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $parcels = Parcel::where('created_at', '>=', $yesterday . ' 00:00:00')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->where('created_at', '<=', $yesterday . ' 23:59:59')
                ->latest()->get();

            $parcel_delivered = ParcelEvent::whereDate('created_at', $yesterday . ' 00:00:00')
                ->whereDate('created_at', '<=', $yesterday . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for ($i = 0; $i <= 11; $i++) {

                $j = $i * 2;

                $j = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start = $yesterday . ' ' . $j . ':00:00';
                $end =  $yesterday . ' ' . $in . ':59:59';

                $merchant_parcels             = $parcels->where('created_at', '>=', $start);
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', $yesterday)->sum('amount');

            $profits = $this->profits($yesterday, $yesterday);

        elseif ($report_type == 'this_week') :
            $now = Carbon::now();

            $start_day = date('Y-m-d', strtotime($now->startOfWeek(Carbon::SATURDAY)));
            $end_day = date('Y-m-d', strtotime($now->endOfWeek(Carbon::FRIDAY)));

            $parcels = Parcel::where('created_at', '>=', $start_day . ' 00:00:00')
                ->where('created_at', '<=', $end_day . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_day . ' 00:00:00')
                ->where('created_at', '<=', $end_day . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 0; $i <= 6; $i++) {

                $created_at = date('Y-m-d', strtotime($start_day . "+" . $i . ' days'));

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at . ' 00:00:00')->where('created_at', '<=', $created_at . ' 23:59:59');

                // dates
                $data['dates'][]              = date('d M, Y', strtotime($start_day . "+" . $i . ' days'));

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $start = date('Y-m-d', strtotime($start_day));
            $end = date('Y-m-d', strtotime($end_day));

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date', '>=', $start)
                ->where('date', '<=', $end)
                ->sum('amount');

            $profits = $this->profits($start, $end);

        elseif ($report_type == 'last_week') :
            $now = Carbon::now();

            $start_day = date('Y-m-d', strtotime($now->startOfWeek(Carbon::SATURDAY) . ('-1 week')));
            $end_day = date('Y-m-d', strtotime($now->endOfWeek(Carbon::FRIDAY) . ('-1 week')));

            $parcels = Parcel::where('created_at', '>=', $start_day . ' 00:00:00')
                ->where('created_at', '<=', $end_day . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_day . ' 00:00:00')
                ->where('created_at', '<=', $end_day . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 0; $i <= 6; $i++) {

                $created_at = date('Y-m-d', strtotime($start_day . "+" . $i . ' days'));

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at . ' 00:00:00')->where('created_at', '<=', $created_at . ' 23:59:59');

                // dates
                $data['dates'][]              = date('d M, Y', strtotime($start_day . "+" . $i . ' days'));

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $start = date('Y-m-d', strtotime($start_day));
            $end = date('Y-m-d', strtotime($end_day));

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date', '>=', $start)
                ->where('date', '<=', $end)
                ->sum('amount');

            $profits = $this->profits($start, $end);
        elseif ($report_type == 'this_month') :

            $start = date('Y-m-' . '01');
            $end = date('Y-m-t');

            $parcels = Parcel::where('created_at', '>=', $start . ' 00:00:00')
                ->where('created_at', '<=', $end . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();
            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start . ' 00:00:00')
                ->where('created_at', '<=', $end . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 1; $i <= date('t'); $i++) {
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                //date range parcels

                $created_at = date('Y-m-' . $i);

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at . ' 00:00:00')->where('created_at', '<=', $created_at . ' 23:59:59');

                // dates
                $data['dates'][]              = $i . ' ' . date('M');

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date', '>=', $start)
                ->where('date', '<=', $end)
                ->sum('amount');

            $profits = $this->profits($start, $end);
        elseif ($report_type == 'last_month') :

            $start  = date('Y-m-d', strtotime("first day of -1 month"));
            $end    = date('Y-m-d', strtotime("last day of -1 month"));

            $parcels = Parcel::where('created_at', '>=', $start . ' 00:00:00')
                ->where('created_at', '<=', $end . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start . ' 00:00:00')
                ->where('created_at', '<=', $end . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 1; $i <= date('t', strtotime('last day of -1 month')); $i++) {
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                //date range parcels

                $created_at = date('Y-m', strtotime('first day of -1 month')) . '-' . $i;

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at . ' 00:00:00')->where('created_at', '<=', $created_at . ' 23:59:59');

                // dates
                $data['dates'][]              = $i . ' ' . date('M', strtotime('first day of -1 month'));

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date', '>=', $start)
                ->where('date', '<=', $end)
                ->sum('amount');

            $profits = $this->profits($start, $end);

        elseif ($report_type == 'last_3_month') :
            $start_month    = date('Y-m', strtotime('-3 month'));
            $end_month      = date('Y-m', strtotime('first day of -1 month'));

            $parcels    = Parcel::where('created_at', '>=', $start_month . '-01' . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')) . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month . '-01' . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')) . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 3; $i >= 1; $i--) {

                $start = date('Y-m-d', strtotime('first day of -' . $i . ' month'));
                $end   = date('Y-m-d', strtotime('last day of -' . $i . ' month'));

                $merchant_parcels             = $parcels->where('created_at', '>=', $start . ' 00:00:00' . '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end . ' 23:59:59' . '%');

                // dates
                $data['dates'][]              = $start = date('Y-m', strtotime('first day of -' . $i . ' month'));

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', '>=', $start_month)->where('date', '<=', $end_month)->sum('amount');

            $start = date('Y-m-d', strtotime('first day of -3 month'));
            $end = date('Y-m-d', strtotime('last day of -1 month'));

            $profits = $this->profits($start, $end);

        elseif ($report_type == 'last_6_month') :
            $start_month    = date('Y-m', strtotime('-6 month'));
            $end_month      = date('Y-m', strtotime('first day of -1 month'));

            $parcels = Parcel::where('created_at', '>=', $start_month . '-01' . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')) . ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month . '-01' . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')) . ' 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 6; $i >= 1; $i--) {

                $start = date('Y-m-d', strtotime('first day of -' . $i . ' month'));
                $end   = date('Y-m-d', strtotime('last day of -' . $i . ' month'));

                $merchant_parcels             = $parcels->where('created_at', '>=', $start . ' 00:00:00' . '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end . ' 23:59:59' . '%');

                // dates
                $data['dates'][]              = $start = date('Y-m', strtotime('first day of -' . $i . ' month'));

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', '>=', $start_month)->where('date', '<=', $end_month)->sum('amount');
            $start = date('Y-m-d', strtotime('first day of -6 month'));
            $end = date('Y-m-d', strtotime('last day of -1 month'));

            $profits = $this->profits($start, $end);

        elseif ($report_type == 'this_year') :

            $start_month = date('Y-' . '01');
            $end_month = date('Y-' . '12');

            $parcels = Parcel::where('created_at', '>=', $start_month . '-01 00:00:00')
                ->where('created_at', '<=', $end_month . '-31 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month . '-01 00:00:00')
                ->where('created_at', '<=', $end_month . '-31 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 1; $i <= 12; $i++) {

                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }

                $created_at = date('Y-' . $i);

                $start = $created_at . '-01';
                $end   = $created_at . '-' . $this->getLastDateOfMonth(01);

                $merchant_parcels             = $parcels->where('created_at', '>=', $start . ' 00:00:00' . '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end . ' 23:59:59' . '%');
                // dates
                $data['dates'][]              = $created_at;

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', '>=', $start_month)->where('date', '<=', $end_month)->sum('amount');

            $profits = $this->profits($start_month . '-01', $end_month . '-31');
        elseif ($report_type == 'last_year') :
            $start_month = date('Y-' . '01', strtotime('-1 year'));
            $end_month = date('Y-' . '12', strtotime('-1 year'));

            $parcels = Parcel::where('created_at', '>=', $start_month . '-01 00:00:00')
                ->where('created_at', '<=', $end_month . '-31 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(function ($q) {
                        $q->where('branch_id', Sentinel::getUser()->branch_id)
                            ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                            ->orWhereNull('pickup_branch_id')
                            ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month . '-01 00:00:00')
                ->where('created_at', '<=', $end_month . '-31 23:59:59')
                ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for ($i = 1; $i <= 12; $i++) {

                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }

                $created_at = date('Y-' . $i, strtotime('-1 year'));

                $start = $created_at . '-01';
                $end   = $created_at . '-' . $this->getLastDateOfMonth(01);

                $merchant_parcels             = $parcels->where('created_at', '>=', $start . ' 00:00:00' . '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end . ' 23:59:59' . '%');

                // dates
                $data['dates'][]              = $created_at;

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', '>=', $start_month)->where('date', '<=', $end_month)->sum('amount');

            $profits = $this->profits($start_month . '-01', $end_month . '-31');
        elseif ($report_type == 'lifetime') :

            $parcels = Parcel::when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(function ($q) {
                    $q->where('branch_id', Sentinel::getUser()->branch_id)
                        ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                        ->orWhereNull('pickup_branch_id')
                        ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                });
            })->latest()->get();

            $parcel_delivered = ParcelEvent::whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
                ->where('reverse_status', null)
                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            $start_year = date('Y', strtotime($parcels->min('date')));
            $last_year = date('Y');

            if ($start_year - $last_year == 0) :
                $start_year = $last_year;
                for ($i = 1; $i <= 12; $i++) {

                    if ($i < 10) {
                        $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }

                    $created_at = date('Y-' . $i);

                    $start = $created_at . '-01';
                    $end   = $created_at . '-' . $this->getLastDateOfMonth(01);

                    $merchant_parcels             = $parcels->where('created_at', '>=', $start . ' 00:00:00' . '%');
                    $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end . ' 23:59:59' . '%');
                    // dates
                    $data['dates'][]              = $created_at;

                    // count
                    $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                    $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                    $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                    $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                    $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                    $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                    $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
                }
            else :
                for ($i = $start_year; $i <= $last_year; $i++) {
                    $start = $i . '-01-01';
                    $end   = $i . '-12-31';

                    $merchant_parcels             = $parcels->where('created_at', '>=', $start . ' 00:00:00' . '%');
                    $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end . ' 23:59:59' . '%');

                    $data['dates'][]              = $i;

                    // count
                    $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                    $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                    $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                    $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                    $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                    $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                    $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
                }
            endif;

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', '>=', $start_year . '-01-01')->where('date', '<=', $last_year . '-12-31')->sum('amount');

            $profits = $this->profits($start_year . '-01-01', $last_year . '-12-31');
        endif;

        $counts = $this->get_counts($parcels);

        return view('admin.dashboard.report', compact('data', 'counts', 'profits'))->render();
    }

    public function staffBalance()
    {
        $accounts = Account::get();

        $total_staff_account_balance = 0;

        foreach ($accounts as $account) {
            $total = $account->incomes()->sum('amount')
                   + $account->fundReceives()->sum('amount')
                   - $account->expenses()->sum('amount')
                   - $account->fundTransfers()->sum('amount');

            $total_staff_account_balance += $total;
        }
        $data = $total_staff_account_balance;


        return $data;

    }

    public function deliverymanBalance()
    {
        $deliverymen = DeliveryMan::active()->get();
        $total_deliveryman_balance = 0;

        foreach ($deliverymen as $deliveryman) {
            $balance = $deliveryman->balance($deliveryman->id);
            $total_deliveryman_balance += $balance;
        }
        $data = $total_deliveryman_balance;

        return $data;

    }

    public function merchantBalance()
    {
        $merchants = Merchant::active()->get();
        $total_merchant_balance = 0;

        foreach ($merchants as $merchant) {
            $balance = $merchant->balance($merchant->id);
            $total_merchant_balance += $balance;
        }
        $data = format_price($total_merchant_balance);

        return $data;

    }


    public function totalPendingPayout()
    {
        $data               = MerchantWithdraw::where('status', 'pending')->get()->sum('amount');

        return $data;

    }

    public function customDateRange(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $parcels = Parcel::where('created_at', '>=', $start_date . ' 00:00:00')
            ->where('created_at', '<=', $end_date . ' 23:59:59')
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(function ($q) {
                    $q->where('branch_id', Sentinel::getUser()->branch_id)
                        ->orWhere('pickup_branch_id', Sentinel::getUser()->branch_id)
                        ->orWhereNull('pickup_branch_id')
                        ->orWhere('transfer_to_branch_id', Sentinel::getUser()->branch_id);
                });
            })
            ->latest()->get();

        $parcel_delivered = ParcelEvent::whereDate('created_at', '>=', $start_date . ' 00:00:00')
            ->whereDate('created_at', '<=', $end_date . ' 23:59:59')
            ->whereIn('title', ['parcel_delivered_event', 'parcel_partial_delivered_event', 'parcel_partial_delivered_event'])
            ->where('reverse_status', null)
            ->get();

        $data['totalParcelDelivered'] = $parcel_delivered->count();

        $start_date = date_create($start_date);
        $end_date = date_create($end_date);

        $different_days = date_diff($start_date, $end_date);

        $days = $different_days->format("%a");

        if ($days == 0) :

            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for ($i = 0; $i <= 11; $i++) {

                $j = $i * 2;

                $j = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start = $request->start_date . ' ' . $j . ':00:00';
                $end =  $request->start_date . ' ' . $in . ':59:59';

                $merchant_parcels             = $parcels->where('created_at', '>=', $start);
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }
        else :
            for ($i = $days; $i >= 0; $i--) {
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                //date range parcels

                $created_at = date('Y-m-d', strtotime('-' . $i . ' days', strtotime($request->end_date)));

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at . ' 00:00:00' . '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $created_at . ' 23:59:59' . '%');

                // dates
                $data['dates'][]              = $created_at;

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }
        endif;
        $data['totalParcels']       = $parcels->count();
        $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
        $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
        $data['totalDelivered']     = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

        $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');

        $profits = $this->profits($request->start_date, $request->end_date);

        $counts = $this->get_counts($parcels);

        return view('admin.dashboard.report', compact('data', 'counts', 'profits'))->render();
    }

    public function profits($start, $end)
    {

        $total_vat_income                         = GovtVat::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'income')
                                                    ->where('parcel_id', '!=', '')->whereIn('source', ['parcel_delivery', 'parcel_return'])
                                                    ->sum('amount');

        $total_vat_expense                        = GovtVat::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'expense')
                                                    ->where('parcel_id', '!=', '')->whereIn('source', ['parcel_delivery', 'parcel_return'])
                                                    ->sum('amount');
        $return_income                            = MerchantAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'income')
                                                    ->where(function ($query) {
                                                        $query->where('source', 'parcel_return')
                                                            ->orWhere(function ($query) {
                                                                $query->where('source', 'vat_adjustment')
                                                                    ->whereIn('details', ['govt_vat_for_parcel_return', 'govt_vat_for_parcel_return_reversed']);
                                                            });
                                                    })
                                                    ->sum('amount');
        $return_expense                            = MerchantAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'expense')
                                                    ->where(function ($query) {
                                                        $query->where('source', 'parcel_return')
                                                            ->orWhere(function ($query) {
                                                                $query->where('source', 'vat_adjustment')
                                                                    ->whereIn('details', ['govt_vat_for_parcel_return', 'govt_vat_for_parcel_return_reversed']);
                                                            });
                                                    })
                                                    ->sum('amount');

        $data['total_parcel_return_charge']       = $return_expense - $return_income;


        $data['total_vat']                        = $total_vat_income - $total_vat_expense;


        $total_charge_vat                         =  Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->withPermission()
                                                    ->where(function ($query) {
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                                                    })
                                                    ->sum('total_delivery_charge');

        $data['total_charge_vat']                 =  $total_charge_vat + $data['total_parcel_return_charge'];


        $total_delivery_charge_income             = DeliveryManAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('source', ['pickup_commission', 'parcel_delivery', 'parcel_return'])
                                                    ->where('type', 'income')
                                                    ->sum('amount');

        $total_delivery_charge_expense            = DeliveryManAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('source', ['pickup_commission', 'parcel_delivery', 'parcel_return'])
                                                    ->where('type', 'expense')
                                                    ->sum('amount');



        $data['total_delivery_charge']            = $total_delivery_charge_expense - $total_delivery_charge_income;


        $data['total_fragile_charge']             = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->withPermission()
                                                    ->where(function ($query) {
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                                                    })
                                                    ->sum('fragile_charge');

        $data['total_packaging_charge']           = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->withPermission()
                                                    ->where(function ($query) {
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                                                    })
                                                    ->sum('packaging_charge');



        $data['total_profit']                      = (abs($data['total_charge_vat']) + $data['total_delivery_charge']) -  $data['total_vat'];


        $data['total_payable_to_merchant']         = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query) {
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                                                    })
                                                    ->sum('price');


        $data['total_paid_to_merchant']           = MerchantWithdraw::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('status', ['processed', 'pending', 'approved'])
                                                    ->sum('amount');

        $data['pending_payouts']                 = MerchantWithdraw::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('status', ['pending', 'approved'])
                                                    ->sum('amount');

        $data['processed_payouts']               = MerchantWithdraw::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('status', 'processed')
                                                    ->sum('amount');


        $data['total_paid_by_merchant']           = CompanyAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('source', 'delivery_charge_receive_from_merchant')
                                                    ->where('type', 'income')
                                                    ->where('merchant_id', '!=', '')
                                                    ->sum('amount');


        $data['current_payable']                  = abs($data['total_payable_to_merchant']) + $data['total_paid_by_merchant'] - $data['total_paid_to_merchant'] -  $data['total_charge_vat'];

        $data['total_cash_on_delivery']           = Parcel::where('date', '>=', $start)
                                                    ->withPermission()
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query) {
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                                                    })
                                                    ->sum('price');

        $data['total_paid_by_delivery_man']       = DeliveryManAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('delivery_man_id', '!=', '')
                                                    ->where('source', 'cash_given_to_staff')
                                                    ->where('type', 'expense')
                                                    ->sum('amount');

        $data['total_expense_from_account']        = CompanyAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'expense')
                                                    ->where('create_type', 'user_defined')
                                                    ->sum('amount');



        $start                                     = $start . ' ' . '00:00:00';
        $end                                       =  $end . ' ' . '23:59:59';

        $data['total_bank_opening_balance']        = Account::where('created_at', '>=', $start)
                                                    ->where('created_at', '<=', $end)
                                                    ->sum('balance');

        return $data;
    }

    public function getLastDateOfMonth($month)
    {
        $date = date('Y') . '-' . $month . '-01';  //make date of month
        return date('t', strtotime($date));
    }


    public function oldBalance()
    {
        DB::beginTransaction();
        try {
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function mergeUpdate()
    {
        Artisan::call('database:backup');
    }


}
