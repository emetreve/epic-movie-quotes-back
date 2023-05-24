<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'name'     => 'required|string|unique:users,name|max:15|min:3|regex:/^[a-z0-9]+$/',
			'email'    => 'required|email|unique:users,email',
			'password' => 'required|confirmed|max:15|min:8|regex:/^[a-z0-9]+$/',
		];
	}
}
