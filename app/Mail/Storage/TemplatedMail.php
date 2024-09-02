<?php

namespace App\Mail\Storage;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Mail\TemplatedMailable;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Log;
use Settings;
use Validator;

abstract class TemplatedMail
{
    protected string|null $code;
    protected Generator $faker;
    protected string $email;
    protected string $name;

    public function __construct(string|null $code = null)
    {
        $this->code = $code;
        $this->faker = Faker::create();
        $defaultEmail = config('mail.from.address', config('mail.mailers.smtp.username', 'info@octopustravelmatrix.com'));
        $defaultName = config('mail.from.name', setting('company.name', 'Octopus Travel Matrix'));
        if (config('mail.individual', false)) {
            $user = Auth::user();
            $this->email = $user->email ?? $defaultEmail;
            // If sending as a user, prepend the users name
            if ($user !== null) {
                $this->name = "{$user->name} - " . $defaultName;
            } else {
                $this->name = $defaultName;
            }
        } else {
            $this->email = $defaultEmail;
            $this->name = $defaultName;
        }
    }

    public function getName(): ?string
    {
        return ucwords(str_replace('-', ' ', $this->code));
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    abstract public function getShortcodes($model = null): array;

    public function getSubject(): string
    {
        if ($this->code === null) return "";
        return setting("email.{$this->code}.subject", '');
    }

    public function getBody(): string
    {
        if ($this->code === null) return "";
        return setting("email.{$this->code}.template", '');
    }

    public function getFormattedSubject($model = null): string
    {
        return $this->replaceShortcodes($this->getSubject(), $model);
    }

    public function getFormattedBody($model = null): string
    {
        return $this->replaceShortcodes($this->getBody(), $model);
    }

    public function getTemplatedMailable($model = null, array $attachments = []): TemplatedMailable
    {

        $template = new TemplatedMailable($this->getFormattedSubject($model), $this->getFormattedBody($model), $this->email, $this->name);
        foreach ($attachments as $attachment) {
            $template->attachData($attachment->data, $attachment->filename, $attachment->opts);
        }
        return $template;
    }

    final public function replaceShortcodes(string $body, $model = null): string
    {
        $replacement = $body;
        foreach ($this->getShortcodes($model) as $key => $value) {
            $replacement = str_replace('[' . $key . ']', $value, $replacement);
        }
        return $replacement;
    }

    /**
     * @param string|null $email
     * @param null $model
     * @param Attachment[] $attachments
     * @param bool $force
     * @return bool
     * @throws MailDisabledException
     * @throws MailFailedException
     */
    final public function send(string|null $email, $model = null, array $attachments = [], bool $force = false): bool
    {
        if (!$force && !flag('system.mail.enabled', true)) {
            throw new MailDisabledException('Sending Emails is disabled on this system');
        }
        $validator = Validator::make(['email' => $email,], ['email' => 'required|email:rfc,dns'], [
            'email.required' => 'Recipient does not have an email address',
            'email.email' => 'Recipient does not have a valid email address',
        ]);
        if ($validator->fails()) {
            throw new MailFailedException($validator->errors()->first());
        }
        try {
            if (empty(config('mail.from.address'))) return false;
            $mail = Mail::to($email);
            $bcc = [];
            if (!empty(config('mail.bcc'))) {
                $bcc[] = config('mail.bcc');
            }
            if (flag('mail.bcc-sender', false)) {
                $bcc = array_merge($bcc, [$this->email,]);
            }
            $mail->bcc($bcc);
            $bcc = " and " . implode(', ', $bcc);
            $mail->send($this->getTemplatedMailable($model, $attachments));
            Log::channel('mail')->debug(class_basename(get_class($this)) . " mail sent to {$email}" . ($bcc ?? ""));
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

    final public function update(string $subject, string $body): void
    {
        Settings::set("email.{$this->code}.subject", $subject);
        Settings::set("email.{$this->code}.template", $body);
    }

    final public function getEditUrl(): string
    {
        return route('email.edit', ['mail' => $this->code,]);
    }

    final public function getUpdateUrl(): string
    {
        return route('email.update', ['mail' => $this->code,]);
    }

    final public function getDemoUrl(): string
    {
        return route('email.demo', ['mail' => $this->code,]);
    }
}
