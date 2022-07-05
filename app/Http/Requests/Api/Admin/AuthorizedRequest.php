<?php

namespace App\Http\Requests\Api\Admin;

use App\Models\User;
use App\Repository\Authentication\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $__api_token
 */
class AuthorizedRequest extends FormRequest
{
    public function getTokenUser(): ?User
    {
        return UserRepository::getUserFromToken($this->__api_token);
    }
}
