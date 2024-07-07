<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationUser;
use App\Models\Notification;
use Sentinel;

class NotificationController extends Controller
{
    public function index($id)
    {
        $notification = NotificationUser::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();
        $data = Notification::where('id', $notification->notification_id)->first();

        return redirect()->to($data->url);

    }


}
