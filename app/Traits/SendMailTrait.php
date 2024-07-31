<?php

namespace App\Traits;

use App\Mail\SendSmtpMail;
use Illuminate\Support\Facades\Mail;

trait SendMailTrait
{
    protected function sendMail($to, $view, $data, $sender = null): bool
    {
        try {
            $engine             = env('MAIL_MAILER');

            $from               = $sender ?? ($engine == 'smtp' ? env('MAIL_FROM_ADDRESS') : env('SENDER_MAIL'));

            $attribute = [
                'from'      => $from,
                'content'   => $data,
                'view'      => $view,
            ];

            $emails = is_array($to) ? array_filter($to) : [$to];


            Mail::to($emails)->send(new SendSmtpMail($attribute));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
