<?php

namespace App\Http\Controllers\Admin\System;

use App\Exceptions\MailDisabledException;
use App\Http\Controllers\Controller;
use App\Repository\Mailing\MailRepository;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function edit(string $mail)
    {
        $template = MailRepository::getMail($mail);
        if (!isset($mail)) abort(404);
        return view('pages.email.editor', ['mail' => $template,]);
    }

    public function update(Request $request, string $mail): RedirectResponse
    {
        $template = MailRepository::getMail($mail);
        if (!isset($template)) abort(404);
        $template->update($request->input('subject'), $request->input('body'));
        return redirect()->route('email.edit', ['mail' => $mail,]);
    }

    public function demo(string $mail): RedirectResponse
    {
        $template = MailRepository::getMail($mail);
        if (!isset($template)) abort(404);
        try {
            $sent = $template->send(Auth::user()->email, null, [], "",true);
        } catch (MailDisabledException $e) {
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        if (!$sent) {
            return back()->withErrors(['msg' => 'Demo mail failed to send.']);
        }
        return back()->with(['success' => 'Demo mail sent successfully']);
    }
}
