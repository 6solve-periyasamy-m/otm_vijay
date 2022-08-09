<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface SerializesToJson
{
    public function serialize(): string;
    public static function deserialize(array $data): Model;
}
