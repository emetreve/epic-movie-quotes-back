<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$locale = $this->input('locale');
		app()->setLocale($locale);

		parent::prepareForValidation();
	}

	public function rules(): array
	{
		return [
			'username' => 'nullable|min:3|unique:users,name',
			'password' => 'nullable|confirmed|max:15|min:8',
			'avatar'   => 'nullable|image|mimes:jpg,jpeg,png',
			'email'    => 'nullable|email|unique:users,email',
			'locale'   => 'nullable|string',
		];
	}
}
