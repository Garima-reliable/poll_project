<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PollVoting extends BaseRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'poll_id' => 'required',
            'option_id' => 'required'
        ];
    }

    public function messages() {
        return [
            'poll_id.required' => 'Please enter poll id.',
            'option_id.required' => 'Please select option.',
        ];
    }

}
