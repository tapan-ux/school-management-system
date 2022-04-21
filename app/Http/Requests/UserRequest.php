<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->update_profile) {
            $rules['role_id' ] = 'sometimes|nullable';
        } else {
            $rules['role_id' ] = 'required';
        }
        $other_validation =  [
            'name'  => 'required|',
            'email' => 'required|email|unique:users,email,'.$this->user,
            'phone_number' => 'required',
            'citizenship_no' => 'sometimes|nullable',
            'gender' => 'required',
            'address' => 'sometimes|nullable',
            'date_of_join' => 'sometimes|nullable',
            'dob' => 'sometimes|nullable',
            'blood_group' => 'sometimes|nullable',
            'citizenship' => 'image|mimes:jpeg,png,jpg|max:10240',
            'image' => 'image|mimes:jpeg,png,jpg|max:10240',
            'other_docs' => 'file|sometimes|nullable'
        ];

        return array_merge($rules, $other_validation);
    }


}
