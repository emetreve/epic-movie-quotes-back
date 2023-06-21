<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'image'         => 'required|image|mimes:jpeg,png,jpg',
			'genres'        => 'required',
			'nameGe'        => 'required',
			'nameEn'        => 'required',
			'year'          => 'required',
			'revenue'       => 'required',
			'directorEn'    => 'required',
			'directorGe'    => 'required',
			'descriptionEn' => 'required',
			'descriptionGe' => 'required',
		];
	}
}
