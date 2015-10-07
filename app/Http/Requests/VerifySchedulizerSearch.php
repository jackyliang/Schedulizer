<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class VerifySchedulizerSearch extends Request {

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
		return [
            'q' => 'required'
		];
	}

    /**
     * Custom message
     * @return array
     */
    public function messages()
    {
        return [
            'q.required' => 'I can\'t search for nothing, silly'
        ];
    }


}
