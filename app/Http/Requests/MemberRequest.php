<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MemberRequest
 * @package App\Http\Request\MemberRequest
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required | string | max:255',
            'email'=>'required | email',
            'password'=>'required | string | min:8 | max:255',
            'role' => 'required | in:admin,user'
        ];
    }
}
