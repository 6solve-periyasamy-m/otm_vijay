<?php

namespace App\Http\Controllers;

use App\Repository\Mailing\MailRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function edit(string $mail)
    {
        if (!MailRepository::doesTemplateExist($mail)) abort(404);
        $details = MailRepository::getMailTemplate($mail);
        return view('pages.email.editor', [
            'templateName' => ucwords(str_replace('-', ' ', $mail)),
            'body' => $details['template'],
            'subject' => $details['subject'],
            'codes' => $details['shortcodes'],
            'action' => route('email.update', ['mail' => $mail,]),
            'demo' => route('email.demo', ['mail' => $mail,]),
        ]);
    }

    public function update(Request $request, string $mail): RedirectResponse
    {
        $update = MailRepository::updateMailTemplate($mail, $request->input('body'));
        $update = $update && MailRepository::updateMailSubject($mail, $request->input('subject'));
        if (!$update) abort(404);
        return redirect()->route('email.edit', ['mail' => $mail,]);
    }

    public function demo(string $mail): RedirectResponse
    {
        $sent = MailRepository::sendDemoMailable($mail);
        if (!$sent) abort(404);
        return redirect()->route('email.edit', ['mail' => $mail,]);
    }
}
