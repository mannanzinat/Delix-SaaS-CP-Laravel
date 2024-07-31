<?php

namespace App\Repositories;

use App\Models\EmailTemplate;

class EmailTemplateRepository
{
    public function get($id)
    {
        return EmailTemplate::findorfail($id);
    }

    public function emailTemplate()
    {
        return EmailTemplate::all();
    }

    public function testMail()
    {
        return EmailTemplate::where('identifier', 'test_email')->first();
    }

    public function emailConfirmation()
    {
        return EmailTemplate::where('identifier', 'confirmation_email')->first();
    }

    public function welcomeMail()
    {
        return EmailTemplate::where('identifier', 'welcome_email')->first();
    }


    public function changePass()
    {
        return EmailTemplate::where('identifier', 'password_reset_email')->first();
    }

    public function recoveryMail()
    {
        return EmailTemplate::where('identifier', 'recovery_email')->first();
    }

    public function update($request)
    {
        $id = $request['id'];
        return EmailTemplate::find($id)->update($request);
    }
}
