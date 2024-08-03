<?php

namespace App\Http\Controllers\Client;

use App\DataTables\Client\TicketDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\DepartmentRepository;
use App\Repositories\TicketRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticket;

    public function __construct(TicketRepository $ticket)
    {
        $this->ticket = $ticket;
    }

    public function index(TicketDataTable $dataTable)
    {
        try {
            $data = [
                'open'     => $this->ticket->countByStatus('open'),
                'pending'  => $this->ticket->countByStatus('pending'),
                'answered' => $this->ticket->countByStatus('answered'),
                'close'    => $this->ticket->countByStatus('close'),
                'hold'     => $this->ticket->countByStatus('hold'),
            ];

            return $dataTable->render('website.clientticket.index', $data);
        }catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function create(DepartmentRepository $departmentRepository): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $data = [
                'departments' => $departmentRepository->activeDepartments(),
            ];

            return view('website.clientticket.create', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'department_id' => 'required',
            'subject'       => 'required',
            'priority'      => 'required',
        ]);

        try {
            $this->ticket->store($request->all());
            Toastr::success(__('create_successful'));

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('client.tickets.index'),
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $ticket = $this->ticket->find($id);

            $ticket->replies()->update(['viewed' => 1]);

            $data   = [
                'ticket'  => $ticket,
                'replies' => $ticket->replies,
            ];

            return view('website.clientticket.reply', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::info(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $ticket = $this->ticket->find($id);
            $ticket->update($request->all());

            return redirect()->route('client.tickets.show', $id);
        }catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function reply(Request $request): \Illuminate\Http\JsonResponse
    {

        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'reply' => 'required',
        ]);

        try {
            $this->ticket->reply($request->all());
            Toastr::success(__('reply_successful'));

            $data = [
                'success' => __('reply_successful'),
            ];

            if ($request->return_to_list == 1) {
                $data['route'] = route('client.tickets.index');
            }

            return response()->json($data);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function replyEdit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {

            $data = [
                'reply' => $this->ticket->replyFind($id),
            ];

            return view('website.clientticket.reply_edit', $data);
        }catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function replyUpdate(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'reply' => 'required',
        ]);

        try {
            $reply = $this->ticket->replyUpdate($request->all(), $id);

            Toastr::success(__('reply_updated'));

            $data  = [
                'success' => __('reply_updated'),
                'route'   => route('client.tickets.show', $reply->ticket_id),
            ];

            return response()->json($data);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function replyDelete($id): \Illuminate\Http\JsonResponse
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
            $this->ticket->replyDelete($id);
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
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => __('error'),
            ];

            return response()->json($data);
        }
    }
}
