<?php

namespace Tests\Traits;

use Settings;
use Config;

trait TestsMailConfig
{
    /**
     * @param string|null $bcc List of BCC targets for the email, aside from specified ones
     * @param bool $individual Should the email send as the logged-in user
     * @param bool $copySender Should the sender be BCCed into the email
     * @param bool $copyConsultant Should the consultant be BCCed into the email
     * @return void
     */
    public function adjustMailConfig(string|null $bcc = null, bool $individual = false, bool $copySender = false, bool $copyConsultant = false): void
    {
        Config::set('mail.bcc', $bcc);
        Config::set('mail.individual', $individual);
        Settings::set('mail.bcc-sender', $copySender);
        Settings::set('mail.bcc-consultant', $copyConsultant);
    }
}
