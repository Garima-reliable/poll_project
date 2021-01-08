<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreatePoll extends BaseRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'poll_name' => 'required|string',
            'poll_desc' => 'required|string',
            'poll_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
            'poll_optn' => 'required|array|min:2'
        ];
    }

    public function messages() {
        return [
            'poll_name.required' => 'Please enter poll name',
            'poll_name.string' => 'Invalid poll name',
            'poll_desc.required' => 'Please enter poll description',
            'poll_desc.string' => 'Invalid poll description',
            'poll_time.required' => 'Please enter date and time(ex : 1990-12-24 14:52:20)',
            'poll_time.date_format' => 'Invalid poll timing',
            'poll_time.after_or_equal' => 'Timing cannot be less then now.',
            'poll_optn.required' => 'The poll options is required.',
            'poll_optn.min' => 'The poll options must have at least 2 items.',
            'poll_opt.array' => 'The poll options must be an list.',
        ];
    }

}
