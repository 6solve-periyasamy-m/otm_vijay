<?php

namespace App\Mail\Storage;

use Illuminate\Contracts\Mail\Attachable;

class Attachment
{
    public string $data;
    public string $filename;
    public array $opts;

    public function __construct(Attachable|\Illuminate\Mail\Attachment|string $data, string $filename, array $opts)
    {
        $this->data = $data;
        $this->filename = $filename;
        $this->opts = $opts;
    }
}
