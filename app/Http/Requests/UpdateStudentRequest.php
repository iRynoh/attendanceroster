<?php

namespace App\Http\Requests;

use App\Models\Student;

class UpdateStudentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|string',
        ];
    }

}
