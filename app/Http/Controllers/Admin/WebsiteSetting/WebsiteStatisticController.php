<?php

namespace App\Http\Controllers\Admin\WebsiteSetting;

use App\DataTables\Admin\WebsiteStatisticDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WebsiteStatisticRequest;
use App\Repositories\LanguageRepository;
use App\Repositories\Admin\WebsiteStatisticRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteStatisticController extends Controller
{
    protected $statistic;

    public function __construct(WebsiteStatisticRepository $statistic)
    {
        $this->statistic = $statistic;
    }

    public function index(WebsiteStatisticDataTable $dataTable)
    {
        return $dataTable->render('admin.website.statistic.index');
    }

    public function create(Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {

        $lang        = $request->lang ?? app()->getLocale();
        $data        = [
            'lang'                 => $lang,
        ];
        return view('admin.website.statistic.create', $data);
    }

    public function store(WebsiteStatisticRequest $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        DB::beginTransaction();
        try {
            $this->statistic->store($request);
            Toastr::success(__('create_successful'));

            DB::commit();

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('statistics.index'),
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Something went wrong, please try again']);
        }
    }

    public function edit($id, LanguageRepository $language, Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $statistic = $this->statistic->find($id);
            $lang        = $request->lang ?? app()->getLocale();
            $data        = [
                'lang'                 => $lang,
                'statistic_language' => $this->statistic->getByLang($id, $lang),
                'statistic'          => $statistic,
            ];

            return view('admin.website.statistic.edit', $data);
        }catch (\Exception $e) {
            Toastr::error('Something went wrong, please try again');

            return back();
        }
    }

    public function update(WebsiteStatisticRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        DB::beginTransaction();
        try {
            $this->statistic->update($request, $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('statistics.index'),
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong, please try again']);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->statistic->destroy($id);
            Toastr::success(__('delete_successful'));
            $data = [
                'status'  => 'success',
                'message' => __('delete_successful'),
                'title'   => __('success'),
            ];

            return response()->json($data);
        }catch (\Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => 'Something went wrong, please try again',
                'title'   => __('error'),
            ];

            return response()->json($data);
        }
    }

    public function statusChange(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->statistic->status($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        }catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => 'Something went wrong, please try again',
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }
}
