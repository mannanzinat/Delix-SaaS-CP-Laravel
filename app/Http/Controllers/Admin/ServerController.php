<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\Admin\ServerDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use App\Repositories\ServerRepository;
use App\Http\Requests\Admin\ServerRequest;
use App\Http\Requests\Admin\ServerUpdateRequest;

class ServerController extends Controller
{
    protected $repo;

    public function __construct(ServerRepository $repo)
    {
        $this->repo   = $repo;

    }

    public function index(ServerDataTable $dataTable)
    {
        try {
            return $dataTable->render('backend.admin.cloud_server.all_server');
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }


    public function create()
    {
        try {
            return view('backend.admin.cloud_server.add_server');
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }
   
    public function store(ServerRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        DB::beginTransaction();
        try {

            $result = $this->repo->store($request->all());
            if($result){
                DB::commit();
                Toastr::success(__('create_successful'));
                return redirect()->route('cloud-server.index');
            }

        } catch (Exception $e) {
            DB::rollback();
            Toastr::error('invalid_credential');
            return redirect()->route('cloud-server.index');

        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $server    = $this->repo->find($id);
            $data      = [
                'server'    => $server,
            ];
            return view('backend.admin.cloud_server.edit_server', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }

    public function update(ServerUpdateRequest $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return response()->json(['error' => __('this_function_is_disabled_in_demo_server')], 422);
        }

        DB::beginTransaction();

        try {
            $updateResult = $this->repo->update($request->all(), $id);

            if ($updateResult) {
                DB::commit();
                Toastr::success(__('update_successful'));
                return redirect()->route('cloud-server.index');
            }else{
                Toastr::error(__('invalid_credential'));
                return redirect()->route('cloud-server.index');

            }
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('clients.delete');

        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->destroy($id);

            $data = [
                'status'  => 'success',
                'message' => __('update_successful'),
                'title'   => __('success'),
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => $e->getMessage(),
                'title'   => 'error',
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
            $this->repo->statusChange($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function defaultChange(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            return response()->json([
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ]);
        }

        try {
            $this->repo->defaultChange($request->all());

            return response()->json([
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ]);
        }
    }



}
