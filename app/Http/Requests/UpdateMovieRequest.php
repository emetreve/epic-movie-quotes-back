<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp',
			'genres'        => 'nullable',
			'name'          => 'nullable',
			'year'          => 'nullable',
			'revenue'       => 'nullable',
			'director'      => 'nullable',
			'description'   => 'nullable',
		];
	}
}
