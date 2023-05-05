<?php

namespace App\Mail\Storage;

use App\Exceptions\MailDisabledException;
use App\Mail\TemplatedMailable;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Support\Facades\Mail;
use Log;
use Settings;


abstract class TemplatedMail
{
    protected string|null $code;
    protected Generator $faker;

    public function __construct(string|null $code = null)
    {
        $this->code = $code;
        $this->faker = Faker::create();
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

    public abstract function getShortcodes($model = null): array;

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
        $template = new TemplatedMailable($this->getFormattedSubject($model), $this->getFormattedBody($model));
        foreach ($attachments as $attachment) {
            $template->attachData($attachment->data, $attachment->filename, $attachment->opts);
        }
        return $template;
    }

    public final function replaceShortcodes(string $body, $model = null): string
    {
        $replacement = $body;
        foreach ($this->getShortcodes($model) as $key => $value) {
            $replacement = str_replace('[' . $key . ']', $value, $replacement);
        }
        return $replacement;
    }

    /**
     * @param string $email
     * @param null $model
     * @param Attachment[] $attachments
     * @param bool $force
     * @return bool
     * @throws MailDisabledException
     */
    public final function send(string $email, $model = null, array $attachments = [], bool $force = false): bool
    {
        if (!flag('system.mail.enabled', true) && !$force ) {
            throw new MailDisabledException('Sending Emails is disabled on this system');
        }
        try {
            $mail = Mail::to($email);
            if (config('mail.bcc') !== null) { $mail->bcc(config('mail.bcc')); }
            $mail->send($this->getTemplatedMailable($model, $attachments));
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public final function update(string $subject, string $body): void
    {
        Settings::set("email.{$this->code}.subject", $subject);
        Settings::set("email.{$this->code}.template", $body);
    }

    public final function getEditUrl(): string
    {
        return route('email.edit', ['mail' => $this->code,]);
    }

    public final function getUpdateUrl(): string
    {
        return route('email.update', ['mail' => $this->code,]);
    }

    public final function getDemoUrl(): string
    {
        return route('email.demo', ['mail' => $this->code,]);
    }
}
