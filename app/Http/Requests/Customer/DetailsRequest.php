<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Password;

/**
 * Basic Details
 * @property string $title
 * @property string $first_name
 * @property string|null $middle_names
 * @property string $last_name
 * @property string $date_of_birth
 * @property string $mobile_number
 * @property string|null $other_phone_number
 * Home Address
 * @property string|null $home_address_line_1
 * @property string|null $home_address_line_2
 * @property string|null $home_town
 * @property string|null $home_region
 * @property string|null $home_country_id
 * @property string|null $home_postcode
 * Billing Address
 * @property string|null $billing_address_line_1
 * @property string|null $billing_address_line_2
 * @property string|null $billing_town
 * @property string|null $billing_region
 * @property string|null $billing_country_id
 * @property string|null $billing_postcode
 * Emergency Contact Information
 * @property string $emergency_contact_name
 * @property string $emergency_contact_relationship
 * @property string $emergency_contact_telephone
 * Passport Details
 * @property string|null $passport_first_name
 * @property string|null $passport_middle_name
 * @property string|null $passport_last_name
 * @property string|null $gender
 * @property string|null $passport_number
 * @property string|null $passport_country
 * @property string|null $passport_issue_date
 * @property string|null $passport_expiry_date
 * Miscellaneous
 * @property string|null $t_shirt_size_id
 * @property string|null $hat_size_id
 * @property string|null $dietary_notes
 * @property string|null $mobility_notes
 * @property string|null $other_notes
 * @property UploadedFile|null $profile_picture
 * Password (Self Only)
 * @property string|null $current_password
 * @property string|null $new_password
 * @property string|null $new_password_confirmation
 */
class DetailsRequest extends FormRequest
{
    public function getCustomerDetails(): array
    {
        return [
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_names' => $this->middle_names,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'mobile_number' => $this->mobile_number,
            'other_phone_number' => $this->other_phone_number,
            'gender' => $this->gender,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_telephone' => $this->emergency_contact_telephone,
            'passport_first_name' => $this->passport_first_name,
            'passport_middle_name' => $this->passport_middle_name,
            'passport_last_name' => $this->passport_last_name,
            'passport_number' => $this->passport_number,
            'passport_country_of_issue' => $this->passport_country,
            'passport_issue_date' => $this->passport_issue_date,
            'passport_expiry_date' => $this->passport_expiry_date,
            't_shirt_size_id' => $this->t_shirt_size_id,
            'hat_size_id' => $this->hat_size_id,
            'external_notes' => $this->other_notes,
            'dietary_notes' => $this->dietary_notes,
            'mobility_notes' => $this->mobility_notes,  
        ];
    }
    
    public function getHomeAddress(): array
    {
        return [
            'address_line_1' => $this->home_address_line_1,
            'address_line_2' => $this->home_address_line_2,
            'town' => $this->home_town,
            'region' => $this->home_region,
            'country_id' => $this->home_country_id,
            'postcode' => $this->home_postcode,
        ];
    }
    
    public function getBillingAddress(): array
    {
        return [
            'address_line_1' => $this->home_address_line_1,
            'address_line_2' => $this->home_address_line_2,
            'town' => $this->home_town,
            'region' => $this->home_region,
            'country_id' => $this->home_country_id,
            'postcode' => $this->home_postcode,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'nullable',
                'required_with:new_password',
                'current_password'
            ],
            'new_password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'emergency_contact_name' => 'required',
            'emergency_contact_relationship' => 'required',
            'emergency_contact_telephone' => 'required',
        ];
    }
}
