<?php

namespace App\Http\Controllers;

use App\Repository\MailRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function edit(string $mail)
    {
        $details = MailRepository::getMailTemplate($mail);
        if (!isset($details)) abort(404);
        return view('pages.email.editor', [
            'body' => $details['template'],
            'codes' => $details['shortcodes'],
            'action' => route('email.update', ['mail' => $mail,]),
            'demo' => route('email.demo', ['mail' => $mail,]),
        ]);
    }

    public function update(Request $request, string $mail): RedirectResponse
    {
        $update = MailRepository::updateMailTemplate($mail, $request->input('body'));
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
