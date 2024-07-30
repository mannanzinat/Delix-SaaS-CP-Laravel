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


            // \Log::info('Sending email', [
            //     'to'   => $emails,
            //     'from' => $from,
            //     'view' => $view,
            //     'data' => $data,
            // ]);

            // if (!isset($attribute['content']) || !is_array($attribute['content'])) {
            //     throw new \Exception('Email content is not properly set.');
            // }

            // if (!isset($data['confirmation_link']) || !isset($data['user'])) {
            //     throw new \Exception('Required email content keys are missing.');
            // }

  
            // dd($emails);

            Mail::to($emails)->send(new SendSmtpMail($attribute));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
