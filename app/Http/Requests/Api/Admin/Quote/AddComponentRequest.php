<?php

namespace App\Http\Requests\Api\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int[] $ids IDs of components to be added
 * @property string $type Tour component type
 */
class AddComponentRequest extends FormRequest
{

}
