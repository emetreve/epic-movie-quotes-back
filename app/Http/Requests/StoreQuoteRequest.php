<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$locale = $this->input('locale');
		app()->setLocale($locale);

		parent::prepareForValidation();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'image'    => 'required|image|mimes:jpeg,png,jpg',
			'bodyEn'   => 'required',
			'bodyGe'   => 'required',
			'movie_id' => 'required',
			'user_id'  => 'required',
		];
	}
}
