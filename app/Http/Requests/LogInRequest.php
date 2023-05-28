<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogInRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'password' => 'required',
			'user'     => 'required|min:3',
			'remember' => 'boolean|nullable',
		];
	}

	public function validationData()
	{
		$this->merge([
			'remember' => (bool) $this->input('remember'),
		]);

		return parent::validationData();
	}
}
