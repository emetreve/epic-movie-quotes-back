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
